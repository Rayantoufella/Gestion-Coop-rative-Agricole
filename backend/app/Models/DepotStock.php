<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepotStock extends Model
{
    use HasFactory;

    // Nom exact de la table.
    protected $table = 'depots_stock';

    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'membre_id',
        'produit_id',
        'date_depot',
        'quantite',
    ];

    protected $casts = [
        'date_depot' => 'date',
        'quantite' => 'decimal:2',
    ];

    // Un dépôt appartient à un membre.
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    // Un dépôt concerne un produit.
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }
}
