<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant une vente groupée d'un produit à un acheteur.
 *
 * Champs principaux :
 * - id : identifiant unique de la vente groupée.
 * - produit_id : identifiant du produit vendu.
 * - acheteur_id : identifiant de l'acheteur.
 * - date_vente : date de la vente.
 * - quantite : quantité vendue.
 * - prix_unitaire : prix unitaire appliqué.
 *
 * @property int $id
 * @property int $produit_id
 * @property int $acheteur_id
 * @property \Illuminate\Support\Carbon $date_vente
 * @property string $quantite
 * @property string $prix_unitaire
 */
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


    /**
     * Produit vendu lors de la vente groupée.
     *
     * @return BelongsTo
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }


    /**
     * Acheteur ayant réalisé la vente groupée.
     *
     * @return BelongsTo
     */
    public function acheteur(): BelongsTo
    {
        return $this->belongsTo(Acheteur::class);
    }
}
