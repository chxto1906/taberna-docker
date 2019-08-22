FROM php:7.2.10-fpm

MAINTAINER Andreas Schlapbach <schlpbch@gmail.com>

RUN apt-get update

RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libzip-dev \
        libxml2-dev \
        libssl-dev \
        libxml2-dev \
        openssl \
    && docker-php-ext-install -j$(nproc) iconv pdo pdo_mysql zip \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apt-get install -y libicu-dev \
    && docker-php-ext-install -j$(nproc) intl

RUN docker-php-ext-install soap
RUN docker-php-ext-configure soap --enable-soap

RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*

#RUN apt-get install git -y \
#    && git clone -b php7 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
#    && docker-php-ext-install -j$(nproc) redis

#Adding MySQL configuration
#COPY redis.ini /etc/php/mods-available/redis.ini
#COPY php.ini /usr/local/etc/php/php.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY hosts /etc/hosts

