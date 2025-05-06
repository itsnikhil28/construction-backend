# Use the official PHP 8.2 image with FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# Install system dependencies required for Laravel and MongoDB
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    unzip \
    curl \
    libssl-dev \
    pkg-config \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    ca-certificates

# Install MongoDB extension (specific version)
RUN pecl install mongodb-1.20.0 && docker-php-ext-enable mongodb

# Install PHP extensions required for Laravel
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl

# Set the working directory inside the container
WORKDIR /var/www

# Copy the Laravel application code into the container
COPY . .

# Install Composer - the PHP dependency manager
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Run Composer to install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose port 8000 (adjust if you use Nginx or another web server)
EXPOSE 8000

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
