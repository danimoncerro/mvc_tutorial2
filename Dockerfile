FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

# Activează mod_rewrite
RUN a2enmod rewrite

# Setează permisiuni
RUN chown -R www-data:www-data /var/www/html

# Copiază toate fișierele în container
COPY . /var/www/html


