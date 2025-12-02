#!/bin/sh
set -e

# Ensure storage directories exist and are writable
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Determine database path - use DB_DATABASE env var if set, otherwise default
if [ -n "$DB_DATABASE" ]; then
    DB_PATH="$DB_DATABASE"
else
    DB_PATH="database/database.sqlite"
fi

# Create database directory and file
DB_DIR=$(dirname "$DB_PATH")
mkdir -p "$DB_DIR"
if [ ! -f "$DB_PATH" ]; then
    touch "$DB_PATH"
    chown www-data:www-data "$DB_PATH"
    chmod 664 "$DB_PATH"
fi

# Run migrations first (before clearing cache, as cache table needs to exist)
php artisan migrate --force || true

# Clear config (safe to run anytime)
php artisan config:clear || true

# Clear cache (only if cache tables exist, suppress errors if they don't)
php artisan cache:clear 2>/dev/null || true

# Start the server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

