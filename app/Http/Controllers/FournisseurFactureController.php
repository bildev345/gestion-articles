<?php

namespace App\Http\Controllers;

use App\Models\FournisseurFacture;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\FournisseurFactureDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FournisseurFactureController extends Controller
{
    public function index(Request $request)
    {
        $query = FournisseurFacture::with('fournisseur');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('date_facture', 'like', "%{$search}%")
                  ->orWhereHas('fournisseur', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

          
        $factures = $query->orderBy('id', 'desc')->get();
        return view('fournisseur_factures.index', compact('factures'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $articles = Article::all();
        return view('fournisseur_factures.create', compact('fournisseurs', 'articles'));
    }

public function store(Request $request)
{
    $request->validate([
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'date_facture' => 'required|date',
        'articles' => 'required|array|min:1',

        'articles.*.id' => 'required|exists:articles,id',
        'articles.*.quantite' => 'required|numeric|min:1',
        'articles.*.prix' => 'required|numeric|min:0',
    ]);

    \DB::beginTransaction();

    try {
        $facture = FournisseurFacture::create([
            'fournisseur_id' => $request->fournisseur_id,
            'date_facture' => $request->date_facture,
            'total_ht' => 0,
            'total_tva' => 0,
            'total_ttc' => 0,
        ]);

        $facture->update([
            'numero' => 'FF' . str_pad($facture->id, 4, '0', STR_PAD_LEFT)
        ]);

        $totalHT = 0;
        $tvaGroups = [];

        foreach ($request->articles as $data) {
            if (
                empty($data['id']) ||
                empty($data['quantite']) ||
                $data['quantite'] <= 0
            ) {
                continue;
            }

            $article = Article::findOrFail($data['id']);

            $qte  = (float) $data['quantite'];
            $prix = (float) $data['prix'];
            $tva  = (float) $article->tva;

            $ht = round($prix * $qte, 2);
            $tvaAmount = round($ht * ($tva / 100), 2);
            $ttc = round($ht + $tvaAmount, 2);

            FournisseurFactureDetail::create([
                'fournisseur_facture_id' => $facture->id,
                'article_id' => $article->id,
                'quantite' => $qte,
                'prix_unitaire' => $prix,
                'tva' => $tva,
                'total_ht' => $ht,
                'total_tva' => $tvaAmount,
                'total_ttc' => $ttc,
            ]);

            // achat => stock يزيد
            $article->increment('quantite_stock', $qte);

            $totalHT += $ht;
            $tvaGroups[$tva] = ($tvaGroups[$tva] ?? 0) + $ht;
        }

        if ($totalHT <= 0) {
            throw new \Exception("Veuillez ajouter au moins un article valide.");
        }

        $totalTVA = 0;
        foreach ($tvaGroups as $taux => $montantHT) {
            $totalTVA += round($montantHT * ($taux / 100), 2);
        }

        $totalTTC = round($totalHT + $totalTVA, 2);

        $facture->update([
            'total_ht' => round($totalHT, 2),
            'total_tva' => round($totalTVA, 2),
            'total_ttc' => round($totalTTC, 2),
        ]);

        \DB::commit();

        return redirect()->route('fournisseur_factures.index')
            ->with('success', 'Facture fournisseur créée avec succès');

    } catch (\Exception $e) {
        \DB::rollBack();

        return back()
            ->with('error', $e->getMessage())
            ->withInput();
    }
}
    public function show(FournisseurFacture $fournisseur_facture)
    {
        $fournisseur_facture->load('fournisseur', 'details.article');
        return view('fournisseur_factures.show', compact('fournisseur_facture'));
    }

    public function download(FournisseurFacture $fournisseur_facture)
    {
        $fournisseur_facture->load('fournisseur', 'details.article');
        $pdf = Pdf::loadView('fournisseur_factures.pdf', compact('fournisseur_facture'));
        return $pdf->download($fournisseur_facture->numero . '.pdf');
    }

    public function edit(FournisseurFacture $fournisseur_facture)
    {
        $fournisseurs = Fournisseur::all();
        $articles = Article::all();
        $fournisseur_facture->load('details.article');
        return view('fournisseur_factures.edit', compact('fournisseurs','articles','fournisseur_facture'));
    }

  public function update(Request $request, FournisseurFacture $fournisseur_facture)
{
    $request->validate([
        'fournisseur_id' => 'required|exists:fournisseurs,id',
        'date_facture' => 'required|date',
        'articles' => 'required|array|min:1',

        'articles.*.id' => 'required|exists:articles,id',
        'articles.*.quantite' => 'required|numeric|min:1',
        'articles.*.prix' => 'required|numeric|min:0',
    ]);

    \DB::beginTransaction();

    try {
        $fournisseur_facture->load('details.article');

        // رجع stock القديم حيث achats كانت زادت stock
        foreach ($fournisseur_facture->details as $detail) {
            if ($detail->article) {
                $detail->article->decrement('quantite_stock', $detail->quantite);
            }
        }

        // حذف التفاصيل القديمة
        $fournisseur_facture->details()->delete();

        // تحديث رأس الفاتورة
        $fournisseur_facture->update([
            'fournisseur_id' => $request->fournisseur_id,
            'date_facture' => $request->date_facture,
            'total_ht' => 0,
            'total_tva' => 0,
            'total_ttc' => 0,
        ]);

        $totalHT = 0;
        $tvaGroups = [];

        foreach ($request->articles as $data) {
            if (
                empty($data['id']) ||
                empty($data['quantite']) ||
                $data['quantite'] <= 0
            ) {
                continue;
            }

            $article = Article::findOrFail($data['id']);

            $qte  = (float) $data['quantite'];
            $prix = (float) $data['prix'];
            $tva  = (float) $article->tva;

            $ht = round($prix * $qte, 2);
            $tvaAmount = round($ht * ($tva / 100), 2);
            $ttc = round($ht + $tvaAmount, 2);

            FournisseurFactureDetail::create([
                'fournisseur_facture_id' => $fournisseur_facture->id,
                'article_id' => $article->id,
                'quantite' => $qte,
                'prix_unitaire' => $prix,
                'tva' => $tva,
                'total_ht' => $ht,
                'total_tva' => $tvaAmount,
                'total_ttc' => $ttc,
            ]);

            // achat => stock يزيد
            $article->increment('quantite_stock', $qte);

            $totalHT += $ht;
            $tvaGroups[$tva] = ($tvaGroups[$tva] ?? 0) + $ht;
        }

        if ($totalHT <= 0) {
            throw new \Exception("Veuillez ajouter au moins un article valide.");
        }

        $totalTVA = 0;
        foreach ($tvaGroups as $taux => $montantHT) {
            $totalTVA += round($montantHT * ($taux / 100), 2);
        }

        $totalTTC = round($totalHT + $totalTVA, 2);

        $fournisseur_facture->update([
            'total_ht' => round($totalHT, 2),
            'total_tva' => round($totalTVA, 2),
            'total_ttc' => round($totalTTC, 2),
        ]);

        \DB::commit();

        return redirect()->route('fournisseur_factures.index')
            ->with('success', 'Facture fournisseur modifiée avec succès');

    } catch (\Exception $e) {
        \DB::rollBack();

        return back()
            ->with('error', $e->getMessage())
            ->withInput();
    }
}

    public function duplicate(FournisseurFacture $fournisseur_facture)
    {
        $fournisseur_facture->load('fournisseur', 'details.article');

        // Prepare data for pre-filling create form
        $duplicateData = [
            'fournisseur_id' => $fournisseur_facture->fournisseur_id,
            'date_facture' => now()->format('Y-m-d'),
            'articles' => $fournisseur_facture->details->map(function($detail) {
                return [
                    'id' => $detail->article_id,
                    'quantite' => $detail->quantite,
                ];
            })->toArray()
        ];

        return redirect()->route('fournisseur_factures.create')
            ->with('duplicateData', $duplicateData)
            ->with('message', 'Facture fournisseur dupliquée - modifiez les détails et sauvegardez');
    }

    public function destroy(FournisseurFacture $fournisseur_facture)
    {
        // إعادة المخزون قبل الحذف
        foreach($fournisseur_facture->details as $detail){
            $detail->article->decrement('quantite_stock', $detail->quantite);
        }

        $fournisseur_facture->delete();
        return redirect()->route('fournisseur_factures.index')->with('success', 'Facture fournisseur supprimée');
    }
}