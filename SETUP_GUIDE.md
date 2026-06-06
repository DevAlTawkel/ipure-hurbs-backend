# IPure Herbs Backend - Setup & Deployment Guide

## Prerequisites

- PHP 8.2+
- Composer
- MySQL 8.0+ or PostgreSQL
- Node.js (for frontend)

---

## Installation Steps

### Step 1: Navigate to Project Directory
```bash
cd c:\Users\DELL\OneDrive\Documents\GitHub\ipure-hurbs-backend
```

### Step 2: Install Dependencies
```bash
composer install
```

### Step 3: Create Environment File
```bash
copy .env.example .env
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Configure Database

Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ipure_hurbs
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 6: Run Migrations

This will create all necessary database tables:
```bash
php artisan migrate
```

**Expected Output:**
```
Migrating: 2024_01_01_000000_create_users_table
Migrated:  2024_01_01_000000_create_users_table (0.52s)
Migrating: 2024_01_01_000001_create_cache_table
Migrated:  2024_01_01_000001_create_cache_table (0.18s)
...
Migrating: 2026_06_01_000004_create_stock_movements_table
Migrated:  2026_06_01_000004_create_stock_movements_table (0.15s)
```

### Step 7: Seed Demo Data

This will populate the database with categories, brands, and products:
```bash
php artisan db:seed
```

**Alternative - Selective Seeding:**
```bash
php artisan db:seed --class=BrandSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
```

### Step 8: Create Storage Link

For file uploads to work:
```bash
php artisan storage:link
```

### Step 9: Create Admin User (Optional)

If you need an additional admin user:
```bash
php artisan tinker
```

Then in the tinker shell:
```php
>>> use App\Models\User;
>>> User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password123')])
>>> exit
```

### Step 10: Clear Cache

```bash
php artisan optimize:clear
```

### Step 11: Start Development Server

Terminal 1 - Laravel Server:
```bash
php artisan serve
```

Terminal 2 - Queue Worker (for async jobs):
```bash
php artisan queue:listen
```

---

## Accessing the Application

### Admin Panel
- **URL**: http://localhost:8000/admin
- **Default Email**: test@example.com
- **Default Password**: (Check your .env or seed output)

### API Base URL
- **URL**: http://localhost:8000/api

### Frontend (when ready)
- **URL**: http://localhost:8000

---

## Database Tables Created

| Table | Purpose |
|-------|---------|
| users | Admin users |
| customers | Customer accounts |
| products | Product catalog |
| product_images | Product images |
| categories | Product categories |
| brands | Product brands |
| cart | Shopping carts |
| cart_items | Items in cart |
| orders | Customer orders |
| order_items | Items in orders |
| reviews | Product reviews |
| addresses | Customer addresses |
| wishlists | Saved products (NEW) |
| coupons | Discount codes (NEW) |
| stock_movements | Inventory audit trail (NEW) |

---

## Key Features

### 1. Admin Panel (Filament)
Access at `/admin`
- Dashboard with sales reports
- Product management with inventory
- Order management
- Customer management
- Review moderation
- Inventory tracking
- Coupon management

### 2. REST API
Complete REST API for frontend integration
- Products
- Categories
- Brands
- Shopping Cart
- Orders
- Customer Authentication
- Wishlists (New)
- Coupons (New)
- Inventory Reports (New)

### 3. Demo Data
Pre-loaded with:
- 4 Product Categories
- 4 Brands
- 11 Products (with SKUs, barcodes, images, pricing)
- Complete product information

---

## Configuration Files

### Key Configuration Files

#### `config/app.php`
- Application name and environment
- Debug mode
- Timezone

#### `config/auth.php`
- Authentication guards (web, customer)
- Password reset configuration
- API token configuration

#### `config/database.php`
- Database driver selection
- Connection settings

#### `config/mail.php`
- Email configuration
- SMTP settings

#### `config/filesystems.php`
- File storage configuration
- Disk setup

---

## Environment Variables Reference

```env
# Application
APP_NAME="IPure Herbs"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ipure_hurbs
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Stripe (Optional - for payments)
STRIPE_PUBLIC_KEY=your_key
STRIPE_SECRET_KEY=your_key

# Session
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync
```

---

## Common Commands

### Database
```bash
php artisan migrate                  # Run migrations
php artisan migrate:refresh          # Reset and run migrations
php artisan migrate:reset            # Rollback all migrations
php artisan db:seed                  # Seed demo data
php artisan tinker                   # Interactive shell
```

### Development
```bash
php artisan serve                    # Start dev server
php artisan serve --host=0.0.0.0     # Listen on all IPs
php artisan queue:listen             # Start queue worker
php artisan storage:link             # Create storage symlink
```

### Code Quality
```bash
php artisan lint                     # Lint PHP files
./vendor/bin/phpstan analyse         # Static analysis
./vendor/bin/pest                    # Run tests
```

### Optimization
```bash
php artisan optimize                 # Optimize for production
php artisan optimize:clear           # Clear optimization
php artisan config:cache             # Cache config
php artisan config:clear             # Clear config cache
```

---

## Troubleshooting

### 1. "Class not found" Error
```bash
composer dump-autoload
php artisan optimize:clear
```

### 2. Database Connection Failed
- Check `.env` database credentials
- Ensure MySQL/PostgreSQL is running
- Verify database exists

### 3. Migration Error: "Duplicate Column"
```bash
php artisan migrate:refresh          # Reset and re-run
```

### 4. Storage Link Permission Error
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/
```

### 5. Admin Panel Not Loading
- Clear cache: `php artisan optimize:clear`
- Clear views: `php artisan view:clear`
- Check user authentication

---

## Testing

### Run Tests
```bash
./vendor/bin/pest                    # Run all tests
./vendor/bin/pest tests/Feature      # Run feature tests
./vendor/bin/pest --filter=ProductTest # Run specific test
```

### Create Test
```bash
php artisan make:test ProductTest
```

---

## Deployment

### Production Checklist

- [ ] Set `APP_DEBUG=false` in .env
- [ ] Set `APP_ENV=production` in .env
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Generate secure APP_KEY
- [ ] Enable HTTPS
- [ ] Set up database backup
- [ ] Configure environment variables
- [ ] Run migrations on server
- [ ] Set file permissions correctly
- [ ] Configure queue worker with supervisor

### Production Build Steps

```bash
# 1. Clone repository
git clone <repository-url>

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Setup environment
cp .env.production .env

# 4. Generate key
php artisan key:generate

# 5. Run migrations
php artisan migrate --force

# 6. Cache configuration
php artisan config:cache
php artisan route:cache

# 7. Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Start services
# Configure supervisor for queue worker
# Configure nginx/apache for web server
```

---

## Security

### Best Practices

1. **API Authentication**
   - Use Sanctum tokens for API authentication
   - Include token in Authorization header: `Authorization: Bearer {token}`

2. **Data Protection**
   - Hash passwords with bcrypt
   - Use HTTPS in production
   - Implement rate limiting

3. **File Uploads**
   - Validate file types
   - Store outside public directory
   - Use secure file permissions

4. **Database**
   - Use parameterized queries (Eloquent)
   - Regular backups
   - Limit connection permissions

5. **Environment**
   - Never commit .env file
   - Use strong APP_KEY
   - Rotate secrets regularly

---

## Performance Optimization

### Database
```php
// Eager loading - avoid N+1 queries
Product::with('category', 'brand', 'images')->get();

// Chunking for large datasets
Product::chunk(100, function($products) {
    // Process chunk
});
```

### Caching
```php
// Cache query results
$products = Cache::remember('products', 3600, function() {
    return Product::all();
});
```

### Indexing
```sql
CREATE INDEX idx_product_sku ON products(sku);
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_order_customer ON orders(customer_id);
```

---

## Monitoring & Logging

### View Logs
```bash
tail -f storage/logs/laravel.log      # Follow log
```

### Log Channels

Configured in `config/logging.php`:
- `single` - Single file logging
- `daily` - Daily file rotation
- `stack` - Multiple channels
- `syslog` - System log
- `errorlog` - Error log

---

## Support Resources

- Laravel Documentation: https://laravel.com/docs
- Filament Documentation: https://filamentphp.com/docs
- Laravel API Docs: https://laravel.com/api
- Stack Overflow: Tag your questions with `laravel`

---

## License

Proprietary - IPure Herbs

---

## Notes

- Database is seeded with demo data for testing
- Admin user: test@example.com
- API requires proper authentication headers
- Stock movements are logged for audit trail
- Inventory status updates automatically based on stock levels

---

Generated: June 1, 2026
Version: 1.0
