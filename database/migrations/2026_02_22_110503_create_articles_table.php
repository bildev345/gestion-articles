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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->decimal('prix', 10, 2);
            $table->decimal('tva', 5, 2); // 20.00% أو 10.00%
            $table->string('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('fournisseur_id'); // الربط مع المورد
            $table->integer('quantite_stock')->default(0); // المخزون الحالي
            $table->integer('seuil_minimum')->default(0);  // حد التحذير
            $table->timestamps();

            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
