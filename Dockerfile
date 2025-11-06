# Start from PHP 8.2 with FPM
FROM php:8.2-fpm

# Install system dependencies and PostgreSQL driver
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy the app
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 8000

# Run migrations and start the server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
