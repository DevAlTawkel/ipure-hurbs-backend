FROM dunglas/frankenphp:php8.3

RUN install-php-extensions intl zip

WORKDIR /app

COPY . .

RUN composer install --no-interaction --optimize-autoloader

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
