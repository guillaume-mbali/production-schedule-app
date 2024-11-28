# Use the official PHP image
FROM php:8.0-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHPStan and Larastan globally via Composer
RUN composer global require phpstan/phpstan larastan/larastan

# Define the working directory
WORKDIR /app

# Add Composer global binaries to PATH
ENV PATH="/root/.composer/vendor/bin:${PATH}"

# Run PHPStan (can be replaced with another script)
CMD ["phpstan", "analyse"]
