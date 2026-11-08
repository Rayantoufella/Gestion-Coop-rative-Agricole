<?php

namespace App\Policies;

use App\Models\Acheteur;
use App\Models\User;

class AcheteurPolicy
{
    /**
     * Vérifier si l'utilisateur peut voir la liste.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut voir un acheteur.
     */
    public function view(User $user, Acheteur $acheteur): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut créer un acheteur.
     */
    public function create(User $user): bool
    {
        return $user->role === 'gestionnaire';
    }

    /**
     * Vérifier si l'utilisateur peut modifier un acheteur.
     */
    public function update(User $user, Acheteur $acheteur): bool
    {
        return $user->role === 'gestionnaire';
    }

    /**
     * Vérifier si l'utilisateur peut supprimer un acheteur.
     */
    public function delete(User $user, Acheteur $acheteur): bool
    {
        return $user->role === 'gestionnaire';
    }
}
