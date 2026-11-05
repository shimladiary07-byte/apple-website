# -------------------------------------------------------------
# Stage 1: Build Frontend Assets (Vite + Vue 3 + Tailwind CSS)
# -------------------------------------------------------------
FROM node:22-slim AS frontend-builder
WORKDIR /app

COPY package*.json .npmrc* ./
RUN npm install --legacy-peer-deps --no-audit

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# -------------------------------------------------------------
# Stage 2: Production PHP 8.4 Apache Environment
# -------------------------------------------------------------
FROM php:8.4-apache

WORKDIR /var/www/html

# Install system packages & PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates \
    git \
    curl \
    unzip \
    zip \
    sqlite3 \
    libsqlite3-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_sqlite \
        pdo_mysql \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
    && a2enmod rewrite headers \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Production PHP settings
RUN { \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "memory_limit = 256M"; \
    echo "opcache.enable = 1"; \
    echo "opcache.memory_consumption = 128"; \
    echo "opcache.interned_strings_buffer = 8"; \
    echo "opcache.max_accelerated_files = 10000"; \
    echo "opcache.revalidate_freq = 2"; \
    echo "opcache.validate_timestamps = 1"; \
} > /usr/local/etc/php/conf.d/custom.ini

# Copy composer files first for optimal Docker layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# Copy application code
COPY . .

# Copy compiled frontend assets from Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Setup runtime storage, cache directories and permissions
RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    storage/app/public \
    public/uploads/products \
    bootstrap/cache \
    database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database public/uploads \
    && chmod -R 775 storage bootstrap/cache database public/uploads \
    && chmod +x docker/entrypoint.sh

# Expose default port
EXPOSE 80

# Entrypoint script
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
