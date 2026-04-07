<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticlesSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'designation' => 'Panneau Solaire 300W',
                'prix' => 1200.00,
                'tva' => 20.00,
                'description' => 'Panneau photovoltaïque pour installations résidentielles',
                'reference' => 'SOL300',
                'image' => null,
                'fournisseur_id' => 2,
                'quantite_stock' => 50,
                'seuil_minimum' => 5,
            ],
            [
                'designation' => 'Panneau Solaire 500W',
                'prix' => 1800.00,
                'tva' => 20.00,
                'description' => 'Panneau solaire haute efficacité 500W',
                'reference' => 'SOL500',
                'image' => null,
                'fournisseur_id' => 2,
                'quantite_stock' => 30,
                'seuil_minimum' => 5,
            ],
            [
                'designation' => 'Régulateur de Charge 20A',
                'prix' => 250.00,
                'tva' => 20.00,
                'description' => 'Régulateur de charge pour batterie solaire 12/24V',
                'reference' => 'REG20A',
                'image' => null,
                'fournisseur_id' => 3,
                'quantite_stock' => 100,
                'seuil_minimum' => 10,
            ],
            [
                'designation' => 'Régulateur de Charge 40A',
                'prix' => 400.00,
                'tva' => 20.00,
                'description' => 'Régulateur de charge 40A pour panneaux solaires',
                'reference' => 'REG40A',
                'image' => null,
                'fournisseur_id' => 2,
                'quantite_stock' => 60,
                'seuil_minimum' => 5,
            ],
            [
                'designation' => 'Batterie AGM 100Ah',
                'prix' => 800.00,
                'tva' => 20.00,
                'description' => 'Batterie AGM 12V pour stockage solaire',
                'reference' => 'BAT100',
                'image' => null,
                'fournisseur_id' => 3,
                'quantite_stock' => 40,
                'seuil_minimum' => 5,
            ],
            [
                'designation' => 'Batterie AGM 200Ah',
                'prix' => 1500.00,
                'tva' => 20.00,
                'description' => 'Batterie AGM 12V 200Ah pour stockage solaire',
                'reference' => 'BAT200',
                'image' => null,
                'fournisseur_id' => 4,
                'quantite_stock' => 30,
                'seuil_minimum' => 5,
            ],
            [
                'designation' => 'Onduleur 1kW',
                'prix' => 2000.00,
                'tva' => 20.00,
                'description' => 'Onduleur solaire monophasé 1kW',
                'reference' => 'OND1K',
                'image' => null,
                'fournisseur_id' => 4,
                'quantite_stock' => 20,
                'seuil_minimum' => 2,
            ],
            [
                'designation' => 'Onduleur 3kW',
                'prix' => 5000.00,
                'tva' => 20.00,
                'description' => 'Onduleur solaire triphasé 3kW',
                'reference' => 'OND3K',
                'image' => null,
                'fournisseur_id' => 4,
                'quantite_stock' => 10,
                'seuil_minimum' => 1,
            ],
            [
                'designation' => 'Câble solaire 6mm²',
                'prix' => 10.00,
                'tva' => 20.00,
                'description' => 'Câble solaire pour panneaux 6mm²',
                'reference' => 'CABLE6',
                'image' => null,
                'fournisseur_id' => 3,
                'quantite_stock' => 500,
                'seuil_minimum' => 50,
            ],
            [
                'designation' => 'Câble solaire 10mm²',
                'prix' => 15.00,
                'tva' => 20.00,
                'description' => 'Câble solaire pour panneaux 10mm²',
                'reference' => 'CABLE10',
                'image' => null,
                'fournisseur_id' => 2,
                'quantite_stock' => 300,
                'seuil_minimum' => 30,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}