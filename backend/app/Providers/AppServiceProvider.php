<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Fournisseur de services principal de l'application.
 *
 * Permet d'enregistrer les liaisons dans le conteneur et d'exécuter la
 * logique d'amorçage des différents services de l'application.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistrer les services de l'application.
     *
     * Aucune liaison n'est enregistrée pour le moment.
     */
    public function register(): void
    {
        //
    }

    /**
     * Amorcer les services de l'application.
     *
     * Aucun traitement d'amorçage n'est exécuté pour le moment.
     */
    public function boot(): void
    {
        //
    }
}
