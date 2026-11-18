<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant un produit commercialisé par l'association.
 *
 * Champs principaux :
 * - id : identifiant unique du produit.
 * - nom : nom du produit.
 * - categorie : catégorie du produit.
 * - unite : unité de mesure du produit.
 *
 * @property int $id
 * @property string $nom
 * @property string $categorie
 * @property string $unite
 */
class Produit extends Model
{
    use HasFactory;

    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'categorie',
        'unite',
    ];

    /**
     * Dépôts de stock concernant le produit.
     *
     * @return HasMany
     */
    // Un produit peut avoir plusieurs dépôts de stock.
    public function depotsStock(): HasMany
    {
        return $this->hasMany(DepotStock::class);
    }

    /**
     * Ventes groupées concernant le produit.
     *
     * @return HasMany
     */
    // Un produit peut avoir plusieurs ventes groupées.
    public function ventesGroupees(): HasMany
    {
        return $this->hasMany(VenteGroupee::class);
    }
}
