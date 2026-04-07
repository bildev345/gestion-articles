<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create a test user (or multiple if you want)
        // User::factory(10)->create();
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // call the application data seeder which populates
        // clients, articles, factures, fournisseurs and related details
        $this->call(
            [
            
                FournisseursSeeder::class,
                ClientsSeeder::class,
                ArticlesSeeder::class,
            ]

            );

    }
}
