<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fournisseur;
use App\Models\Client;
use App\Models\Article;
use App\Models\Facture;
use App\Models\FournisseurFacture;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {

        // ==============================
        // 1️⃣ FOURNISSEURS (5)
        // ==============================

        $f1 = Fournisseur::create([
            'date' => now(),
            'nom' => 'BATITHERM',
            'telephone' => '0611111111',
            'email' => 'contact@batitherm.ma',
            'adresse' => 'Zone industrielle',
            'ville' => 'Fès',
            'ice' => '001111111000001',
        ]);

        $f2 = Fournisseur::create([
            'date' => now(),
            'nom' => 'SOLARTECH',
            'telephone' => '0622222222',
            'email' => 'info@solartech.ma',
            'adresse' => 'Ain Sebaa',
            'ville' => 'Casablanca',
            'ice' => '002222222000002',
        ]);

        $f3 = Fournisseur::create([
            'date' => now(),
            'nom' => 'ENERGIE PLUS',
            'telephone' => '0633333333',
            'email' => 'contact@energieplus.ma',
            'adresse' => 'Centre ville',
            'ville' => 'Rabat',
            'ice' => '003333333000003',
        ]);

        $f4 = Fournisseur::create([
            'date' => now(),
            'nom' => 'SUN POWER',
            'telephone' => '0644444444',
            'email' => 'info@sunpower.ma',
            'adresse' => 'Quartier industriel',
            'ville' => 'Tanger',
            'ice' => '004444444000004',
        ]);

        $f5 = Fournisseur::create([
            'date' => now(),
            'nom' => 'GREEN SOLAR',
            'telephone' => '0655555555',
            'email' => 'contact@greensolar.ma',
            'adresse' => 'Route Meknes',
            'ville' => 'Fès',
            'ice' => '005555555000005',
        ]);

        // ==============================
        // 2️⃣ CLIENTS (5)
        // ==============================

        $c1 = Client::create([
            'nom' => 'Ahmed Benali',
            'telephone' => '0670000001',
            'email' => 'ahmed@gmail.com',
            'adresse' => 'Fès',
        ]);

        $c2 = Client::create([
            'nom' => 'Société Al Amal',
            'telephone' => '0670000002',
            'email' => 'contact@alamal.ma',
            'adresse' => 'Rabat',
        ]);

        $c3 = Client::create([
            'nom' => 'Youssef Idrissi',
            'telephone' => '0670000003',
            'email' => 'youssef@gmail.com',
            'adresse' => 'Meknes',
        ]);

        $c4 = Client::create([
            'nom' => 'Entreprise Atlas',
            'telephone' => '0670000004',
            'email' => 'atlas@company.ma',
            'adresse' => 'Casablanca',
        ]);

        $c5 = Client::create([
            'nom' => 'Fatima Zahra',
            'telephone' => '0670000005',
            'email' => 'fatima@gmail.com',
            'adresse' => 'Tanger',
        ]);

        // ==============================
        // 3️⃣ ARTICLES (5)
        // ==============================

        Article::create([
            'designation' => 'Panneau Solaire 540W',
            'prix' => 727.27,
            'tva' => 10,
            'description' => 'Panneau photovoltaïque haute performance',
            'reference' => 'PS-540W',
            'fournisseur_id' => $f1->id,
            'quantite_stock' => 100,
            'seuil_minimum' => 5,
        ]);

        Article::create([
            'designation' => 'Chauffe-eau Solaire 300L',
            'prix' => 15000,
            'tva' => 20,
            'description' => 'Chauffe eau solaire circuit fermé',
            'reference' => 'CES-300L',
            'fournisseur_id' => $f2->id,
            'quantite_stock' => 20,
            'seuil_minimum' => 2,
        ]);

        Article::create([
            'designation' => 'Onduleur 5KW',
            'prix' => 8000,
            'tva' => 20,
            'description' => 'Onduleur hybride solaire',
            'reference' => 'OND-5KW',
            'fournisseur_id' => $f3->id,
            'quantite_stock' => 15,
            'seuil_minimum' => 3,
        ]);

        Article::create([
            'designation' => 'Batterie Lithium 10KW',
            'prix' => 25000,
            'tva' => 20,
            'description' => 'Batterie haute capacité',
            'reference' => 'BAT-10KW',
            'fournisseur_id' => $f4->id,
            'quantite_stock' => 10,
            'seuil_minimum' => 2,
        ]);

        Article::create([
            'designation' => 'Kit Fixation Panneaux',
            'prix' => 1200,
            'tva' => 10,
            'description' => 'Support aluminium',
            'reference' => 'KIT-FIX',
            'fournisseur_id' => $f5->id,
            'quantite_stock' => 50,
            'seuil_minimum' => 5,
        ]);

        // ==============================
        // 4️⃣ FOURNISSEUR FACTURES (5)
        // ==============================

        FournisseurFacture::create([
            'fournisseur_id' => $f1->id,
            'date_facture' => now(),
            'numero' => 'FF-001',
            'total_ht' => 90000,
            'total_tva' => 9000,
            'total_ttc' => 99000,
        ]);

        FournisseurFacture::create([
            'fournisseur_id' => $f2->id,
            'date_facture' => now(),
            'numero' => 'FF-002',
            'total_ht' => 30000,
            'total_tva' => 6000,
            'total_ttc' => 36000,
        ]);

        FournisseurFacture::create([
            'fournisseur_id' => $f3->id,
            'date_facture' => now(),
            'numero' => 'FF-003',
            'total_ht' => 20000,
            'total_tva' => 4000,
            'total_ttc' => 24000,
        ]);

        FournisseurFacture::create([
            'fournisseur_id' => $f4->id,
            'date_facture' => now(),
            'numero' => 'FF-004',
            'total_ht' => 50000,
            'total_tva' => 10000,
            'total_ttc' => 60000,
        ]);

        FournisseurFacture::create([
            'fournisseur_id' => $f5->id,
            'date_facture' => now(),
            'numero' => 'FF-005',
            'total_ht' => 15000,
            'total_tva' => 1500,
            'total_ttc' => 16500,
        ]);

        // ==============================
        // 5️⃣ FACTURES CLIENT (5)
        // ==============================

        Facture::create([
            'client_id' => $c1->id,
            'numero' => 'F-001',
            'date' => now(),
            'date_echeance' => now()->addDays(30),
            'total_ht' => 15000,
            'total_tva' => 3000,
            'total_ttc' => 18000,
        ]);

        Facture::create([
            'client_id' => $c2->id,
            'numero' => 'F-002',
            'date' => now(),
            'date_echeance' => now()->addDays(30),
            'total_ht' => 8000,
            'total_tva' => 1600,
            'total_ttc' => 9600,
        ]);

        Facture::create([
            'client_id' => $c3->id,
            'numero' => 'F-003',
            'date' => now(),
            'date_echeance' => now()->addDays(30),
            'total_ht' => 25000,
            'total_tva' => 5000,
            'total_ttc' => 30000,
        ]);

        Facture::create([
            'client_id' => $c4->id,
            'numero' => 'F-004',
            'date' => now(),
            'date_echeance' => now()->addDays(30),
            'total_ht' => 12000,
            'total_tva' => 2400,
            'total_ttc' => 14400,
        ]);

        Facture::create([
            'client_id' => $c5->id,
            'numero' => 'F-005',
            'date' => now(),
            'date_echeance' => now()->addDays(30),
            'total_ht' => 10000,
            'total_tva' => 2000,
            'total_ttc' => 12000,
        ]);
    }
}