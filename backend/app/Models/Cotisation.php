<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotisation extends Model
{
    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'membre_id',
        'periode',
        'montant',
        'date_paiement',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    // Une cotisation appartient à un membre.
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }
}
