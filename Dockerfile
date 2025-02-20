FROM php:8.1-fpm

# Instalacija sistemskih paketa i PHP ekstenzija
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql opcache intl mbstring xml curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacija Composer-a
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Radni direktorijum aplikacije
WORKDIR /var/www

# Kopiranje Laravel fajlova
COPY . .

# Instalacija PHP paketa
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Podešavanje permisija
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Podešavanje Nginx servera
COPY nginx/laravel.conf /etc/nginx/sites-available/default

# Expose porta
EXPOSE 80

# Start Nginx i PHP-FPM
CMD service php8.1-fpm start && nginx -g "daemon off;"
