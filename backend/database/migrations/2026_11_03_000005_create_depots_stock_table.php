<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des dépôts de stock.
        Schema::create('depots_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres');
            $table->foreignId('produit_id')->constrained('produits');
            $table->date('date_depot');
            $table->decimal('quantite', 10, 2);
        });
    }
};
