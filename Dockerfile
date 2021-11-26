FROM php:7-alpine

ENV docker=true
ENV JWT_KEY=gK0gNq1hjymQLAeDTYbnbjVYsOaj4xTIYn8u9o9Wx

WORKDIR /var/www

RUN set -ex \
    && apk --no-cache add \
    postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql