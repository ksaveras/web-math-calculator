FROM php:7.4-apache

RUN apt-get update \
    && apt-get install -y git unzip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-install opcache

COPY --from=composer:1.9 /usr/bin/composer /usr/bin/composer
RUN mkdir -p /var/www/.composer \
    && chown -R www-data: /var/www/.composer \
    && mkdir -p /root/.composer \
    && chown -R www-data: /root/.composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_NO_INTERACTION=1

RUN composer global require hirak/prestissimo --no-suggest --optimize-autoloader --classmap-authoritative

ARG NODE_VERSION=10.15.2
ARG NVM_DIR=/usr/local/nvm

# https://github.com/creationix/nvm#install-script
RUN mkdir $NVM_DIR && curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# confirm installation
RUN node -v
RUN npm -v

ARG YARN_VERSION=1.21.1
RUN chown www-data: /var/www
RUN su www-data -s /bin/sh -c 'curl -o- -L https://yarnpkg.com/install.sh | bash -s -- --version $YARN_VERSION'
RUN chown root: /var/www

COPY ./docker/000-default.conf /etc/apache2/sites-available

COPY --chown=www-data composer.json /var/www/html
COPY --chown=www-data composer.lock /var/www/html

RUN su www-data -s /bin/sh -c 'composer install --no-scripts --no-autoloader --no-dev'

COPY --chown=www-data . /var/www/html

ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN  su www-data -s /bin/sh -c '/var/www/.yarn/bin/yarn install'  \
    && su www-data -s /bin/sh -c '/var/www/.yarn/bin/yarn encore prod'  \
    && su www-data -s /bin/sh -c 'composer dump-autoload --optimize'  \
    && su www-data -s /bin/sh -c 'composer run-script post-install-cmd'  \
    && su www-data -s /bin/sh -c 'bin/console cache:warmup -n --no-ansi'
