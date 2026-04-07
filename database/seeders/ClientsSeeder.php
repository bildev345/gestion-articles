<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsSeeder extends Seeder
{
    public function run()
{
    $clients = [
        [
            'nom' => 'Mohamed El Fassi',
            'telephone' => '0612345678',
            'email' => 'mohamed.fassi@example.com',
            'adresse' => 'Rue des Fleurs, Fes',
            'date' => '2024-01-10',
        ],
        [
            'nom' => 'Sara Benkirane',
            'telephone' => '0623456789',
            'email' => 'sara.benkirane@example.com',
            'adresse' => 'Avenue Mohammed V, Casablanca',
            'date' => '2024-02-15',
        ],
        [
            'nom' => 'Youssef El Amrani',
            'telephone' => '0634567890',
            'email' => 'youssef.elamrani@example.com',
            'adresse' => 'Quartier Gueliz, Marrakech',
            'date' => '2024-03-20',
        ],
        [
            'nom' => 'Fatima Zahra Idrissi',
            'telephone' => '0645678901',
            'email' => 'fatima.idrissi@example.com',
            'adresse' => 'Hay Mohammadi, Casablanca',
            'date' => '2024-04-05',
        ],
        [
            'nom' => 'Rachid Tazi',
            'telephone' => '0656789012',
            'email' => 'rachid.tazi@example.com',
            'adresse' => 'Rue des Oliviers, Fes',
            'date' => '2024-05-12',
        ],
    ];

    foreach ($clients as $client) {
        Client::create($client);
    }
}
}
    