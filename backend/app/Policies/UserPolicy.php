<?php

namespace App\Policies;

use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les comptes utilisateurs.
 *
 * Seul l'administrateur peut voir, créer, modifier et supprimer un compte
 * gestionnaire.
 */
class UserPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des comptes.
     *
     * Règle : administrateur uniquement.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut consulter un compte gestionnaire.
     *
     * Règle : administrateur uniquement.
     */
    public function view(User $user, User $utilisateur): bool
    {
        return $user->role === 'admin' && $utilisateur->role === 'gestionnaire';
    }

    /**
     * Détermine si l'utilisateur peut créer un compte gestionnaire.
     *
     * Règle : administrateur uniquement.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut modifier un compte gestionnaire.
     *
     * Règle : administrateur uniquement, et la cible doit être un gestionnaire.
     */
    public function update(User $user, User $utilisateur): bool
    {
        return $user->role === 'admin' && $utilisateur->role === 'gestionnaire';
    }

    /**
     * Détermine si l'utilisateur peut supprimer un compte gestionnaire.
     *
     * Règle : administrateur uniquement, et la cible doit être un gestionnaire.
     */
    public function delete(User $user, User $utilisateur): bool
    {
        return $user->role === 'admin' && $utilisateur->role === 'gestionnaire';
    }
}
