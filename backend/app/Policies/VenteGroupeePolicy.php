<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VenteGroupee;

/**
 * Politique d'autorisation régissant les actions sur les ventes groupées.
 *
 * La consultation, la création et la suppression sont réservées aux
 * administrateurs et aux gestionnaires. La modification n'est jamais autorisée.
 */
class VenteGroupeePolicy
{
    /**
     * Détermine si l'utilisateur peut consulter la liste des ventes groupées.
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
     * Détermine si l'utilisateur peut consulter une vente groupée.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  VenteGroupee  $venteGroupee
     * @return bool
     */
    public function view(User $user, VenteGroupee $venteGroupee): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut créer une vente groupée.
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
     * Détermine si l'utilisateur peut modifier une vente groupée.
     *
     * Règle : la modification n'est jamais autorisée (retourne toujours false).
     *
     * @param  User  $user
     * @param  VenteGroupee  $venteGroupee
     * @return bool
     */
    public function update(User $user, VenteGroupee $venteGroupee): bool
    {
        return false;
    }

    /**
     * Détermine si l'utilisateur peut supprimer une vente groupée.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  VenteGroupee  $venteGroupee
     * @return bool
     */
    public function delete(User $user, VenteGroupee $venteGroupee): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }
}
