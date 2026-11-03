<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('cotisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained('membres');
            $table->string('periode');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotisations');
    }
};
