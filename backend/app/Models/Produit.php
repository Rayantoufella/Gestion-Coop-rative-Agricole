<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // Un produit peut avoir plusieurs dépôts de stock.
    public function depotsStock(): HasMany
    {
        return $this->hasMany(DepotStock::class);
    }

    // Un produit peut avoir plusieurs ventes groupées.
    public function ventesGroupees(): HasMany
    {
        return $this->hasMany(VenteGroupee::class);
    }
}
