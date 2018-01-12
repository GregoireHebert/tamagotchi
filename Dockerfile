FROM php:7.1-fpm-alpine

RUN apk add --no-cache --virtual .persistent-deps \
		git \
		icu-libs \
		zlib

ENV APCU_VERSION 5.1.8
ENV PATH /root/.yarn/bin:$PATH

RUN apk add --no-cache --virtual .yarn-deps curl gnupg && \
  curl -o- -L https://yarnpkg.com/install.sh | sh

RUN echo 'http://dl-cdn.alpinelinux.org/alpine/edge/testing' >> /etc/apk/repositories && \
    set -xe \
    apk --update add \
        php7-gd

RUN set -xe \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		zlib-dev \
		freetype-dev \
		libpng-dev \
		libjpeg-turbo-dev \
		libmcrypt-dev \
		freetype \
		libpng \
		libjpeg-turbo \
		libmcrypt \
        gd \
        nodejs \
        python \
	&& docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ && \
        NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
	&& docker-php-ext-install -j${NPROC} \
		intl \
		pdo_mysql \
		zip \
		exif \
		gd \
	&& pecl install \
		apcu-${APCU_VERSION} \
	&& docker-php-ext-enable --ini-name 20-apcu.ini apcu \
	&& docker-php-ext-enable --ini-name 05-opcache.ini opcache

COPY docker/php/php.ini /usr/local/etc/php/php.ini

COPY docker/php/install-composer.sh /usr/local/bin/docker-app-install-composer

RUN chmod +x /usr/local/bin/docker-app-install-composer

RUN set -xe \
	&& apk add --no-cache --virtual .fetch-deps \
		openssl \
	&& docker-app-install-composer \
	&& mv composer.phar /usr/local/bin/composer \
	&& apk del .fetch-deps

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --optimize-autoloader --classmap-authoritative \
	&& composer clear-cache

WORKDIR /srv/tamagotchi

COPY composer.json ./
COPY composer.lock ./

RUN mkdir -p \
		var/cache \
		var/logs \
		var/sessions

COPY app app/
COPY bin bin/
COPY src src/
COPY web web/

COPY docker/php/start.sh /usr/local/bin/docker-app-start

RUN chmod +x /usr/local/bin/docker-app-start

CMD ["docker-app-start"]

RUN cp app/config/parameters.yml.dist app/config/parameters.yml
