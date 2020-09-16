FROM php:7.4 as build

RUN apt-get update \
    && apt-get install -y git unzip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_NO_INTERACTION=1
ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN composer global require hirak/prestissimo --no-suggest --optimize-autoloader --classmap-authoritative

ARG NODE_VERSION=12.18.4
ARG NVM_DIR=/usr/local/nvm

# https://github.com/creationix/nvm#install-script
RUN mkdir $NVM_DIR && curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.35.3/install.sh | bash

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# confirm installation
RUN node -v
RUN npm -v

ARG YARN_VERSION=1.22.5
RUN chown www-data: /var/www
RUN su www-data -s /bin/sh -c 'curl -o- -L https://yarnpkg.com/install.sh | bash -s -- --version $YARN_VERSION'
RUN chown root: /var/www

WORKDIR /var/www/html/
COPY composer.json composer.lock /var/www/html/
RUN composer install --no-scripts --no-autoloader --no-dev

COPY package.json yarn.lock /var/www/html/
RUN /var/www/.yarn/bin/yarn install

COPY . /var/www/html

RUN /var/www/.yarn/bin/yarn encore production && \
    rm -rf node_modules

RUN composer dump-autoload --optimize && \
    composer run-script post-install-cmd && \
    bin/console cache:warmup -n --no-ansi

FROM php:7.4-apache
RUN docker-php-ext-install opcache
COPY ./docker/000-default.conf /etc/apache2/sites-available

ENV APP_ENV=prod \
    APP_DEBUG=0

COPY --chown=www-data --from=build /var/www/html/ /var/www/html/
