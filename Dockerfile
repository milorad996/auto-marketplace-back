# Koristi PHP 8.1 sa FPM
FROM php:8.1-fpm

# Instalacija potrebnih paketa
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    curl \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libicu-dev

# Instalacija Composer-a
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Postavljanje radnog direktorijuma
WORKDIR /var/www

# Kopiraj sve fajlove u konteiner
COPY . .

# Postavljanje prava za direktorijume
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www

# Instalacija zavisnosti
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalacija Node.js i npm (ako koristiš React)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - 
RUN apt-get install -y nodejs

# Instalacija frontend zavisnosti (ako koristiš npm)
RUN npm install

# Kopiraj Nginx konfiguraciju
COPY ./nginx/laravel.conf /etc/nginx/sites-available/default

# Otvori port 80
EXPOSE 80

# Pokreni Nginx i PHP-FPM
CMD service nginx start && php-fpm
