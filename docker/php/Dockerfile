FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Исправляем права
RUN addgroup -g 1000 laravel && \
    adduser -u 1000 -G laravel -s /bin/sh -D laravel

WORKDIR /var/www

# Настраиваем временную директорию composer
RUN mkdir -p /tmp/composer && chown -R laravel:laravel /tmp/composer
ENV COMPOSER_HOME=/tmp/composer

USER laravel