# ============================================================
# Stage 1: Composer Dependencies
# ============================================================

FROM composer:2 AS composer_builder

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ============================================================
# Stage 2: PHP Runtime
# ============================================================

FROM php:8.2-fpm

WORKDIR /var/www/html


# ============================================================
# System Packages + PHP Extensions
# ============================================================

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libsqlite3-dev \
        unzip \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# ============================================================
# Copy Composer Dependencies
# ============================================================

COPY --from=composer_builder \
    /var/www/html/vendor \
    /var/www/html/vendor


# ============================================================
# Copy Laravel Application
# ============================================================

COPY . .


# ============================================================
# Remove Laravel Cached Bootstrap Files
# ============================================================

RUN rm -f bootstrap/cache/*.php


# ============================================================
# Laravel Permissions
# ============================================================

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache


# ============================================================
# Laravel Package Discovery
# ============================================================

RUN php artisan package:discover --ansi


# ============================================================
# PHP-FPM
# ============================================================

EXPOSE 9000

CMD ["php-fpm"]