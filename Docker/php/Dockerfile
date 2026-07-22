FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    libxslt-dev \
    imagemagick-dev \
    imagemagick-libs \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    bash

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp && \
    docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    intl \
    mbstring \
    exif \
    xml \
    xsl \
    opcache && \
    pecl install redis imagick && \
    docker-php-ext-enable redis imagick

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 10M/' "$PHP_INI_DIR/php.ini" && \
    sed -i 's/post_max_size = .*/post_max_size = 12M/' "$PHP_INI_DIR/php.ini" && \
    sed -i 's/memory_limit = .*/memory_limit = 256M/' "$PHP_INI_DIR/php.ini" && \
    sed -i 's/max_execution_time = .*/max_execution_time = 120/' "$PHP_INI_DIR/php.ini" && \
    sed -i 's/;opcache.enable=.*/opcache.enable=1/' "$PHP_INI_DIR/php.ini" && \
    sed -i 's/;opcache.memory_consumption=.*/opcache.memory_consumption=128/' "$PHP_INI_DIR/php.ini"

RUN addgroup -g 1000 wp && adduser -G wp -u 1000 -D wp && \
    mkdir -p /var/www/html && chown -R wp:wp /var/www/html

COPY --chown=wp:wp php.ini "$PHP_INI_DIR/conf.d/hds.ini"

USER wp

WORKDIR /var/www/html
