<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant un membre de l'association.
 *
 * Champs principaux :
 * - id : identifiant unique du membre.
 * - code_membre : code unique d'identification du membre.
 * - nom : nom du membre.
 * - prenom : prénom du membre.
 * - telephone : numéro de téléphone du membre.
 * - date_adhesion : date d'adhésion du membre.
 * - statut : statut du membre.
 *
 * @property int $id
 * @property string $code_membre
 * @property string $nom
 * @property string $prenom
 * @property string $telephone
 * @property \Illuminate\Support\Carbon $date_adhesion
 * @property string $statut
 */
class Membre extends Model
{
    use HasFactory;

    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'code_membre',
        'nom',
        'prenom',
        'telephone',
        'date_adhesion',
        'statut',
    ];

    protected $casts = [
        'date_adhesion' => 'date',
    ];


    /**
     * Cotisations rattachées au membre.
     *
     * @return HasMany
     */
    public function cotisations(): HasMany
    {
        return $this->hasMany(Cotisation::class);
    }


    /**
     * Dépôts de stock effectués par le membre.
     *
     * @return HasMany
     */
    public function depotsStock(): HasMany
    {
        return $this->hasMany(DepotStock::class);
    }
}
