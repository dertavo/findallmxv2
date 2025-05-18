# Usar una imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instalar extensiones necesarias de PHP y otras herramientas
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el contenido del proyecto en el contenedor
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

RUN php artisan storage:link

# Dar permisos necesarios a las carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar dependencias de Composer
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader

# Configurar el puerto de escucha
EXPOSE 9000
