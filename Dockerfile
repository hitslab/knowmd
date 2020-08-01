FROM php:7.3-cli-alpine

LABEL maintainer="Alexandr Ladygin <hitslab@yandex.ru>"

RUN apk update && \
    apk add --no-cache git unzip && \
    php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" && \
    php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer && \
    rm -rf /tmp/composer-setup.php

WORKDIR /project

COPY . /project

RUN composer install

CMD php bin/generate.php
