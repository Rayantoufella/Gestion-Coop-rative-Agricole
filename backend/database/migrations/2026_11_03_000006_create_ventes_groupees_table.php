<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des ventes groupées.
        Schema::create('ventes_groupees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits');
            $table->foreignId('acheteur_id')->constrained('acheteurs');
            $table->date('date_vente');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
        });
    }
};
