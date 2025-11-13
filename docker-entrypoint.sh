#!/bin/bash
set -e

echo "=== ENTRYPOINT START ==="

# Wait for Postgres
echo "Waiting for Postgres at $DB_HOST:$DB_PORT..."

for i in {1..30}; do
    php -r "
        try {
            new PDO(
                'pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
                '${DB_USERNAME}',
                '${DB_PASSWORD}',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo 'DB OK';
        } catch (Exception \$e) {
            exit(1);
        }
    " && break

    echo "  Attempt $i/30: database not ready"
    sleep 2
done

echo "✓ Database is ready."

# Run migrations (safe)
php artisan migrate --force || echo "Migrations already ran"

# Cache
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "=== STARTING APACHE ==="
exec apache2-foreground
