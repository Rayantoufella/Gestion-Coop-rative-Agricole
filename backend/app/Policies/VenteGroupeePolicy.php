<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VenteGroupee;

class VenteGroupeePolicy
{
    /**
     * Vérifier si l'utilisateur peut voir la liste.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut voir une vente.
     */
    public function view(User $user, VenteGroupee $venteGroupee): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut créer une vente.
     */
    public function create(User $user): bool
    {
        return $user->role === 'gestionnaire';
    }

    /**
     * Vérifier si l'utilisateur peut modifier une vente.
     */
    public function update(User $user, VenteGroupee $venteGroupee): bool
    {
        return false;
    }

    /**
     * Vérifier si l'utilisateur peut supprimer une vente.
     */
    public function delete(User $user, VenteGroupee $venteGroupee): bool
    {
        return $user->role === 'gestionnaire';
    }
}
