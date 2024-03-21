FROM php:8.2

RUN apt update \
    && apt install -y wget  zlib1g-dev g++ libicu-dev zip libzip-dev zip libxml2-dev libldb-dev libldap2-dev libpng-dev libpq-dev  zip  \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install xml \
    && docker-php-ext-install gd \
    && pecl install redis  \
    && docker-php-ext-enable redis

WORKDIR /var/www/symfony_docker

#----------------------------USER--------------------------------
ARG ARG_UID=1000
ARG ARG_GID=1000

ENV APP_USER="www-data"
ENV APP_GROUP="www-data"

RUN usermod -u ${ARG_UID} -s /bin/bash ${APP_USER} && groupmod -g ${ARG_GID} ${APP_GROUP}
