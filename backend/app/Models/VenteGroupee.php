<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenteGroupee extends Model
{
    use HasFactory;

    // Nom exact de la table.
    protected $table = 'ventes_groupees';

    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'produit_id',
        'acheteur_id',
        'date_vente',
        'quantite',
        'prix_unitaire',
    ];

    protected $casts = [
        'date_vente' => 'date',
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
    ];


    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }


    public function acheteur(): BelongsTo
    {
        return $this->belongsTo(Acheteur::class);
    }
}
