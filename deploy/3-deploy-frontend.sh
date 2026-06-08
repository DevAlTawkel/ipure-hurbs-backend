#!/bin/bash
# ============================================================
# IPure Herbs — Next.js Frontend Deploy Script
# Run on VPS: bash 3-deploy-frontend.sh
# Also use for updates: bash 3-deploy-frontend.sh update
# ============================================================

set -e

REPO_URL="https://github.com/YOUR_USERNAME/ipure-hurbs-frontend.git"
APP_DIR="/var/www/frontend"
BRANCH="main"
APP_NAME="ipure-frontend"

MODE=${1:-"fresh"}  # fresh | update

echo "========================================"
echo " IPure Herbs Frontend — ${MODE} deploy"
echo "========================================"

if [ "$MODE" = "fresh" ]; then
    echo "Cloning frontend repository..."
    rm -rf ${APP_DIR}
    git clone --branch ${BRANCH} ${REPO_URL} ${APP_DIR}
else
    echo "Pulling latest code..."
    cd ${APP_DIR}
    git fetch origin
    git reset --hard origin/${BRANCH}
fi

cd ${APP_DIR}

echo "Setting up .env.local..."
if [ ! -f .env.local ]; then
    cat > .env.local << 'ENVEOF'
NEXT_PUBLIC_API_URL=https://api.ipureherbs.com/api
NEXT_PUBLIC_STRIPE_KEY=pk_live_YOUR_STRIPE_KEY
NEXTAUTH_URL=https://ipureherbs.com
NEXTAUTH_SECRET=generate_with_openssl_rand_base64_32
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
ENVEOF
    echo ""
    echo "!!! IMPORTANT: Edit /var/www/frontend/.env.local with your values !!!"
fi

echo "Installing Node dependencies..."
npm ci --production=false

echo "Building Next.js..."
npm run build

echo "Starting/Restarting with PM2..."
if pm2 describe ${APP_NAME} > /dev/null 2>&1; then
    pm2 reload ${APP_NAME} --update-env
else
    pm2 start npm --name "${APP_NAME}" -- start
    pm2 save
fi

echo ""
echo "=============================================="
echo " Frontend deploy COMPLETE"
echo " URL: https://ipureherbs.com"
echo " PM2 status: pm2 status"
echo "=============================================="
