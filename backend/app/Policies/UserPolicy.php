<?php

namespace App\Policies;

use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les comptes utilisateurs.
 *
 * Seul l'administrateur peut voir les comptes et créer un gestionnaire.
 */
class UserPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des comptes.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut créer un compte gestionnaire.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }
}
