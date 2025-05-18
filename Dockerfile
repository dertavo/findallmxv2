FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar solo los archivos necesarios para instalar dependencias primero (cache eficiente)
COPY composer.json composer.lock ./

# Instalar dependencias antes de copiar el resto
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader

# Luego copia todo lo demás
COPY . .

# Asignar permisos necesarios
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ahora que vendor/ ya existe, podemos ejecutar Artisan sin errores
RUN php artisan storage:link

EXPOSE 9000
