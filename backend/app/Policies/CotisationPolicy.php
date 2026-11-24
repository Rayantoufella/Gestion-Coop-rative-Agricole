<?php

namespace App\Policies;

use App\Models\Cotisation;
use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les cotisations.
 *
 * Toutes les actions sont réservées aux administrateurs.
 */
class CotisationPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des cotisations.
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
     * Détermine si l'utilisateur peut consulter une cotisation.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Cotisation  $cotisation
     * @return bool
     */
    public function view(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut créer une cotisation.
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

    /**
     * Détermine si l'utilisateur peut modifier une cotisation.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Cotisation  $cotisation
     * @return bool
     */
    public function update(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut supprimer une cotisation.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Cotisation  $cotisation
     * @return bool
     */
    public function delete(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut restaurer une cotisation.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Cotisation  $cotisation
     * @return bool
     */
    public function restore(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement une cotisation.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Cotisation  $cotisation
     * @return bool
     */
    public function forceDelete(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }
}
