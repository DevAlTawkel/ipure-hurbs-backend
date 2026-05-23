# iPureHerbs Backend

Laravel 13 + Filament 5 ecommerce backend for iPureHerbs — a herbal products store.

## Stack

- **Framework:** Laravel 13.8
- **Admin Panel:** Filament 5.6 at `/ipure`
- **Auth:** Laravel Sanctum (customers) + session (admin)
- **Payments:** Stripe
- **Database:** MySQL (production) / SQLite (local dev)

## Local Setup

```bash
# 1. Clone and install
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database (SQLite for dev)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# 4. Storage link
php artisan storage:link

# 5. Run
php artisan serve
npm run dev
```

## Admin Panel

URL: `http://localhost:8000/ipure`

Create your first admin user:
```bash
php artisan make:filament-user
```

## API Base URL

`http://localhost:8000/api`

See the full endpoint list in [routes/api.php](routes/api.php).

## Environment Variables

| Variable | Description |
|---|---|
| `DB_*` | MySQL connection details |
| `STRIPE_KEY` | Stripe publishable key (`pk_...`) |
| `STRIPE_SECRET` | Stripe secret key (`sk_...`) |
| `STRIPE_WEBHOOK_SECRET` | Stripe webhook signing secret (`whsec_...`) |
| `APP_URL` | Full public URL of the app |

## Deployment (Render)

This repo includes a `render.yaml` blueprint.

1. Push this repo to GitHub
2. Go to [render.com](https://render.com) → New → Blueprint
3. Connect your GitHub repo
4. Render auto-creates the web service + MySQL database
5. Set `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` in the Render dashboard
6. Deploy

## Key Features

- Products with SKU, multiple images, compare price, reviews
- Category & brand management
- Guest + authenticated cart (session token based)
- Checkout with Stripe Payment Intents
- 20% first-order discount for registered customers
- Orders with full shipping snapshot + status tracking
- Stripe webhooks for payment confirmation
- Filament admin: Products, Categories, Brands, Orders, Customers, Sales Report, Stock Management
