#!/bin/sh
set -e

# Ensure storage directories exist and are writable
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not set, generating new key..."
    # Generate a 32-byte random key and encode it in base64
    GENERATED_KEY=$(openssl rand -base64 32 2>/dev/null || head -c 32 /dev/urandom | base64)
    export APP_KEY="base64:${GENERATED_KEY}"
    echo "✅ Generated APP_KEY: $APP_KEY"
    echo "⚠️  IMPORTANT: Copy the key above and set it as APP_KEY environment variable in Render"
    echo "⚠️  This ensures the key persists across deployments"
fi

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

