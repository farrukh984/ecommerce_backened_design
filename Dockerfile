# Step 1: Build the Vite assets
FROM node:20 AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Step 2: Set up the PHP environment with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring dom \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Change Apache Root to Laravel's /public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .
COPY --from=node_builder /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Render uses $PORT, Apache uses 80 by default. We tell Apache to listen to $PORT.
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Final command to run Apache
CMD ["apache2-foreground"]
