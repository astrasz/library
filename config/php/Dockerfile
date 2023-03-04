FROM php:8.2-fpm-alpine

RUN set -ex \
    && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

COPY ../../api/composer.* ./

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

COPY ../../api .

WORKDIR /var/www/html
