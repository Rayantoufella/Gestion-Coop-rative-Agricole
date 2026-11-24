FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libonig-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 1. Dépendances PHP (cette étape reste en cache tant que composer.lock ne change pas)
COPY backend/composer.json backend/composer.lock ./
RUN composer install --no-interaction --no-progress --prefer-dist --no-scripts --no-autoloader

# 2. Code de l'application
COPY backend/ ./
RUN composer dump-autoload --optimize \
    && chmod -R ug+rwX storage bootstrap/cache

# 3. Script de démarrage : .env, clé, migrations et données de démo
COPY docker/php/entrypoint.sh /usr/local/bin/gca-entrypoint
RUN chmod +x /usr/local/bin/gca-entrypoint

EXPOSE 8000

ENTRYPOINT ["gca-entrypoint"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000", "--no-reload"]
