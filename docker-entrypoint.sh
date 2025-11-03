#!/bin/bash
set -e

# Wait for database to be ready (with timeout)
echo "Waiting for database connection..."
MAX_ATTEMPTS=30
ATTEMPT=0

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
  if php artisan db:show &> /dev/null; then
    echo "Database connection successful!"
    break
  fi
  ATTEMPT=$((ATTEMPT + 1))
  echo "Attempt $ATTEMPT/$MAX_ATTEMPTS - Database not ready, waiting..."
  sleep 2
done

if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
  echo "Warning: Could not connect to database after $MAX_ATTEMPTS attempts"
  echo "Continuing anyway - migrations can be run manually later"
else
  # Run migrations (safe to run multiple times)
  echo "Running database migrations..."
  php artisan migrate --force || echo "Migration failed or already up to date"
fi

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start Apache
echo "Starting Apache..."
exec apache2-foreground

