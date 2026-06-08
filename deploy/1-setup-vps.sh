#!/bin/bash
# ============================================================
# IPure Herbs — Hostinger VPS One-Time Setup Script
# OS: Ubuntu 22.04 LTS  |  Server: KVM VPS 4
# Run as root: bash 1-setup-vps.sh
# ============================================================

set -e

BACKEND_DOMAIN="api.ipureherbs.com"
FRONTEND_DOMAIN="ipureherbs.com"
DB_NAME="ipureherbs"
DB_USER="ipure"
DB_PASS="Change_This_Strong_Password_123!"
DEPLOY_USER="deploy"

echo "========================================"
echo " Step 1: System update"
echo "========================================"
apt update && apt upgrade -y
apt install -y curl git unzip wget gnupg2 ca-certificates lsb-release \
  software-properties-common apt-transport-https

echo "========================================"
echo " Step 2: Install PHP 8.3 + extensions"
echo "========================================"
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y \
  php8.3-fpm php8.3-cli php8.3-common \
  php8.3-mysql php8.3-xml php8.3-mbstring \
  php8.3-curl php8.3-zip php8.3-gd \
  php8.3-redis php8.3-intl php8.3-bcmath \
  php8.3-tokenizer php8.3-opcache

# PHP-FPM tuning for VPS 4 (4 CPU / 4GB RAM)
cat > /etc/php/8.3/fpm/pool.d/www.conf << 'EOF'
[www]
user = www-data
group = www-data
listen = /run/php/php8.3-fpm.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 3
pm.max_spare_servers = 8
pm.max_requests = 500
EOF

# PHP ini tuning
sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.3/fpm/php.ini
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' /etc/php/8.3/fpm/php.ini
sed -i 's/post_max_size = .*/post_max_size = 20M/' /etc/php/8.3/fpm/php.ini
sed -i 's/max_execution_time = .*/max_execution_time = 60/' /etc/php/8.3/fpm/php.ini
sed -i 's/;opcache.enable=1/opcache.enable=1/' /etc/php/8.3/fpm/php.ini
sed -i 's/;opcache.memory_consumption=128/opcache.memory_consumption=256/' /etc/php/8.3/fpm/php.ini
sed -i 's/;opcache.validate_timestamps=1/opcache.validate_timestamps=0/' /etc/php/8.3/fpm/php.ini

systemctl restart php8.3-fpm
systemctl enable php8.3-fpm
echo "PHP 8.3 installed."

echo "========================================"
echo " Step 3: Install Composer"
echo "========================================"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
echo "Composer installed."

echo "========================================"
echo " Step 4: Install Nginx"
echo "========================================"
apt install -y nginx
systemctl enable nginx
echo "Nginx installed."

echo "========================================"
echo " Step 5: Install MySQL 8"
echo "========================================"
apt install -y mysql-server
systemctl enable mysql

mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# MySQL performance tuning
cat >> /etc/mysql/mysql.conf.d/mysqld.cnf << 'EOF'

# IPure Herbs tuning
innodb_buffer_pool_size = 512M
innodb_log_file_size = 128M
max_connections = 100
query_cache_size = 0
query_cache_type = 0
EOF

systemctl restart mysql
echo "MySQL 8 installed. DB: ${DB_NAME}, User: ${DB_USER}"

echo "========================================"
echo " Step 6: Install Redis"
echo "========================================"
apt install -y redis-server
sed -i 's/^# maxmemory .*/maxmemory 256mb/' /etc/redis/redis.conf
sed -i 's/^# maxmemory-policy .*/maxmemory-policy allkeys-lru/' /etc/redis/redis.conf
systemctl enable redis-server
systemctl restart redis-server
echo "Redis installed."

echo "========================================"
echo " Step 7: Install Node.js 20 + PM2"
echo "========================================"
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
npm install -g pm2
pm2 startup systemd -u root --hp /root
echo "Node.js $(node -v) and PM2 installed."

echo "========================================"
echo " Step 8: Install Supervisor (queue worker)"
echo "========================================"
apt install -y supervisor
systemctl enable supervisor
echo "Supervisor installed."

echo "========================================"
echo " Step 9: Install Certbot (SSL)"
echo "========================================"
apt install -y certbot python3-certbot-nginx
echo "Certbot installed."

echo "========================================"
echo " Step 10: Create deploy user"
echo "========================================"
if ! id "${DEPLOY_USER}" &>/dev/null; then
    adduser --disabled-password --gecos "" ${DEPLOY_USER}
    usermod -aG www-data ${DEPLOY_USER}
fi

mkdir -p /var/www
chown ${DEPLOY_USER}:www-data /var/www

echo "========================================"
echo " Step 11: Firewall setup"
echo "========================================"
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw --force enable
echo "Firewall configured."

echo ""
echo "=============================================="
echo " VPS SETUP COMPLETE"
echo "=============================================="
echo " PHP 8.3:     $(php8.3 -v | head -1)"
echo " MySQL:       $(mysql --version)"
echo " Node.js:     $(node -v)"
echo " Redis:       $(redis-server --version)"
echo ""
echo " DB Name:     ${DB_NAME}"
echo " DB User:     ${DB_USER}"
echo " DB Pass:     ${DB_PASS}   ← CHANGE THIS"
echo ""
echo " Next step:   bash 2-deploy-backend.sh"
echo "=============================================="
