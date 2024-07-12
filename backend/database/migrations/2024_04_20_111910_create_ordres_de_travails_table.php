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
        Schema::create('ordres_de_travail', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('description');
            $table->boolean('urgent')->nullable();;
            $table->enum('statut', ['En panne', 'En attente', 'En cours', 'Réparé']); // Define work order statuses
            $table->string('utilisateur_id');
            $table->string('equipement_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordres_de_travail');
    }
};
