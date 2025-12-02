#!/bin/sh
set -e

# Ensure storage directories exist and are writable
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chown www-data:www-data database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Run migrations if needed
php artisan migrate --force || true

# Clear and cache config
php artisan config:clear || true
php artisan cache:clear || true

# Start the server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

