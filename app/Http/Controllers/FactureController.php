<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Client;
use App\Models\Article;
use App\Models\FactureDetail;  // ✅ Votre modèle
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::with('client');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%")  // ✅ 'date'
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        $factures = $query->orderBy('id', 'desc')->paginate(15);
        return view('factures.index', compact('factures'));
    }

    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        $articles = Article::orderBy('designation')->get();
        return view('factures.create', compact('clients', 'articles'));
    }
    

public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'date' => 'required|date',
        'articles.*.id' => 'nullable|exists:articles,id',
        'articles.*.prix' => 'required|numeric|min:0.01',
        'articles.*.quantite' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {

        // ======================
        // 1. Générer numéro facture
        // ======================
        $year = date('Y');

        $lastFacture = Facture::where('numero', 'like', 'F'.$year.'-%')
            ->orderBy('numero', 'desc')
            ->first();

        $nextNumber = $lastFacture
            ? ((int) substr($lastFacture->numero, -4)) + 1
            : 1;

        $numero = 'F'.$year.'-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // ======================
        // 2. Create facture
        // ======================
        $facture = Facture::create([
            'client_id' => $request->client_id,
            'date' => $request->date,
            'date_echeance' => $request->date_echeance ?? null,
            'mode_reglement' => $request->mode_reglement ?? 'Manuelle',
            'total_ht' => 0,
            'total_tva' => 0,
            'total_ttc' => 0,
            'statut' => 'En attente',
            'numero' => $numero,
        ]);

        $totalHT = 0;
        $totalTVA = 0;

        // ======================
        // 3. Articles loop
        // ======================
        foreach ($request->articles as $item) {

            if (empty($item['prix']) || empty($item['quantite'])) {
                continue;
            }

            $prix = (float) $item['prix'];
            $quantite = (int) $item['quantite'];
            $tva = isset($item['tva']) ? (float) $item['tva'] : 20;

            $article = null;

            // ======================
            // 4. STOCK MANAGEMENT
            // ======================
            if (!empty($item['id'])) {

                $article = Article::where('id', $item['id'])
                    ->lockForUpdate()
                    ->first();

                if ($article) {

                    if ($article->quantite_stock < $quantite) {
                        throw new \Exception(
                            "Stock insuffisant pour: " . $article->designation
                        );
                    }

                    // safe decrement
                    $article->decrement('quantite_stock', $quantite);
                }
            }

            // ======================
            // 5. Calculs
            // ======================
            $sousTotalHT = $prix * $quantite;
            $montantTVA = $sousTotalHT * ($tva / 100);

            // ======================
            // 6. Save detail facture
            // ======================
            FactureDetail::create([
                'facture_id' => $facture->id,
                'article_id' => $item['id'] ?? null,
                'designation' => empty($item['id']) ? 'Article libre' : null,
                'prix_unitaire' => $prix,
                'tva' => $tva,
                'quantite' => $quantite,
                'total_ht' => $sousTotalHT,
                'total_tva' => $montantTVA,
                'total_ttc' => $sousTotalHT + $montantTVA,
            ]);

            $totalHT += $sousTotalHT;
            $totalTVA += $montantTVA;
        }

        // ======================
        // 7. Update totals facture
        // ======================
        $facture->update([
            'total_ht' => round($totalHT, 2),
            'total_tva' => round($totalTVA, 2),
            'total_ttc' => round($totalHT + $totalTVA, 2),
        ]);

        DB::commit();

        return redirect()->route('factures.index')
            ->with('success', '✅ Facture #' . $facture->numero . ' créée avec succès !');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->withInput()
            ->with('error', '❌ Erreur: ' . $e->getMessage());
    }
}
    public function show(Facture $facture)
    {
        $facture->load(['client', 'details.article']);  // ✅ 'details'
        return view('factures.show', compact('facture'));
    }

    public function edit(Facture $facture)
    {
        $clients = Client::all();
        $articles = Article::all();
        $facture->load('details.article');
        return view('factures.edit', compact('facture', 'clients', 'articles'));
    }

    // Gardez votre update existant...

    public function update(Request $request, Facture $facture)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'date' => 'required|date',  // ✅ 'date'
        'date_echeance' => 'nullable|date',
        'numero' => 'required|unique:factures,numero,' . $facture->id,
        'articles' => 'required|array|min:1',
        'articles.*.prix' => 'required|numeric|min:0.01',
        'articles.*.quantite' => 'required|integer|min:1',
    ]);

    try {
        // Supprimer anciennes lignes
        $facture->details()->delete();

        // Mettre à jour facture
        $facture->update([
            'client_id' => $request->client_id,
            'date' => $request->date,  // ✅ 'date'
            'date_echeance' => $request->date_echeance,
            'mode_reglement' => $request->mode_reglement ?? 'Manuelle',
            'numero' => $request->numero,
            'total_ht' => 0,
            'total_tva' => 0,
            'total_ttc' => 0,
        ]);

        $totalHT = 0;
        $totalTVA = 0;

        // Recréer lignes
        foreach ($request->articles as $article) {
            if (empty($article['prix']) || (int)$article['quantite'] <= 0) continue;

            $prix = (float) $article['prix'];
            $quantite = (int) $article['quantite'];
            $tva = isset($article['tva']) ? (float) $article['tva'] : 20;

            $sousTotalHT = $prix * $quantite;
            $montantTVA = $sousTotalHT * ($tva / 100);

            FactureDetail::create([
                'facture_id' => $facture->id,
                'article_id' => $article['id'] ?? null,
                'designation' => empty($article['id']) ? 'Article libre' : null,
                'prix_unitaire' => $prix,
                'tva' => $tva,
                'quantite' => $quantite,
                'total_ht' => $sousTotalHT,
                'total_tva' => $montantTVA,
                'total_ttc' => $sousTotalHT + $montantTVA,
            ]);

            $totalHT += $sousTotalHT;
            $totalTVA += $montantTVA;
        }

        $facture->update([
            'total_ht' => round($totalHT, 2),
            'total_tva' => round($totalTVA, 2),
            'total_ttc' => round($totalHT + $totalTVA, 2),
        ]);

        return redirect()->route('factures.show', $facture->id)
            ->with('success', '✅ Facture modifiée ! Total: ' . number_format($totalHT + $totalTVA, 2) . ' DH');

    } catch (\Exception $e) {
        return back()->withInput()->with('error', '❌ Erreur: ' . $e->getMessage());
    }
}

    public function download(Facture $facture)
    {
        $facture->load('client', 'details.article');
        $pdf = Pdf::loadView('factures.pdf', compact('facture'));
        return $pdf->download($facture->numero . '.pdf');
    }
 

public function duplicate($id)
{
    // نجيب الفاتورة مع التفاصيل
    $facture = Facture::with('details')->findOrFail($id);

    // إنشاء فاتورة جديدة (نسخ البيانات الأساسية فقط)
    $newFacture = $facture->replicate();

    // تغيير أشياء ضرورية
    $newFacture->numero = 'FAC-' . time();
    $newFacture->date = now();
    $newFacture->date_echeance = null;

    // reset totals (إلى كنتي غادي تعاود تحسبهم)
    $newFacture->total_ht = $facture->total_ht;
    $newFacture->total_tva = $facture->total_tva;
    $newFacture->total_ttc = $facture->total_ttc;

    $newFacture->save();

    // نسخ التفاصيل
    foreach ($facture->details as $detail) {
        $newDetail = $detail->replicate();
        $newDetail->facture_id = $newFacture->id;
        $newDetail->save();
    }

    return redirect()
        ->route('factures.index')
        ->with('success', 'Facture dupliquée avec succès');
}

    public function destroy(Facture $facture)
    {
        foreach($facture->details as $detail){
            if($detail->article) $detail->article->increment('quantite_stock', $detail->quantite);
        }
        $facture->delete();
        return redirect()->route('factures.index')->with('success', 'Facture supprimée');
    }

}