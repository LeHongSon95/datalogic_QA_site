FROM php:8.1.16-fpm-alpine

RUN set -eux \
  && apk update \
  && apk upgrade

RUN apk add --no-cache --update \
  nodejs \
  npm \
  autoconf \
  gcc \
  g++ \
  make \
  linux-headers \
  icu-dev \
  zlib-dev \
  libpng-dev \
  libzip-dev \
  libxml2-dev

RUN docker-php-ext-install \
  intl \
  pdo \
  pdo_mysql \
  gd \
  zip \
  xml \
  opcache

RUN pecl install xdebug-3.2.1
RUN docker-php-ext-enable xdebug

# composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --version="2.3.5" --install-dir=/usr/bin --filename=composer

WORKDIR /var/www/app

RUN addgroup -g 1000 -S appuser \
  && adduser -u 1000 -D -G appuser appuser

USER appuser
