# iPureHerbs Backend — Full Project Documentation

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Technology Stack](#2-technology-stack)
3. [Local Development Setup](#3-local-development-setup)
4. [Live Server Setup (Hostinger)](#4-live-server-setup-hostinger)
5. [Database Tables](#5-database-tables)
6. [API Endpoints](#6-api-endpoints)
7. [Authentication](#7-authentication)
8. [Cart System](#8-cart-system)
9. [Checkout & Stripe Payments](#9-checkout--stripe-payments)
10. [Admin Panel (Filament)](#10-admin-panel-filament)
11. [Features Built](#11-features-built)
12. [Deployment Workflow](#12-deployment-workflow)

---

## 1. Project Overview

**iPureHerbs** is a full e-commerce backend for a herbal products store. It provides a REST API for the frontend (Next.js/React) and a full admin panel for managing the store.

| Item | Value |
|------|-------|
| Project Name | iPureHerbs Backend |
| Owner | Tamim Al-Tawkeel |
| Frontend Developer | Mohamed Aseem |
| Live Site | https://ipureherbs.org |
| Live API | https://api.ipureherbs.org/api/ |
| Admin Panel | https://ipureherbs.org/ipure |
| GitHub | https://github.com/DevAlTawkel/ipure-hurbs-backend |

---

## 2. Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 13.8 |
| Admin Panel | Filament | 5.6 |
| PHP | PHP | 8.3 |
| Database | MySQL | 8.x |
| API Auth | Laravel Sanctum | 4.3 |
| Payments | Stripe | PHP SDK 20.x |
| Frontend Assets | Vite + Tailwind | 4.x |
| Local Server | Laragon | — |
| Hosting | Hostinger Business Shared | — |

---

## 3. Local Development Setup

### Requirements
- Laragon Full (includes PHP 8.3, MySQL, Apache, Composer, Node.js)
- Download from: https://laragon.org

### Step-by-Step Setup (New Machine)

```bash
# 1. Clone the project into Laragon's www folder
git clone https://github.com/DevAlTawkel/ipure-hurbs-backend.git C:\laragon\www\ipureherbs

# 2. Install PHP dependencies
cd C:\laragon\www\ipureherbs
composer install

# 3. Install Node.js dependencies
npm install

# 4. Copy environment file
cp .env.example .env
```

### Configure .env for Local

Open `.env` and set:

```env
APP_NAME="iPureHerbs"
APP_ENV=local
APP_KEY=          # will be generated next
APP_DEBUG=true
APP_URL=http://ipureherbs.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ipureherbs
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_YOUR_KEY
STRIPE_SECRET=sk_test_YOUR_SECRET
STRIPE_WEBHOOK_SECRET=whsec_YOUR_SECRET
```

```bash
# 5. Generate app key
php artisan key:generate

# 6. Create database in HeidiSQL/phpMyAdmin
#    Database name: ipureherbs

# 7. Run migrations
php artisan migrate

# 8. Create storage symlink
php artisan storage:link

# 9. Build frontend assets
npm run build

# 10. Create admin user
php artisan make:filament-user
```

### Running Locally

1. Open **Laragon**
2. Click **Start All** (starts Apache + MySQL)
3. Open browser:

| URL | Purpose |
|-----|---------|
| `http://ipureherbs.test` | Main site |
| `http://ipureherbs.test/api/home` | API test |
| `http://ipureherbs.test/ipure` | Filament admin panel |

> **Important:** Always use `http://` not `https://` for local. Laragon serves HTTP only by default.

> **Note:** Do NOT run `php artisan serve`. Laragon's Apache already serves the project automatically from the `www` folder.

### Local Project Folder

```
C:\laragon\www\ipureherbs\   ← USE THIS FOLDER
C:\projects\ipureherbs-backend\  ← Ignore — incomplete setup
```

---

## 4. Live Server Setup (Hostinger)

### Server Details

| Item | Value |
|------|-------|
| Host | Hostinger Business Shared |
| Domain | https://ipureherbs.org |
| API Subdomain | https://api.ipureherbs.org |
| SSH | `ssh -p 65002 u873283015@145.79.4.64` |
| App Path | `/home/u873283015/ipureherbs/` |
| Web Root | `~/domains/ipureherbs.org/public_html/` → symlinked to `~/ipureherbs/public/` |
| Admin Panel | https://ipureherbs.org/ipure |

### Production Database

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u873283015_ipureherbs
DB_USERNAME=u873283015_Ipure2026
DB_PASSWORD=iPure@111!
```

### Production .env Settings

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ipureherbs.org
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### Hostinger Constraints

| Constraint | Solution |
|-----------|---------|
| `exec()` is disabled | Can't run `php artisan storage:link` |
| Storage symlink | Created manually: `ln -s ~/ipureherbs/storage/app/public ~/ipureherbs/public/storage` |
| No Node.js | Build assets locally with `npm run build` and commit to git |
| PHP 8.3 | Available ✓ |
| Composer | Available ✓ |

### Deploy to Server

```bash
# SSH into server
ssh -p 65002 u873283015@145.79.4.64

# Pull latest code
cd ~/ipureherbs
git pull origin main

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
```

---

## 5. Database Tables

| Table | Purpose |
|-------|---------|
| `users` | Admin accounts (Filament login) |
| `customers` | Customer accounts (API login) |
| `addresses` | Customer delivery addresses |
| `products` | Product catalog |
| `categories` | Product categories |
| `brands` | Product brands |
| `product_images` | Product gallery images |
| `product_variants` | Product sizes / SKU variants |
| `product_sections` | Product detail tabs (ingredients, benefits, etc.) |
| `carts` | Shopping carts (guest + customer) |
| `cart_items` | Items inside carts |
| `orders` | Placed orders |
| `order_items` | Line items in each order |
| `reviews` | Customer product reviews |
| `wishlists` | Customer saved products |
| `coupons` | Discount coupon codes |
| `stock_movements` | Inventory change history |

### Key Field Details

#### products
```
id, category_id, brand_id, sku, barcode, name, slug,
short_description, description, price, compare_price, sale_price,
rating, review_count, sales_count, stock, low_stock_threshold,
stock_status, image, gallery (JSON), tags (JSON),
key_herbal_ingredients (JSON), key_benefits (JSON),
specifications (JSON), indications (JSON),
allergen_info, other_ingredients,
is_active, is_featured, is_trending,
seo_title, seo_description
```

#### product_variants
```
id, product_id, name (e.g. "500g"), sku, price,
compare_price, sale_price, stock,
is_default, is_active, sort_order
```

#### orders
```
id, order_number (IPH-XXXXXXXX), customer_id,
shipping_name, shipping_phone, shipping_line1, shipping_line2,
shipping_city, shipping_state, shipping_country, shipping_pincode,
subtotal, discount_amount, discount_reason,
shipping_charge, total,
status, payment_method, payment_status,
stripe_payment_intent_id, stripe_charge_id, paid_at, notes
```

**Order Statuses:** pending, confirmed, processing, shipped, delivered, cancelled, refunded

**Payment Statuses:** pending, paid, failed, refunded

---

## 6. API Endpoints

**Local base URL:** `http://ipureherbs.test/api`
**Live base URL:** `https://api.ipureherbs.org/api`

### Public Endpoints (no auth needed)

#### Home
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/home` | Featured products, trending, categories |

#### Products
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/products` | List all products (filter: category, brand, q, sort) |
| GET | `/products/featured` | Featured products (limit 8) |
| GET | `/products/trending` | Trending products (limit 8) |
| GET | `/products/{slug}` | Product detail with variants, sections, reviews, related |
| GET | `/products/{slug}/reviews` | Product reviews (paginated) |

#### Categories
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/categories` | All active categories |
| GET | `/categories/{slug}` | Single category |
| GET | `/categories/{slug}/products` | Products in category (paginated) |

#### Brands
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/brands` | All active brands |
| GET | `/brands/directory` | A-Z brand directory |
| GET | `/brands/{slug}` | Single brand |
| GET | `/brands/{slug}/products` | Products in brand (paginated) |

#### Cart (Guest — use X-Cart-Token header)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/cart` | Show cart |
| POST | `/cart/items` | Add item to cart |
| PATCH | `/cart/items/{id}` | Update item quantity |
| DELETE | `/cart/items/{id}` | Remove item |
| DELETE | `/cart` | Clear entire cart |

#### Checkout
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/checkout/initiate` | Get Stripe client_secret + pricing |
| POST | `/checkout/confirm` | Create order after payment |

#### Coupons
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/coupons/validate` | Validate coupon code |
| GET | `/coupons/{code}` | Get coupon details |

#### Webhooks
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/webhooks/stripe` | Stripe webhook (no auth, no CSRF) |

---

### Auth-Required Endpoints (Bearer Token)

#### Customer Auth
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Register new customer |
| POST | `/auth/login` | Login customer |
| POST | `/auth/logout` | Logout |
| GET | `/auth/profile` | Get profile |
| PATCH | `/auth/profile` | Update profile |
| POST | `/auth/addresses` | Add address |
| DELETE | `/auth/addresses/{id}` | Delete address |

#### Orders
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/orders` | Customer order history |
| GET | `/orders/{order_number}` | Single order detail |
| POST | `/orders/{order_number}/cancel` | Cancel order |

#### Reviews
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/products/{slug}/reviews` | Post a review |

#### Wishlist
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/wishlist` | Get wishlist |
| GET | `/wishlist/count` | Wishlist item count |
| POST | `/wishlist` | Add to wishlist |
| DELETE | `/wishlist` | Remove from wishlist |

---

### Request & Response Examples

#### Add to Cart (with variant)
```json
POST /api/cart/items
{
  "product_id": 1,
  "variant_id": 2,
  "qty": 1
}
```

#### Cart Response
```json
{
  "cart_token": "abc123",
  "item_count": 2,
  "total": "140.00",
  "items": [
    {
      "id": 1,
      "qty": 1,
      "price_at_add": "70.00",
      "subtotal": "70.00",
      "product": {
        "id": 1,
        "name": "Ashwagandha Powder",
        "slug": "ashwagandha-powder",
        "image_url": "https://api.ipureherbs.org/storage/products/..."
      },
      "variant": {
        "id": 2,
        "name": "500g",
        "price": "70.00",
        "in_stock": true
      }
    }
  ]
}
```

#### Initiate Checkout
```json
POST /api/checkout/initiate
{
  "address_id": 1,
  "shipping_type": "standard"
}

Response:
{
  "client_secret": "pi_xxx_secret_xxx",
  "payment_intent_id": "pi_xxx",
  "pricing": {
    "subtotal": "140.00",
    "discount_amount": "28.00",
    "discount_reason": "First order 20% off",
    "shipping_charge": "0.00",
    "total": "112.00"
  }
}
```

#### Product Detail Response
```json
{
  "id": 1,
  "sku": "ASHW-001",
  "name": "Ashwagandha Powder",
  "slug": "ashwagandha-powder",
  "tags": ["Best Seller", "Hot"],
  "price": "70.00",
  "compare_price": "90.00",
  "sale_price": "67.00",
  "effective_price": "67.00",
  "formatted_price": "$67.00",
  "has_discount": true,
  "discount_percentage": 25,
  "image_url": "https://...",
  "images": [{"id": 1, "url": "https://..."}],
  "additional_info": {
    "key_herbal_ingredients": ["Ashwagandha Root"],
    "key_benefits": ["Reduces stress", "Boosts energy"],
    "specifications": [{"label": "Form", "value": "Powder"}],
    "indications": ["Stress relief"],
    "allergen_info": null,
    "other_ingredients": null
  },
  "variants": [
    {
      "variant_id": 1,
      "name": "250g",
      "price": "40.00",
      "effective_price": "40.00",
      "formatted_price": "$40.00",
      "has_discount": false,
      "in_stock": true,
      "is_default": false
    },
    {
      "variant_id": 2,
      "name": "500g",
      "price": "70.00",
      "effective_price": "67.00",
      "formatted_price": "$67.00",
      "has_discount": true,
      "in_stock": true,
      "is_default": true
    }
  ],
  "reviews": {"average": 4.5, "total": 12, "data": []},
  "sections": [],
  "related_products": []
}
```

---

## 7. Authentication

### Admin (Filament Panel)
- **URL:** `/ipure`
- **Model:** `User`
- **Guard:** `web` (session-based)
- **Login:** Email + password

### Customers (API)
- **Model:** `Customer` (separate from User)
- **Guard:** `customer`
- **Token:** Laravel Sanctum Bearer token
- **Usage:**
  ```
  Authorization: Bearer {token}
  ```

### Guest Cart
- No login needed for cart
- On first `GET /api/cart` or `POST /api/cart/items`, a cart token is returned
- **Save this token and send it on every cart request:**
  ```
  X-Cart-Token: {cart_token}
  ```
- When customer logs in, guest cart merges with their account cart

---

## 8. Cart System

### How it works

1. Guest visits site → call `GET /api/cart` → get a `cart_token`
2. Save `cart_token` in localStorage/cookie on frontend
3. Send `X-Cart-Token: {token}` header on every cart request
4. Customer logs in → call `POST /api/auth/login` → cart auto-merges
5. Proceed to checkout

### Add item (no variant)
```json
POST /api/cart/items
{ "product_id": 1, "qty": 2 }
```

### Add item (with variant — required if product has variants)
```json
POST /api/cart/items
{ "product_id": 1, "variant_id": 3, "qty": 1 }
```

---

## 9. Checkout & Stripe Payments

### Pricing Rules

| Rule | Value |
|------|-------|
| Standard shipping | Free if order ≥ $100, else $30 |
| Express shipping | Always $40 |
| First-order discount | 20% off for first-time registered customers |

### Full Checkout Flow

```
Step 1: POST /api/checkout/initiate
        → Send: address_id, shipping_type (standard/express)
        → Receive: client_secret, payment_intent_id, pricing breakdown

Step 2: Frontend uses Stripe.js to confirm payment with client_secret
        (customer enters card details)

Step 3: POST /api/checkout/confirm
        → Send: payment_intent_id, address data
        → Receive: order_number, order details
        → Stock is deducted, cart is cleared, email is sent
```

### Stripe Webhook
- Endpoint: `POST /api/webhooks/stripe`
- No CSRF, no auth required
- Handles: `payment_intent.succeeded`, `payment_intent.payment_failed`, `charge.refunded`

---

## 10. Admin Panel (Filament)

**URL:** `/ipure` (local: `http://ipureherbs.test/ipure`)

### Resources Available

| Resource | What it manages |
|---------|----------------|
| Products | Full product CRUD with all tabs |
| Categories | Product categories |
| Brands | Product brands with logo |
| Orders | View/update order status, send emails |
| Customers | View customer accounts and order history |
| Reviews | Approve/reject product reviews |
| Coupons | Create and manage discount codes |
| Inventory | Stock reports, movement history |
| Wishlists | View customer wishlists |

### Product Form Tabs

| Tab | Fields |
|-----|--------|
| Basic Information | Name, Slug, SKU, Category, Brand, Description, Short Description, Tags |
| Pricing | Price, Compare Price, Sale Price |
| Inventory | Stock, Low Stock Threshold, Stock Status |
| Size Variants | Repeater: Name, SKU, Price, Compare Price, Sale Price, Stock, Is Default |
| Gallery | Multiple image upload (up to 4, stored in `products/gallery/`) |
| Additional Info | Key Herbal Ingredients, Key Benefits, Specifications, Indications, Allergen Info, Other Ingredients |
| SEO & Settings | SEO Title, SEO Description, Is Active, Is Featured, Is Trending |

### Order Management

From the Orders list, admin can:
- Update order status (pending → confirmed → processing → shipped → delivered)
- Cancel or refund orders
- Send shipping confirmation email
- Send delivery confirmation email
- View full order details with items and customer info

---

## 11. Features Built

### Products
- Full product catalog with categories and brands
- Multi-image gallery (up to 4 images per product)
- Product variants (different sizes with separate pricing and stock)
- Product sections (rich content tabs for ingredients, benefits, directions, etc.)
- Tags (Best Seller, Hot, New Arrival, Deal, Sale, etc.)
- SEO fields (title, description)
- Featured and trending flags
- Ratings aggregation (average rating, review count)
- Stock tracking with low stock alerts

### Shopping
- Guest cart using session token (no login needed)
- Guest-to-customer cart merge on login
- Add items with or without variants
- Update quantities, remove items, clear cart
- Stock validation on add-to-cart

### Checkout & Payments
- Stripe Payment Intents (full PCI-compliant flow)
- First-order 20% discount for new customers (auto-applied)
- Free shipping on orders ≥ $100
- Express shipping option
- Full shipping address snapshot stored on order
- Order number auto-generated (IPH-XXXXXXXX format)

### Customers
- Registration and login with Sanctum tokens
- Profile management (name, phone, date of birth)
- Multiple saved addresses with one default
- Order history with full details
- Order cancellation

### Reviews
- Customers can review any product
- Verified purchase badge (if linked to an order)
- One review per customer per product
- Admin approval before showing publicly
- Average rating auto-calculated

### Wishlist
- Save products for later
- Requires customer login
- Count badge for UI

### Coupons
- Fixed amount or percentage discounts
- Minimum spend requirement
- Usage limits (total and per customer)
- Validity date range
- Active/inactive toggle

### Inventory
- Real-time stock tracking per product (and per variant)
- Stock movements history (purchase, return, adjustment, damaged, lost)
- Low stock threshold alerts
- Out-of-stock filtering

### Email Notifications
- Welcome email on registration
- Order confirmation email on purchase
- Shipping confirmation email (admin sends from panel)
- Delivery confirmation email (admin sends from panel)

### Admin Panel
- Full Filament 5.6 admin at `/ipure`
- Protected by email + password login
- All e-commerce management in one place

---

## 12. Deployment Workflow

### Every time you make a code change

Run these commands on your local machine (in the project folder):

```bash
git add .
git commit -m "Your description of what changed"
git push origin main
```

Then SSH into the server and pull:

```bash
ssh -p 65002 u873283015@145.79.4.64
cd ~/ipureherbs
git pull origin main
php artisan migrate --force
php artisan config:cache
php artisan route:cache
```

### If you added new images or changed frontend assets

Build locally first, then push:

```bash
npm run build
git add .
git commit -m "Rebuild assets"
git push origin main
```

Then SSH and pull (no migration needed if no DB changes).

---

*Documentation last updated: June 2026*
