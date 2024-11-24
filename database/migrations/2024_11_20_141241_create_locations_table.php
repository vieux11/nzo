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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->date('date')->useCurrent();
            $table->text('description')->nullable();
            $table->decimal('loyer_montant', 10, 2);
            $table->enum('devise', ['FC', 'USD'])->default('USD');
            $table->foreignId('id_propriete')->constrained('proprietes')->onDelete('cascade');
            $table->foreignId('id_locataire')->references('id')->on('locataires')->onDelete('cascade');
            $table->foreignId('id_proprietaire')->constrained('proprietaires')->onDelete('cascade');
            $table->boolean('confirm')->default(false); // Champ booléen par défaut à false
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
