# Local development image for the Job Portal (Phase 1A).
# Not hardened for production.
FROM php:8.4-fpm-alpine

# System deps + PHP extensions (intl is required by Filament).
RUN apk add --no-cache \
        bash git icu-dev libzip-dev oniguruma-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j"$(nproc)" \
        intl pdo pdo_mysql bcmath zip pcntl \
    && apk del $PHPIZE_DEPS

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js for asset builds
RUN apk add --no-cache nodejs npm

COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-jobportal.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

WORKDIR /var/www/html

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
