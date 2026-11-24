<?php

namespace App\Policies;

use App\Models\Membre;
use App\Models\User;

/**
 * Politique d'autorisation régissant les actions sur les membres.
 *
 * La consultation est ouverte aux administrateurs et aux gestionnaires,
 * tandis que la création, la modification et la suppression sont réservées
 * aux administrateurs.
 */
class MembrePolicy
{
    /**
     * Vérifie si l'utilisateur est administrateur.
     *
     * @param  User  $user
     * @return bool
     */
    private function estAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Détermine si l'utilisateur peut consulter la liste des membres.
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
     * Détermine si l'utilisateur peut consulter un membre.
     *
     * Règle : administrateur ou gestionnaire.
     *
     * @param  User  $user
     * @param  Membre  $membre
     * @return bool
     */
    public function view(User $user, Membre $membre): bool
    {
        return in_array($user->role, ['admin', 'gestionnaire']);
    }

    /**
     * Détermine si l'utilisateur peut créer un membre.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Détermine si l'utilisateur peut modifier un membre.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Membre  $membre
     * @return bool
     */
    public function update(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Détermine si l'utilisateur peut supprimer un membre.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Membre  $membre
     * @return bool
     */
    public function delete(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Détermine si l'utilisateur peut restaurer un membre.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Membre  $membre
     * @return bool
     */
    public function restore(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }

    /**
     * Détermine si l'utilisateur peut supprimer définitivement un membre.
     *
     * Règle : administrateur uniquement.
     *
     * @param  User  $user
     * @param  Membre  $membre
     * @return bool
     */
    public function forceDelete(User $user, Membre $membre): bool
    {
        return $this->estAdmin($user);
    }
}
