#!/bin/bash
set -e

# Wait for database to be ready
echo "Waiting for database..."
until php artisan db:show &> /dev/null; do
  echo "Database is unavailable - sleeping"
  sleep 2
done

echo "Database is up - running migrations..."

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground

