FROM php:8.2-apache

# Activează mod_rewrite
RUN a2enmod rewrite

# Setează permisiuni
RUN chown -R www-data:www-data /var/www/html

# Copiază toate fișierele în container
COPY . /var/www/html
