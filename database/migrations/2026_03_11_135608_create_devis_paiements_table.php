<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devis_paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('devis_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('montant', 15, 2);

            $table->date('date_paiement');

            $table->string('mode_paiement')->nullable(); 
            // ex: cash, virement, cheque

            $table->string('reference')->nullable(); 
            // ex: numéro chèque / référence virement

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_paiements');
    }
};
