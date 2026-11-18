<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant un acheteur.
 *
 * Un acheteur est une personne ou une entité qui participe aux ventes groupées.
 *
 * Champs principaux :
 * - id : identifiant unique de l'acheteur.
 * - nom : nom de l'acheteur.
 * - telephone : numéro de téléphone de l'acheteur.
 *
 * @property int $id
 * @property string $nom
 * @property string $telephone
 */
class Acheteur extends Model
{
    use HasFactory;

    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'telephone',
    ];

    /**
     * Ventes groupées associées à l'acheteur.
     *
     * @return HasMany
     */
    // Un acheteur peut avoir plusieurs ventes groupées.
    public function ventesGroupees(): HasMany
    {
        return $this->hasMany(VenteGroupee::class);
    }
}
