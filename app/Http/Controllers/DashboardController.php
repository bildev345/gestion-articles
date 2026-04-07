<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        // Comptes
        $recentActivities = Activity::latest()->take(10)->get();
        $articles_count = DB::table('articles')->count();
        $clients_count = DB::table('clients')->count();
        $fournisseurs_count = DB::table('fournisseurs')->count();
        $ventes_count = DB::table('factures')->count(); // ventes
        $achats_count = DB::table('fournisseur_factures')->count(); // achats

        // Stock alerts
        $faible_stock = DB::table('articles')->whereColumn('quantite_stock', '<=', 'seuil_alerte')->count();
        $rupture_stock = DB::table('articles')->where('quantite_stock', 0)->count();

        return view('dashboard', compact(
            'articles_count',
            'clients_count',
            'fournisseurs_count',
            'ventes_count',
            'achats_count',
            'faible_stock',
            'rupture_stock'
        ));
    }
    

}