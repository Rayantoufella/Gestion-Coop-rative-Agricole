<?php

namespace App\Policies;

use App\Models\Produit;
use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les produits.
 *
 * Toutes les actions sont réservées aux administrateurs et aux gestionnaires.
 */
class ProduitPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des produits.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut consulter un produit.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Produit  $produit
     * @return bool
     */
    public function view(User $user, Produit $produit): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut créer un produit.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut modifier un produit.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Produit  $produit
     * @return bool
     */
    public function update(User $user, Produit $produit): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut supprimer un produit.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Produit  $produit
     * @return bool
     */
    public function delete(User $user, Produit $produit): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }
}
