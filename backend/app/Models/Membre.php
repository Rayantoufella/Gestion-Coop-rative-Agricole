<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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


    public function cotisations(): HasMany
    {
        return $this->hasMany(Cotisation::class);
    }


    public function depotsStock(): HasMany
    {
        return $this->hasMany(DepotStock::class);
    }
}
