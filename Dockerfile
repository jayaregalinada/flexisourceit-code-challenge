FROM php:7.3-alpine

ENV COMPOSER_OPTIONS \
    --prefer-dist \
    --no-cache \
    --no-suggest \
    --ignore-platform-reqs \
    --optimize-autoloader

ENV APP_ENV ${APP_ENV:-production}
ENV APP_DEBUG ${APP_DEBUG:-false}

# Install dev dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    curl-dev \
    libtool \
    libxml2-dev

# Install production dependencies
RUN apk add --no-cache \
    bash \
    curl \
    libc-dev \
    libzip-dev \
    icu-dev \
    make \
    zlib-dev \
    git

# Configure php extensions
RUN docker-php-ext-configure zip --with-libzip

# Install php extensions
RUN docker-php-ext-install \
    bcmath \
    calendar \
    curl \
    exif \
    intl \
    mbstring \
    pdo \
    pdo_mysql \
    pcntl \
    tokenizer \
    xml \
    zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN /usr/bin/composer self-update

# Cleanup dev dependencies
RUN apk del -f .build-deps

RUN rm -rf /var/www && \
    mkdir -p /var/www && \
    chown -R www-data:www-data /var/www

# START www-data ##################
USER www-data

COPY --chown=www-data:www-data . /var/www

WORKDIR /var/www

RUN mkdir -p storage && \
    mkdir -p storage/logs && \
    mkdir -p storage/framework && \
    mkdir -p storage/framework/cache && \
    mkdir -p storage/framework/cache/data && \
    mkdir -p storage/framework/views && \
    mkdir -p storage/app && \
    chmod -R 777 storage

RUN mkdir -p bootstrap/cache && \
    chmod -R 777 bootstrap/cache

RUN /usr/bin/composer install $COMPOSER_OPTIONS
################### END www-data #

USER root

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
