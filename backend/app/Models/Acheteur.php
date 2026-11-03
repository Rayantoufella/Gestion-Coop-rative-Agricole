<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Acheteur extends Model
{
    // Pas de colonnes created_at et updated_at.
    public $timestamps = false;

    protected $fillable = [
        'nom',
        'telephone',
    ];

    // Un acheteur peut avoir plusieurs ventes groupées.
    public function ventesGroupees(): HasMany
    {
        return $this->hasMany(VenteGroupee::class);
    }
}
