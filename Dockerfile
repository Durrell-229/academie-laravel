FROM php:8.2-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.js pour le build des assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Config Nginx
COPY docker/nginx.conf /etc/nginx/sites-available/default

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Entrypoint qui gère le cache Laravel AU DEMARRAGE (variables d'env disponibles)
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000
ENTRYPOINT ["/entrypoint.sh"]
