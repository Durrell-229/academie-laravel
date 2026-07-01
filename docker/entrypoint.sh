#!/bin/sh
set -e

# Ces commandes tournent maintenant au demarrage du conteneur,
# quand les variables d'environnement Render (APP_KEY, DB_*, etc.)
# sont deja injectees. C'est ce qui corrige l'erreur
# "No application encryption key has been specified."

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

service nginx start
php-fpm
