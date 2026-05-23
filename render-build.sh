#!/usr/bin/env bash
set -e

npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link || true
php artisan migrate --force
php artisan filament:optimize || true
