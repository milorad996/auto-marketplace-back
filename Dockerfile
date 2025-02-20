# Koristimo zvanični PHP image
FROM php:8.1-fpm

# Instalacija sistemskih paketa
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libonig-dev \  # OVO JE DODATO - rešava problem sa oniguruma
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql soap opcache intl mbstring xml curl

# Instalacija Composer-a
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Kreiranje radnog direktorijuma
WORKDIR /var/www

# Kopiranje Laravel koda
COPY . .

# Osiguravanje da .env fajl postoji
RUN cp .env.example .env

# Instalacija PHP zavisnosti
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Podešavanje permisija
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www

# Kopiranje Nginx konfiguracije
COPY ./nginx/laravel.conf /etc/nginx/sites-available/default

# Otvaranje porta
EXPOSE 80

# Pokretanje servisa
CMD service nginx start && php-fpm
