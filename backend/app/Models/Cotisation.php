<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant la cotisation d'un membre pour une période donnée.
 *
 * Champs principaux :
 * - id : identifiant unique de la cotisation.
 * - membre_id : identifiant du membre ayant payé la cotisation.
 * - periode : période couverte par la cotisation.
 * - montant : montant versé.
 * - date_paiement : date du paiement.
 *
 * @property int $id
 * @property int $membre_id
 * @property string $periode
 * @property string $montant
 * @property \Illuminate\Support\Carbon $date_paiement
 */
class Cotisation extends Model
{
    use HasFactory;

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

    /**
     * Membre auquel appartient la cotisation.
     *
     * @return BelongsTo
     */
    // Une cotisation appartient à un membre.
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }
}
