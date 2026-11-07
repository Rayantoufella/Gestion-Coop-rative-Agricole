<?php

namespace App\Policies;

use App\Models\DepotStock;
use App\Models\User;

class DepotStockPolicy
{
    /**
     * Vérifier si l'utilisateur peut voir la liste.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut voir un dépôt.
     */
    public function view(User $user, DepotStock $depotStock): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut créer un dépôt.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut modifier un dépôt.
     */
    public function update(User $user, DepotStock $depotStock): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Vérifier si l'utilisateur peut supprimer un dépôt.
     */
    public function delete(User $user, DepotStock $depotStock): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }
}
