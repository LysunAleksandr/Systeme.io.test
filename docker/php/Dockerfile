FROM php:8.2-fpm

WORKDIR /var/www/symfony_docker

# Multistage builds
COPY --from=node:14.17.0-slim /usr/local/bin /usr/local/bin
COPY --from=node:14.17.0-slim /usr/local/lib/node_modules/npm /usr/local/lib/node_modules/npm
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN mkdir "/var/www/.npm" && chown -R 1000:1000 "/var/www/.npm"
RUN mkdir "/var/www/.symfony5" && chown -R 1000:1000 "/var/www/.symfony5"

## Install additinal dependencies
RUN apt update \
    && apt install -y wget zlib1g-dev g++  \
    libicu-dev zip libzip-dev zip libxml2-dev  \
    libldb-dev libldap2-dev libpng-dev  \
    libpq-dev git zip cron sudo ca-certificates \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo pdo_pgsql pgsql \
    && pecl update-channels \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install xml \
    && docker-php-ext-install gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install redis  \
    && docker-php-ext-enable redis

#Fixing user and permissions
RUN adduser www-data sudo
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
ARG ARG_UID=1000
ARG ARG_GID=1000
ENV APP_USER="www-data"
ENV APP_GROUP="www-data"
RUN usermod -u ${ARG_UID} -s /bin/bash ${APP_USER} && groupmod -g ${ARG_GID} ${APP_GROUP}

## Update root certificates
RUN mkdir /usr/local/share/ca-certificates/extra
COPY ./navibackend.dd.mmk.ru-chain.crt /usr/local/share/ca-certificates/extra/
RUN update-ca-certificates

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# CacheTool - Manage cache in the CLI
RUN curl -sLO https://github.com/gordalina/cachetool/releases/latest/download/cachetool.phar