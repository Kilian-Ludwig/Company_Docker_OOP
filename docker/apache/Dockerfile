FROM php:8.3-apache

RUN    docker-php-ext-install  pdo pdo_mysql
COPY company.conf /etc/apache2/sites-available
RUN a2ensite company.conf
RUN a2enmod rewrite

