#!/bin/bash
# ============================================================
# IPure Herbs — SSL Certificate Setup (Let's Encrypt)
# Run AFTER DNS is pointed to this server
# Run: bash 4-ssl.sh
# ============================================================

BACKEND_DOMAIN="api.ipureherbs.com"
FRONTEND_DOMAIN="ipureherbs.com"
EMAIL="your@email.com"   # ← CHANGE THIS

echo "Getting SSL for backend: ${BACKEND_DOMAIN}"
certbot --nginx \
  -d ${BACKEND_DOMAIN} \
  --email ${EMAIL} \
  --agree-tos \
  --non-interactive \
  --redirect

echo "Getting SSL for frontend: ${FRONTEND_DOMAIN} and www.${FRONTEND_DOMAIN}"
certbot --nginx \
  -d ${FRONTEND_DOMAIN} \
  -d www.${FRONTEND_DOMAIN} \
  --email ${EMAIL} \
  --agree-tos \
  --non-interactive \
  --redirect

echo "Testing auto-renewal..."
certbot renew --dry-run

echo ""
echo "=============================================="
echo " SSL setup COMPLETE"
echo " Certificates auto-renew every 90 days"
echo "=============================================="
