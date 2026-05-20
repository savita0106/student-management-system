FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    && docker-php-ext-install mysqli \
    && pecl install redis \
    && pecl install mongodb \
    && docker-php-ext-enable redis mongodb

COPY . /var/www/html/

EXPOSE 80