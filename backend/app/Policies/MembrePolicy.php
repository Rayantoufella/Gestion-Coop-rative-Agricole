<?php

namespace App\Policies;

use App\Models\Membre;
use App\Models\User;

class MembrePolicy
{
    /**
     * Determine whether the user is an admin.
     */
    private function estAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }
}
