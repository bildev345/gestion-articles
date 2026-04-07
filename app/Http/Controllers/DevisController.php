<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devis;
use App\Models\Client;
use App\Models\Article;
use App\Models\DevisDetail;  // ✅ Votre modèle
use Illuminate\Support\Facades\DB;

class DevisController extends Controller
{
    public function index(Request $request)
    {
        $query = Devis::with('client');

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%")  // ✅ 'date'
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        $devis = $query->orderBy('id', 'desc')->paginate(15);
        return view('devis.index', compact('devis'));
    }

    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        $articles = Article::orderBy('designation')->get();
        return view('devis.create', compact('clients', 'articles'));
    }
        
    public function store(Request $request)
        
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'articles.*.prix' => 'required|numeric|min:0.01',
            'articles.*.quantite' => 'required|integer|min:1',
        ]);

        try {

            // ✅ GENERER NUMERO DEVIS PAR ANNEE
            $year = date('Y');

            $lastDevis = Devis::where('numero', 'like', 'F'.$year.'-%')
                ->orderBy('numero', 'desc')
                ->first();

            if ($lastDevis) {
                $lastNumber = (int) substr($lastDevis->numero, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $numero = 'F'.$year.'-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $devis = Devis::create([
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

            foreach ($request->articles as $article) {

                if (empty($article['prix']) || (int)$article['quantite'] <= 0) {
                    continue;
                }

                $prix = (float) $article['prix'];
                $quantite = (int) $article['quantite'];
                $tva = isset($article['tva']) ? (float) $article['tva'] : 20;

                $sousTotalHT = $prix * $quantite;
                $montantTVA = $sousTotalHT * ($tva / 100);

                DevisDetail::create([
                    'devis_id' => $devis->id,
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

            $devis->update([
                'total_ht' => round($totalHT, 2),
                'total_tva' => round($totalTVA, 2),
                'total_ttc' => round($totalHT + $totalTVA, 2),
            ]);

            return redirect()->route('devis.index')
                ->with('success', '✅ Devis #' . $devis->numero . ' créé !');

        } catch (\Exception $e) {

            return back()->withInput()
                ->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }

    
    public function edit(Devis $devis)
    {
        $clients = Client::orderBy('nom')->get();
        $articles = Article::orderBy('designation')->get();
        $devis->load('details');
        return view('devis.edit', compact('devis', 'clients', 'articles'));
    }

// ===== UPDATE - Modifier un devis existant =====
    // ===== UPDATE - Modifier un devis existant =====
 
    public function update(Request $request, Devis $devis)

{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'date' => 'required|date',  // ✅ 'date'
        'date_echeance' => 'nullable|date',
        'numero' => 'required|unique:devis,numero,' . $devis->id,
        'articles' => 'required|array|min:1',
        'articles.*.prix' => 'required|numeric|min:0.01',
        'articles.*.quantite' => 'required|integer|min:1',
    ]);

    try {
        // Supprimer anciennes lignes
        $devis->details()->delete();

        // Mettre à jour facture
        $devis->update([
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

            DevisDetail::create([
                'devis_id' => $devis->id,
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

        $devis->update([
            'total_ht' => round($totalHT, 2),
            'total_tva' => round($totalTVA, 2),
            'total_ttc' => round($totalHT + $totalTVA, 2),
        ]);

        return redirect()->route('devis.show', $devis->id)
            ->with('success', '✅ Devis modifiée ! Total: ' . number_format($totalHT + $totalTVA, 2) . ' DH');

    } catch (\Exception $e) {
        return back()->withInput()->with('error', '❌ Erreur: ' . $e->getMessage());
    }
}

    public function download(Devis $devis)
    {
        $devis->load('client', 'details.article');
        $pdf = Pdf::loadView('devis.pdf', compact('devis'));
        return $pdf->download($devis->numero . '.pdf');
    }
    public function show(Devis $devis)
    {
        $devis->load('client', 'details.article');
        return view('devis.show', compact('devis'));
}

// public function duplicate(Devis $devis)
//     {
//         $devis->load('client', 'details.article');

//         // Prepare data for pre-filling create form
//         $duplicateData = [
//             'devis_id' => $devis->devis_id,
//             'date' => now()->format('Y-m-d'),
//             'articles' => $fournisseur_facture->details->map(function($detail) {
//                 return [
//                     'id' => $detail->article_id,
//                     'quantite' => $detail->quantite,
//                 ];
//             })->toArray()
//         ];

//         return redirect()->route('fournisseur_factures.create')
//             ->with('duplicateData', $duplicateData)
//             ->with('message', 'Facture fournisseur dupliquée - modifiez les détails et sauvegardez');
//     }
public function duplicate($id)
{
    // نجيب الفاتورة مع التفاصيل
    $devis = Devis::with('details')->findOrFail($id);

    // إنشاء فاتورة جديدة (نسخ البيانات الأساسية فقط)
    $newDevis = $devis->replicate();

    // تغيير أشياء ضرورية
    $newDevis->numero = 'DEV-' . time();
    $newDevis->date = now();
    $newDevis->date_echeance = null;

    // reset totals (إلى كنتي غادي تعاود تحسبهم)
    $newDevis->total_ht = $devis->total_ht;
    $newDevis->total_tva = $devis->total_tva;
    $newDevis->total_ttc = $devis->total_ttc;

    $newDevis->save();

    // نسخ التفاصيل
    foreach ($devis->details as $detail) {
        $newDetail = $detail->replicate();
        $newDetail->devis_id = $newDevis->id;
        $newDetail->save();
    }

    return redirect()
        ->route('devis.index')
        ->with('success', 'Devis dupliquée avec succès');
}

public function destroy(Devis $devis)
{
    try {
        $devis->delete();
        return redirect()->route('devis.index')->with('message', 'Devis supprimé !');
    } catch (\Exception $e) {
        return back()->with('error', 'Erreur suppression : ' . $e->getMessage());
    }

}
}