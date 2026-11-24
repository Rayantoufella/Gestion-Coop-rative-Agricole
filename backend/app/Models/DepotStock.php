<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant un dépôt de stock effectué par un membre pour un produit.
 *
 * Champs principaux :
 * - id : identifiant unique du dépôt.
 * - membre_id : identifiant du membre ayant effectué le dépôt.
 * - produit_id : identifiant du produit déposé.
 * - date_depot : date du dépôt.
 * - quantite : quantité déposée.
 *
 * @property int $id
 * @property int $membre_id
 * @property int $produit_id
 * @property \Illuminate\Support\Carbon $date_depot
 * @property string $quantite
 */
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

    /**
     * Membre ayant effectué le dépôt.
     *
     * @return BelongsTo
     */
    // Un dépôt appartient à un membre.
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * Produit concerné par le dépôt.
     *
     * @return BelongsTo
     */
    // Un dépôt concerne un produit.
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }
}
