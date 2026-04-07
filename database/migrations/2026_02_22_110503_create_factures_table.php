<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('numero')->unique(); // numero editable  // نولدو الرقم بعد إنشاء الفاتورة باستخدام id
            $table->date('date');
            $table->date('date_echeance')->nullable();
            $table->decimal('total_ht', 15, 2)->default(0);
            $table->decimal('total_tva', 15, 2)->default(0);
            $table->decimal('total_ttc', 15, 2)->default(0);
            $table->string('mode_reglement')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};