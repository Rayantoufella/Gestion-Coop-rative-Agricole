<?php

namespace App\Policies;

use App\Models\Cotisation;
use App\Models\User;

class CotisationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cotisation $cotisation): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cotisation $cotisation): bool
    {
        return $user->role === 'admin';
    }
}
