<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseur_factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained()->onDelete('cascade');
            $table->date('date_facture');
            $table->string('numero')->nullable();

            $table->decimal('total_ht',15,2)->default(0);
            $table->decimal('total_tva',15,2)->default(0);
            $table->decimal('total_ttc',15,2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseur_factures');
    }
};