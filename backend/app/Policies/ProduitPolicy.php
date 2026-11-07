<?php

namespace App\Policies;

use App\Models\Produit;
use App\Models\User;

class ProduitPolicy
{
    /**
     * Vérifier si l'utilisateur peut voir la liste.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut voir un produit.
     */
    public function view(User $user, Produit $produit): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut créer un produit.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut modifier un produit.
     */
    public function update(User $user, Produit $produit): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut supprimer un produit.
     */
    public function delete(User $user, Produit $produit): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }
}
