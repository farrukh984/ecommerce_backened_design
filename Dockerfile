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

# 1. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 2. Enable AllowOverride All
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 3. Change Apache Root to /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .
COPY --from=node_builder /app/public/build ./public/build

# Install PHP dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Create storage symlink AND set permissions (ZAROORI)
RUN php artisan storage:link
RUN chown -R www-data:www-data storage bootstrap/cache public/storage

# Listen to Render Port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

CMD ["apache2-foreground"]
