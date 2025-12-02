#!/bin/sh
set -e

# Ensure storage directories exist and are writable
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Ensure .env file exists
if [ ! -f .env ]; then
    touch .env
fi

# Ensure APP_DEBUG is set to true (simple append, Laravel will use the last one)
echo "APP_DEBUG=true" >> .env

# Generate APP_KEY if not set in environment
if [ -z "$APP_KEY" ]; then
    # Check if APP_KEY exists in .env file
    if ! grep -q "^APP_KEY=" .env 2>/dev/null; then
        echo "Generating APP_KEY..."
        php artisan key:generate --force
        # Extract the key value and remove base64: prefix
        APP_KEY_VALUE=$(grep "^APP_KEY=" .env | cut -d '=' -f2- | sed 's/^base64://')
        # Remove old APP_KEY line and add new one without base64: prefix
        grep -v "^APP_KEY=" .env > .env.tmp 2>/dev/null || true
        echo "APP_KEY=$APP_KEY_VALUE" >> .env.tmp
        mv .env.tmp .env
    fi
else
    # APP_KEY is set as environment variable - remove base64: prefix if present
    APP_KEY_CLEAN=$(echo "$APP_KEY" | sed 's/^base64://')
    # Remove old APP_KEY line and add new one
    grep -v "^APP_KEY=" .env > .env.tmp 2>/dev/null || true
    echo "APP_KEY=$APP_KEY_CLEAN" >> .env.tmp
    mv .env.tmp .env
fi

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

# Clear and cache config (must be done after .env is ready)
php artisan config:clear || true
php artisan cache:clear || true

# Run migrations if needed
php artisan migrate --force || true

# Start the server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}

