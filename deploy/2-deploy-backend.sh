#!/bin/bash
# ============================================================
# IPure Herbs — Laravel Backend Deploy Script
# Run on VPS: bash 2-deploy-backend.sh
# Also use for updates: bash 2-deploy-backend.sh update
# ============================================================

set -e

REPO_URL="https://github.com/YOUR_USERNAME/ipure-hurbs-backend.git"
APP_DIR="/var/www/backend"
BRANCH="main"

MODE=${1:-"fresh"}  # fresh | update

echo "========================================"
echo " IPure Herbs Backend — ${MODE} deploy"
echo "========================================"

if [ "$MODE" = "fresh" ]; then
    echo "Cloning repository..."
    rm -rf ${APP_DIR}
    git clone --branch ${BRANCH} ${REPO_URL} ${APP_DIR}
else
    echo "Pulling latest code..."
    cd ${APP_DIR}
    git fetch origin
    git reset --hard origin/${BRANCH}
fi

cd ${APP_DIR}

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Setting up .env..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo ""
    echo "!!! IMPORTANT: Edit /var/www/backend/.env with your production values !!!"
    echo "Then run: bash 2-deploy-backend.sh update"
    exit 0
fi

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

echo "Creating storage link..."
php artisan storage:link

echo "Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "Setting permissions..."
chown -R www-data:www-data ${APP_DIR}
chmod -R 775 ${APP_DIR}/storage
chmod -R 775 ${APP_DIR}/bootstrap/cache

echo "Reloading PHP-FPM..."
systemctl reload php8.3-fpm

echo "Restarting queue workers..."
supervisorctl restart ipure-queue:*

echo ""
echo "=============================================="
echo " Backend deploy COMPLETE"
echo " URL: https://api.ipureherbs.com"
echo " Admin: https://api.ipureherbs.com/ipure"
echo "=============================================="
