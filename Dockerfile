FROM php:8.3-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    postgresql-dev \
    libpq

RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
