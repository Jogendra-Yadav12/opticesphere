# Stage 1: Build frontend assets with Node
FROM node:20 AS node_build
WORKDIR /app
COPY package.json package-lock.json* yarn.lock* ./
RUN yarn install
COPY . .
RUN yarn build

# Stage 2: Setup PHP, Apache, and Laravel
FROM php:8.2-apache
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the rest of the application files
COPY . .

# Copy the built Vite assets from the Node stage
COPY --from=node_build /app/public/build /var/www/html/public/build

# Update Apache configuration to point to Laravel's 'public' folder
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/apache2.conf

# Enable Apache mod_rewrite (required for Laravel routing)
RUN a2enmod rewrite

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set correct permissions for Laravel's cache and storage directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for Render
EXPOSE 80

# Start Apache in the foreground (and run migrations first)
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
