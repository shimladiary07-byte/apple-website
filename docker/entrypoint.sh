#!/bin/bash
set -e

# Render assigns a dynamic port via $PORT (defaults to 80 if not set)
TARGET_PORT="${PORT:-80}"
echo "Configuring Apache to listen on port ${TARGET_PORT}..."

# Update Apache ports configuration
sed -i -E "s/Listen [0-9]+/Listen ${TARGET_PORT}/g" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost \*:${TARGET_PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Ensure SQLite database file exists if DB_CONNECTION is sqlite
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    mkdir -p "$(dirname "$DB_FILE")"
    if [ ! -f "$DB_FILE" ]; then
        echo "Creating SQLite database at $DB_FILE..."
        touch "$DB_FILE"
    fi
    chown -R www-data:www-data "$(dirname "$DB_FILE")"
    chmod -R 775 "$(dirname "$DB_FILE")"
    chmod 664 "$DB_FILE"
fi

# Ensure storage directories and uploads exist with proper permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache/data}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/public/uploads/products
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/uploads
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/uploads

# Generate application key if not provided
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is missing, generating a temporary key..."
    php artisan key:generate --force || true
fi

# Link storage
php artisan storage:link --force || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed database with initial products, settings and admin if empty
echo "Running database seeder..."
php artisan db:seed --force || true

# Cache Laravel configurations and routes for maximum production speed
echo "Caching Laravel config, routes, and views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Apache web server on port ${TARGET_PORT}..."
exec apache2-foreground
