FROM dunglas/frankenphp:php8.3

# Install REQUIRED extensions
RUN install-php-extensions \
    intl \
    zip \
    pdo_mysql \
    mysqli

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --optimize-autoloader

# ONLY config cache (NO DB commands)
RUN php artisan config:cache

RUN chmod -R 777 storage bootstrap/cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
