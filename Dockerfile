# Start from PHP 8.2 with FPM
FROM php:8.2-fpm

# Install system dependencies, Node.js, npm, and PostgreSQL driver
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql

# Copy composer from official composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the application files
COPY . .

# Install PHP dependencies (optimized for production)
# This step creates the vendor directory and fetches dependencies
RUN composer install --no-dev --optimize-autoloader

# --- FIX INSERTED HERE ---
# Force Composer to rebuild the autoload map to ensure global helpers (like fake()) are loaded
RUN composer dump-autoload
# -------------------------

# Install and build frontend assets with Vite
RUN npm install && npm run build

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 8000

# Run migrations and start the Laravel server
CMD php artisan migrate --force && php artisan db:seed  --force && php artisan serve --host=0.0.0.0 --port=8000