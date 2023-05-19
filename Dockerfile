FROM php:7.4-apache

# Set the working directory
WORKDIR /var/www/html

# Install PostgreSQL dependencies
RUN apt-get update \
    && apt-get install -y libpq-dev postgresql-client

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Enable Apache modules
RUN a2enmod rewrite

# Copy application files
COPY . .

# Set file permissions
RUN chown -R www-data:www-data /var/www/html
