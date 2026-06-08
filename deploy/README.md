# IPure Herbs — Hostinger VPS Deployment Guide
## Server: KVM VPS 4 | Ubuntu 22.04 LTS

---

## Files in this folder

```
deploy/
├── 1-setup-vps.sh              ← Run ONCE to install all software
├── 2-deploy-backend.sh         ← Deploy / update Laravel backend
├── 3-deploy-frontend.sh        ← Deploy / update Next.js frontend
├── 4-ssl.sh                    ← Get SSL certificates
├── env.production.example      ← Copy this to /var/www/backend/.env
├── nginx/
│   ├── backend.conf            ← Nginx config for Laravel API
│   └── frontend.conf           ← Nginx config for Next.js
└── supervisor/
    └── ipure-queue.conf        ← Queue worker config
```

---

## Pre-requisites

Before starting, make sure you have:
- [ ] Hostinger KVM VPS 4 purchased
- [ ] Ubuntu 22.04 selected as OS
- [ ] VPS IP address noted
- [ ] DNS A records pointed to your VPS IP:
  - `ipureherbs.com` → VPS IP
  - `www.ipureherbs.com` → VPS IP
  - `api.ipureherbs.com` → VPS IP
- [ ] GitHub repositories ready (backend + frontend)

---

## Step-by-Step Deployment

### STEP 1 — Connect to your VPS

```bash
ssh root@YOUR_VPS_IP
```

---

### STEP 2 — Upload and run the setup script

On your **local machine**, upload the deploy folder:
```bash
scp -r deploy/ root@YOUR_VPS_IP:/root/
```

On the **VPS**, run the one-time setup:
```bash
cd /root/deploy
chmod +x *.sh
bash 1-setup-vps.sh
```

**What this installs:**
- PHP 8.3 + all extensions (mysql, redis, gd, zip, mbstring, opcache...)
- Composer
- Nginx
- MySQL 8 (creates `ipureherbs` database)
- Redis
- Node.js 20 + PM2
- Supervisor
- Certbot (SSL)
- UFW firewall

**Time:** ~5-10 minutes

---

### STEP 3 — Deploy the Laravel Backend

```bash
bash 2-deploy-backend.sh fresh
```

This will clone your repo. Then it will stop and ask you to fill in `.env`.

**Fill in the .env file:**
```bash
cp /root/deploy/env.production.example /var/www/backend/.env
nano /var/www/backend/.env
```

Edit these values:
| Variable | What to put |
|---|---|
| `APP_KEY` | Run `php artisan key:generate` first |
| `DB_PASSWORD` | The password you set in `1-setup-vps.sh` |
| `STRIPE_KEY` | Your Stripe live publishable key |
| `STRIPE_SECRET` | Your Stripe live secret key |
| `STRIPE_WEBHOOK_SECRET` | From Stripe Dashboard webhooks |
| `MAIL_USERNAME` | Your Mailgun SMTP username |
| `MAIL_PASSWORD` | Your Mailgun SMTP password |
| `GOOGLE_CLIENT_ID` | From Google Cloud Console |
| `GOOGLE_CLIENT_SECRET` | From Google Cloud Console |

**Generate APP_KEY:**
```bash
cd /var/www/backend
php artisan key:generate
```

**Run deploy again:**
```bash
bash /root/deploy/2-deploy-backend.sh update
```

---

### STEP 4 — Deploy the Next.js Frontend

```bash
bash 3-deploy-frontend.sh fresh
```

**Fill in frontend .env:**
```bash
nano /var/www/frontend/.env.local
```

| Variable | What to put |
|---|---|
| `NEXT_PUBLIC_API_URL` | `https://api.ipureherbs.com/api` |
| `NEXT_PUBLIC_STRIPE_KEY` | Your Stripe live publishable key |
| `NEXTAUTH_SECRET` | Run: `openssl rand -base64 32` |
| `GOOGLE_CLIENT_ID` | From Google Cloud Console |

**Run deploy again:**
```bash
bash /root/deploy/3-deploy-frontend.sh update
```

---

### STEP 5 — Configure Nginx

```bash
# Copy nginx configs
cp /root/deploy/nginx/backend.conf  /etc/nginx/sites-available/backend
cp /root/deploy/nginx/frontend.conf /etc/nginx/sites-available/frontend

# Enable them
ln -sf /etc/nginx/sites-available/backend  /etc/nginx/sites-enabled/backend
ln -sf /etc/nginx/sites-available/frontend /etc/nginx/sites-enabled/frontend

# Remove default nginx site
rm -f /etc/nginx/sites-enabled/default

# Test and reload
nginx -t && systemctl reload nginx
```

---

### STEP 6 — Get SSL Certificates

> DNS must be pointed to this server before this step!

```bash
# Edit your email in 4-ssl.sh first
nano /root/deploy/4-ssl.sh

bash /root/deploy/4-ssl.sh
```

---

### STEP 7 — Configure Supervisor (Queue Worker)

```bash
cp /root/deploy/supervisor/ipure-queue.conf /etc/supervisor/conf.d/ipure-queue.conf
supervisorctl reread
supervisorctl update
supervisorctl start ipure-queue:*
supervisorctl status
```

---

### STEP 8 — Set up Cron Job (Laravel Scheduler)

```bash
crontab -e
```

Add this line:
```
* * * * * cd /var/www/backend && php artisan schedule:run >> /dev/null 2>&1
```

---

### STEP 9 — Verify Everything Works

```bash
# Test backend API
curl https://api.ipureherbs.com/api/categories

# Test frontend
curl https://ipureherbs.com

# Check queue workers
supervisorctl status

# Check PM2 (Next.js)
pm2 status

# Check Nginx
systemctl status nginx

# Check PHP-FPM
systemctl status php8.3-fpm

# Check MySQL
systemctl status mysql

# Check Redis
redis-cli ping   # Should return PONG
```

---

## Updating the App (After Code Changes)

### Update backend only
```bash
ssh root@YOUR_VPS_IP
bash /root/deploy/2-deploy-backend.sh update
```

### Update frontend only
```bash
ssh root@YOUR_VPS_IP
bash /root/deploy/3-deploy-frontend.sh update
```

---

## Useful Commands

```bash
# View backend logs
tail -f /var/www/backend/storage/logs/laravel.log

# View queue worker logs
tail -f /var/www/backend/storage/logs/queue.log

# View Nginx error log
tail -f /var/log/nginx/backend-error.log

# Restart all services
systemctl restart php8.3-fpm nginx mysql redis-server supervisor

# Clear Laravel cache (after config changes)
cd /var/www/backend
php artisan config:clear && php artisan cache:clear && php artisan route:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Restart Next.js
pm2 restart ipure-frontend

# Restart queue workers
supervisorctl restart ipure-queue:*
```

---

## Server Architecture Diagram

```
Internet
    │
    ▼
Cloudflare (optional CDN)
    │
    ▼
Hostinger VPS (Ubuntu 22.04)
    │
    ├── Nginx (port 80/443)
    │       ├── ipureherbs.com    → Next.js (port 3000 via PM2)
    │       └── api.ipureherbs.com → PHP-FPM → Laravel (/var/www/backend)
    │
    ├── MySQL 8           (port 3306, local only)
    ├── Redis             (port 6379, local only)
    ├── Supervisor        (queue workers — emails)
    └── Cron              (Laravel scheduler)
```

---

## Stripe Webhook Setup

1. Go to [Stripe Dashboard](https://dashboard.stripe.com) → Developers → Webhooks
2. Click **Add endpoint**
3. URL: `https://api.ipureherbs.com/api/webhooks/stripe`
4. Events to select:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
5. Copy the **Signing secret** → paste as `STRIPE_WEBHOOK_SECRET` in `.env`

---

## Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create project → Enable **Google+ API** or **Google Identity**
3. OAuth consent screen → External
4. Credentials → Create OAuth 2.0 Client ID → Web application
5. Authorized redirect URIs:
   - `https://api.ipureherbs.com/api/auth/google/callback`
6. Copy Client ID + Secret → paste in `.env`

---

## Cost Estimate

| Item | Cost |
|---|---|
| Hostinger KVM VPS 4 | ~$15-20/month |
| Domain (ipureherbs.com) | ~$12/year |
| Mailgun (email) | Free up to 100/day |
| Stripe | 2.9% + $0.30 per transaction |
| SSL | FREE (Let's Encrypt) |
| **Total** | **~$20/month** |

---

## Railway vs VPS Comparison

| Feature | Railway | Hostinger VPS |
|---|---|---|
| Setup effort | Minutes | 1-2 hours |
| Cost | $5-20/month + DB | ~$20/month all-in |
| Control | Limited | Full root access |
| Scaling | Easy (click) | Manual |
| Custom domains | Yes | Yes |
| Queue workers | Extra service | Supervisor (free) |
| Redis | Extra service | Included |
| Recommended for | Quick deploys | Production/custom |
