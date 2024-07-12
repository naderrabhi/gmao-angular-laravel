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
        Schema::create('affectation_des_ordres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('technicien_id');
            $table->unsignedBigInteger('ordre_travail_id');
            $table->string('date_resolution')->nullable();
            $table->string('date_confirmation')->nullable();
            $table->boolean('confirmer')->nullable();
            $table->boolean('reparer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectation_des_ordres');
    }

};
