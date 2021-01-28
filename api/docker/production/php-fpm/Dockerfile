FROM php:7.4-cli-alpine AS builder

RUN apk add unzip

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && rm -rf /root/.composer/cache

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --prefer-dist --no-progress --no-suggest --no-scripts --optimize-autoloader \
    && rm -rf /root/.composer/cache

#############

FROM php:7.4-fpm-alpine

RUN apk add postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

RUN apk add unzip

RUN docker-php-ext-install opcache

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./production/php/conf.d /usr/local/etc/php/conf.d
COPY ./production/php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY ./production/php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

COPY --from=builder /app ./

COPY ./ ./
