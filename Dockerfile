# Step 1: Build the Vite assets
FROM node:20 AS node_builder

WORKDIR /app

# Copy package management files first to cache dependencies
COPY package*.json ./

# Install NOde.js dependencies
RUN npm install

# Copy all source files
COPY . .

# Run the build script to generate production assets
RUN npm run build

# Step 2: Set up the PHP environment
FROM php:8.2-cli

# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring dom \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www

# Copy Composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies without dev tools
RUN composer install --no-interaction --no-dev --optimize-autoloader --no-scripts

# Copy the rest of the application
COPY . .

# Copy built assets from the node_builder stage
COPY --from=node_builder /app/public/build ./public/build

# Finalize composer install to run package:discover and other scripts
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Render environments use the $PORT variable, default is 10000
ENV PORT=10000
EXPOSE ${PORT}

# Optimize Laravel for production
# Disabling these can sometimes fix issues if ENV is not yet available, but generally good practice
# RUN php artisan view:cache && \
#    php artisan route:cache

# Final command to serve the application on the port provided by Render
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
