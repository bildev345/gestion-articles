<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fournisseur;

class FournisseursSeeder extends Seeder
{
    
    public function run()
    {
        $fournisseurs = [
            [
                'date' => '2026-03-01',
                'nom' => 'Batheterm',
                'telephone' => '0612345678',
                'email' => 'contact@ibnsouda.com',
                'adresse' => 'Rue Centrale, Fes',
                'ville' => 'Fes',
                'ice' => 'ICE123456',
            ],
            [
                'date' => '2026-03-02',
                'nom' => 'SolarTech ',
                'telephone' => '0623456789',
                'email' => 'info@solartech.ma',
                'adresse' => 'Zone Industrielle, Casablanca',
                'ville' => 'Casablanca',
                'ice' => 'ICE987654',
            ],
            [
                'date' => '2026-03-03',
                'nom' => 'CleanEnergie ',
                'telephone' => '0634567890',
                'email' => 'sales@greenenergy.com',
                'adresse' => 'Avenue de l’Énergie, Marrakech',
                'ville' => 'Marrakech',
                'ice' => 'ICE456789',
            ],
            [
                'date' => '2026-03-04',
                'nom' => 'EcoPower ',
                'telephone' => '0645678901',
                'email' => 'info@ecopower.ma',
                'adresse' => 'Rue de l’Énergie, Rabat',
                'ville' => 'Rabat',
                'ice' => 'ICE321654',
            ],
        ];

        foreach ($fournisseurs as $fournisseur) {
            Fournisseur::create($fournisseur);
        }
    }
}