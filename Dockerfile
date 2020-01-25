FROM php:7.2.0-fpm-alpine

## alpineの形の場合以下のようになる
RUN apk update \
    && apk upgrade \
    && apk add --no-cache \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        jpeg-dev \
        libjpeg \
        libjpeg-turbo-dev 


RUN docker-php-ext-install pdo_mysql mysqli exif gd 
