<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseur_facture_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fournisseur_facture_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('article_id')
                ->constrained()
                ->onDelete('cascade');

            $table->integer('quantite');

            $table->decimal('prix_unitaire',10,2);
            $table->decimal('tva',5,2);

            $table->decimal('total_ht',15,2);
            $table->decimal('total_tva',15,2);
            $table->decimal('total_ttc',15,2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseur_facture_details');
    }
};