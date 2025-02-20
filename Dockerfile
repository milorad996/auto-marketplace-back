# Odaberi PHP verziju sa FPM (FastCGI Process Manager) za Laravel
FROM php:8.1-fpm

# Instaliraj potrebne pakete i ekstenzije
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    nginx \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    php-mbstring \
    php-xml \
    php-curl \
    php-mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql soap opcache intl mbstring xml curl

# Instalacija Composer-a
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setuj radnu mapu
WORKDIR /var/www

# Kopiraj sve fajlove u radnu mapu u Docker kontejneru
COPY . .

# Prekopiraj .env fajl ako ne postoji
RUN cp .env.example .env

# Instaliraj sve Composer zavisnosti sa verbose logovanjem
RUN composer install --no-dev --optimize-autoloader --no-interaction --verbose

# Dodeli odgovarajuće dozvole za direktorijume
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www

# Konfiguriši nginx da koristi Laravel
COPY ./nginx/laravel.conf /etc/nginx/sites-available/default

# Ekspoziraj port 80
EXPOSE 80

# Startuj nginx i php-fpm servis
CMD service nginx start && php-fpm
