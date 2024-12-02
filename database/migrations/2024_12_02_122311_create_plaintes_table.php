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
        Schema::create('plaintes', function (Blueprint $table) {
            $table->id();
            $table->string('sujet');
            $table->text('description');
            $table->timestamp('date_plainte')->useCurrent();
            $table->enum('status', ['en_cours', 'résolue', 'fermée'])->default('en_cours');
            $table->foreignId('id_locataire')->references('id')->on('locataires')->onDelete('cascade');
            $table->foreignId('id_proprietaire')->references('id')->on('proprietaires')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plaintes');
    }
};
