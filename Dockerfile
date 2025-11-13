# Multi-stage build for Laravel + Vite + PostgreSQL + Apache

# Stage 1: Build assets
FROM node:20-alpine AS assets

WORKDIR /app

# Copy package files
COPY package*.json ./
RUN npm ci

# Copy source files needed for build
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./
COPY resources ./resources
COPY public ./public

# Build assets
RUN echo "=== Starting Vite build ===" && \
    npm run build && \
    echo "=== Vite build completed ===" && \
    ls -la public/build/ && \
    test -f public/build/manifest.json && \
    echo "✓ Build successful - manifest.json exists" || \
    (echo "✗ ERROR: Build failed or manifest.json missing!" && exit 1)

# Stage 2: PHP application
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy and set up entrypoint script FIRST (before COPY . .)
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Copy application files (excluding public/build which we'll copy separately)
COPY . .
# Remove public/build if it exists from COPY . . (we want the built version)
RUN rm -rf public/build

# Copy built assets from assets stage
COPY --from=assets /app/public/build ./public/build

# Verify assets were copied and show contents
RUN echo "=== Checking built assets in final image ===" && \
    ls -la public/build/ 2>/dev/null || echo "WARNING: public/build directory does not exist!" && \
    test -f public/build/manifest.json && \
    echo "✓ manifest.json exists - Vite assets will be loaded" || \
    (echo "✗ WARNING: manifest.json missing - using Tailwind CDN fallback" && \
     echo "=== Listing public directory ===" && \
     ls -la public/ || true) && \
    echo "=== Asset check complete ==="

# Install PHP dependencies (production only)
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Set permissions (ensure build directory is readable)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 755 /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Configure Apache
RUN a2enmod rewrite headers && \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    ServerName localhost
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Configure PHP
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && echo "upload_max_filesize = 64M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini \
    && echo "post_max_size = 64M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini

# Configure OpCache for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# Use entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
