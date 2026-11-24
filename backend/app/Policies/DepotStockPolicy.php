<?php

namespace App\Policies;

use App\Models\DepotStock;
use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les dépôts de stock.
 *
 * Toutes les actions sont réservées aux administrateurs et aux gestionnaires.
 */
class DepotStockPolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des dépôts.
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
     * Détermine si l'utilisateur peut consulter un dépôt.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  DepotStock  $depotStock
     * @return bool
     */
    public function view(User $user, DepotStock $depotStock): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut créer un dépôt.
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
     * Détermine si l'utilisateur peut modifier un dépôt.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  DepotStock  $depotStock
     * @return bool
     */
    public function update(User $user, DepotStock $depotStock): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut supprimer un dépôt.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  DepotStock  $depotStock
     * @return bool
     */
    public function delete(User $user, DepotStock $depotStock): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }
}
