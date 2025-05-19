FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Reinstala dependencias (Composer se ejecuta *dentro* del contenedor para evitar pérdida por volumen)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel setup
RUN php artisan storage:link || true
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 9000
