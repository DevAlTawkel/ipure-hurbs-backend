FROM dunglas/frankenphp:php8.3

# Install required PHP extensions (IMPORTANT)
RUN install-php-extensions \
    intl \
    zip \
    pdo_mysql \
    mysqli

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --optimize-autoloader

# Clear Laravel cache
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan route:clear
RUN php artisan view:clear

RUN php artisan config:cache

RUN chmod -R 777 storage bootstrap/cache

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
