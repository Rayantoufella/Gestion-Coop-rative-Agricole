#!/bin/sh
# Préparation automatique du backend au démarrage du conteneur.
# Le script peut être relancé sans risque : chaque étape vérifie d'abord ce qui existe déjà.
set -e

cd /app

# 1. Fichier .env (valeurs de démonstration)
if [ ! -f .env ]; then
    echo "[gca] Création du fichier .env à partir de .env.example"
    cp .env.example .env
fi

# Écrit (ou remplace) une ligne CLE=valeur dans .env.
# Nécessaire car "php artisan serve" ne transmet pas toutes les variables
# d'environnement du conteneur à l'application.
ecrire_dans_env() {
    if grep -q "^$1=" .env; then
        sed -i "s#^$1=.*#$1=$2#" .env
    else
        echo "$1=$2" >> .env
    fi
}

# Adresses venant de compose.yaml (elles suivent les ports choisis)
if [ -n "$APP_URL" ]; then
    ecrire_dans_env APP_URL "$APP_URL"
fi
if [ -n "$CORS_ALLOWED_ORIGINS" ]; then
    ecrire_dans_env CORS_ALLOWED_ORIGINS "$CORS_ALLOWED_ORIGINS"
fi

# 2. Clé de l'application
if ! grep -q '^APP_KEY=base64:' .env; then
    echo "[gca] Génération de la clé de l'application"
    php artisan key:generate --force
fi

# 3. Base de données
# "migrate:status" échoue tant que la table des migrations n'existe pas :
# c'est le premier lancement, on crée les tables ET les données de démo.
if php artisan migrate:status > /dev/null 2>&1; then
    echo "[gca] Base existante : application des nouvelles migrations"
    php artisan migrate --force
else
    echo "[gca] Premier lancement : migrations et données de démonstration"
    php artisan migrate --force --seed
fi

echo "[gca] API prête sur le port 8000 du conteneur"
exec "$@"
