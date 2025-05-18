# 1. Imagen base de PHP con FPM
FROM php:8.2-fpm

# 2. Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# 3. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Establecer el directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar el contenido del proyecto (después del WORKDIR)
COPY . .

# 6. Instalar dependencias de Composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader

# 7. Crear enlace de storage (después de composer install)
RUN php artisan storage:link

# 8. Permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exponer el puerto de PHP-FPM
EXPOSE 9000
