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
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->date('date_payement');
            $table->decimal('montant', 10, 2);
            $table->enum('devise', ['FC', 'USD'])->default('USD');
            $table->enum('status', ['en_cours', 'effectué', 'rejeté'])->default('en_cours');
            $table->string('methode_paiement'); // e.g., "mobile_money", "virement", "espèces"
            $table->integer('mois'); // Mois associé au paiement
            $table->integer('annee'); // Année associée au paiement
            $table->foreignId('id_locataire')->references('id')->on('locataires');
            $table->foreignId('id_location')->references('id')->on('locations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payements');
    }
};
