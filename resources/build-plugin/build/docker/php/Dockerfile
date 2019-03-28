FROM php:7.3-apache

# Enable mod rewrite
RUN a2enmod rewrite

# Set document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Copy default confg
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Install Composer
RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip libzip-dev
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) zip pdo pdo_mysql

WORKDIR /var/www/html
