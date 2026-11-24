<?php

namespace App\Policies;

use App\Models\Acheteur;
use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les acheteurs.
 *
 * Toutes les actions sont réservées aux administrateurs et aux gestionnaires.
 */
class AcheteurPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des acheteurs.
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
     * Détermine si l'utilisateur peut consulter un acheteur.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Acheteur  $acheteur
     * @return bool
     */
    public function view(User $user, Acheteur $acheteur): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut créer un acheteur.
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
     * Détermine si l'utilisateur peut modifier un acheteur.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Acheteur  $acheteur
     * @return bool
     */
    public function update(User $user, Acheteur $acheteur): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut supprimer un acheteur.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Acheteur  $acheteur
     * @return bool
     */
    public function delete(User $user, Acheteur $acheteur): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }
}
