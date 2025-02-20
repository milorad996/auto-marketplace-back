# Koristi PHP sa FPM
FROM php:8.1-fpm

# Instalacija sistemskih paketa
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    curl \
    unzip \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    oniguruma \
    && apt-get clean

# Instalacija PHP ekstenzija
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql soap opcache intl mbstring xml curl

# Instalacija Composer-a
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    mv composer.phar /usr/local/bin/composer && \
    rm composer-setup.php

# Postavljanje radnog direktorijuma
WORKDIR /var/www

# Kopiranje Laravel projekta
COPY . .

# Postavljanje permisija
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www

# Instalacija Composer zavisnosti
RUN composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction

# Expose porta
EXPOSE 9000

# Pokretanje PHP-FPM
CMD ["php-fpm"]
