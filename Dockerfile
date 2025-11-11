# Start from PHP 8.2 FPM
FROM php:8.2-fpm

# Install system dependencies, Node.js, npm, PostgreSQL driver, and useful tools
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libonig-dev curl nodejs npm supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring bcmath

# Copy composer from official composer image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader
RUN composer dump-autoload

# Install and build frontend assets with Vite
RUN npm install && npm run build

# Set proper permissions for Laravel storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 8000

# Set environment variables for Paystack (example placeholders)
ENV PAYSTACK_PUBLIC_KEY=pk_test_1d82a86ef7f0c683eb0fae33f882105192313431
ENV PAYSTACK_SECRET_KEY=sk_test_e615c91dac8a9ccf9f3bb27fcbc860ab44aa2c7c
ENV PAYSTACK_PAYMENT_URL=https://api.paystack.co

# Copy supervisor config
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Run supervisor to manage multiple processes
CMD ["supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
