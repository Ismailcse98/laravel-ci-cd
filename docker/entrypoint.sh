#!/bin/sh

set -e

echo "=========================================="
echo "Laravel Docker Container Starting..."
echo "=========================================="

echo "Creating Laravel writable directories..."

mkdir -p \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache


echo "Setting Laravel permissions..."

chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache


echo "Laravel permissions configured."

echo "Starting PHP-FPM..."

exec "$@"