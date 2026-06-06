# IPure Herbs Backend - API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication

### Token-Based (Sanctum)

1. **Register**: `POST /auth/register`
2. **Login**: `POST /auth/login`
3. **Include token in headers**:
   ```
   Authorization: Bearer {token}
   ```

---

## Public Endpoints (No Auth Required)

### Home
```
GET /home
```
Returns homepage data (featured products, banners, etc.)

### Products

#### List All Products
```
GET /api/products
```

**Query Parameters:**
- `page=1` - Page number
- `per_page=10` - Items per page

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Organic Aloe Vera Gel",
      "slug": "organic-aloe-vera-gel",
      "sku": "ALOE-001",
      "price": 299,
      "sale_price": 249,
      "stock_status": "in_stock",
      "rating": 4.5,
      "is_featured": true
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

#### Featured Products
```
GET /api/products/featured
```
Get products marked as featured.

#### Trending Products
```
GET /api/products/trending
```
Get trending products.

#### Get Single Product
```
GET /api/products/{slug}
```

**Response:**
```json
{
  "id": 1,
  "name": "Organic Aloe Vera Gel",
  "slug": "organic-aloe-vera-gel",
  "description": "...",
  "short_description": "...",
  "price": 299,
  "sale_price": 249,
  "compare_price": 399,
  "sku": "ALOE-001",
  "barcode": "9780001001001",
  "stock": 120,
  "stock_status": "in_stock",
  "rating": 4.5,
  "reviews_count": 24,
  "sales_count": 156,
  "is_featured": true,
  "is_trending": true,
  "seo_title": "Organic Aloe Vera Gel - Pure & Natural Skincare",
  "seo_description": "Shop pure organic aloe vera gel...",
  "category": {
    "id": 1,
    "name": "Skincare",
    "slug": "skincare"
  },
  "brand": {
    "id": 1,
    "name": "IPure Herbs",
    "slug": "ipure-herbs"
  },
  "images": [
    {
      "id": 1,
      "url": "storage/products/image1.jpg",
      "is_primary": true,
      "sort_order": 1
    }
  ]
}
```

#### Get Related Products
```
GET /api/products/{slug}/related
```
Get products from the same category.

#### Get Product Reviews
```
GET /api/products/{slug}/reviews
```

### Categories

#### List Categories
```
GET /api/categories
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Skincare",
      "slug": "skincare",
      "description": "...",
      "image": null,
      "product_count": 3
    }
  ]
}
```

#### Get Category Details
```
GET /api/categories/{slug}
```

#### Get Category Products
```
GET /api/categories/{slug}/products
```

### Brands

#### List All Brands
```
GET /api/brands
```

#### Brand Directory
```
GET /api/brands/directory
```
Get brands organized by first letter.

#### Get Brand Details
```
GET /api/brands/{slug}
```

#### Get Brand Products
```
GET /api/brands/{slug}/products
```

### Cart (Guest & Authenticated)

#### View Cart
```
GET /api/cart
```

**Response:**
```json
{
  "items": [
    {
      "id": 1,
      "product_id": 1,
      "product_name": "Organic Aloe Vera Gel",
      "quantity": 2,
      "price": 249,
      "subtotal": 498
    }
  ],
  "subtotal": 498,
  "tax": 49.80,
  "total": 547.80
}
```

#### Add to Cart
```
POST /api/cart/items
```

**Payload:**
```json
{
  "product_id": 1,
  "quantity": 1
}
```

#### Update Cart Item
```
PATCH /api/cart/items/{item_id}
```

**Payload:**
```json
{
  "quantity": 3
}
```

#### Remove from Cart
```
DELETE /api/cart/items/{item_id}
```

#### Clear Cart
```
DELETE /api/cart
```

### Coupon Management

#### Validate Coupon
```
POST /api/coupons/validate
```

**Payload:**
```json
{
  "code": "SUMMER20",
  "cart_subtotal": 500
}
```

**Response (Valid):**
```json
{
  "valid": true,
  "coupon": {
    "id": 1,
    "code": "SUMMER20",
    "discount_type": "percentage",
    "discount_value": 20,
    "discount_amount": 100
  },
  "message": "Coupon applied successfully!"
}
```

**Response (Invalid):**
```json
{
  "valid": false,
  "message": "Coupon code not found."
}
```

#### Get Coupon Details
```
GET /api/coupons/{code}
```

### Inventory Information

#### Get Inventory Report
```
GET /api/inventory/report
```

**Response:**
```json
{
  "stats": {
    "total_products": 11,
    "in_stock": 8,
    "low_stock": 2,
    "out_of_stock": 1,
    "total_inventory_value": 45000
  },
  "products": [
    {
      "id": 1,
      "name": "Organic Aloe Vera Gel",
      "sku": "ALOE-001",
      "stock": 120,
      "stock_status": "in_stock"
    }
  ],
  "pagination": { ... }
}
```

#### Low Stock Products
```
GET /api/inventory/low-stock
```

#### Out of Stock Products
```
GET /api/inventory/out-of-stock
```

#### Stock Movement History
```
GET /api/inventory/products/{product_id}/movements
```

---

## Authenticated Endpoints (Requires Login)

### Customer Authentication

#### Register
```
POST /api/auth/register
```

**Payload:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```
POST /api/auth/login
```

**Payload:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "token": "token_string",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

#### Logout
```
POST /api/auth/logout
```

*Requires authentication header*

#### Get Profile
```
GET /api/auth/profile
```

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "1234567890",
  "dob": "1990-01-01",
  "addresses": [...]
}
```

#### Update Profile
```
PATCH /api/auth/profile
```

**Payload:**
```json
{
  "name": "Jane Doe",
  "phone": "0987654321"
}
```

#### Add Address
```
POST /api/auth/addresses
```

**Payload:**
```json
{
  "name": "Home",
  "phone": "1234567890",
  "line1": "123 Main St",
  "line2": "Apt 4B",
  "city": "New York",
  "state": "NY",
  "country": "USA",
  "pincode": "10001",
  "is_default": true
}
```

#### Delete Address
```
DELETE /api/auth/addresses/{address_id}
```

### Wishlist Management

#### Get Wishlist
```
GET /api/wishlist
```

*Requires authentication*

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "product": {
        "id": 1,
        "name": "Organic Aloe Vera Gel",
        "price": 249
      }
    }
  ],
  "pagination": { ... }
}
```

#### Get Wishlist Count
```
GET /api/wishlist/count
```

**Response:**
```json
{
  "count": 5
}
```

#### Add to Wishlist
```
POST /api/wishlist
```

**Payload:**
```json
{
  "product_id": 1
}
```

#### Remove from Wishlist
```
DELETE /api/wishlist
```

**Payload:**
```json
{
  "product_id": 1
}
```

### Orders

#### Get My Orders
```
GET /api/orders
```

*Requires authentication*

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-001",
      "total": 547.80,
      "status": "processing",
      "payment_status": "paid",
      "created_at": "2026-06-01T10:00:00Z"
    }
  ],
  "pagination": { ... }
}
```

#### Get Order Details
```
GET /api/orders/{order_number}
```

**Response:**
```json
{
  "id": 1,
  "order_number": "ORD-001",
  "items": [
    {
      "product_name": "Organic Aloe Vera Gel",
      "quantity": 1,
      "unit_price": 249,
      "subtotal": 249
    }
  ],
  "subtotal": 498,
  "discount": 0,
  "shipping": 50,
  "total": 548,
  "status": "processing",
  "payment_status": "paid",
  "shipping_address": { ... }
}
```

#### Cancel Order
```
POST /api/orders/{order_number}/cancel
```

### Reviews

#### Post Review
```
POST /api/products/{product_slug}/reviews
```

*Requires authentication*

**Payload:**
```json
{
  "title": "Great product!",
  "body": "Very satisfied with this aloe vera gel...",
  "rating": 5
}
```

### Checkout

#### Initiate Checkout
```
POST /api/checkout/initiate
```

*Requires authentication or guest email*

**Payload:**
```json
{
  "shipping_address_id": 1,
  "coupon_code": "SUMMER20"
}
```

**Response:**
```json
{
  "order_id": 1,
  "amount": 548,
  "currency": "USD"
}
```

#### Confirm Checkout
```
POST /api/checkout/confirm
```

**Payload:**
```json
{
  "order_id": 1,
  "payment_method": "stripe",
  "payment_token": "stripe_token_here"
}
```

---

## Error Responses

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### 500 Server Error
```json
{
  "message": "Internal Server Error"
}
```

---

## Rate Limiting

API endpoints are rate-limited to prevent abuse:
- **General API**: 60 requests per minute
- **Auth Endpoints**: 10 requests per minute
- **Checkout**: 5 requests per minute

---

## Response Headers

All API responses include:
```
Content-Type: application/json
X-Request-ID: unique-request-id
```

---

## Example Frontend Usage

### React Example
```javascript
// Get product list
const fetchProducts = async () => {
  const response = await fetch('http://localhost:8000/api/products');
  const data = await response.json();
  return data;
};

// Add to cart
const addToCart = async (productId, quantity) => {
  const response = await fetch('http://localhost:8000/api/cart/items', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity
    })
  });
  return response.json();
};

// Validate coupon
const validateCoupon = async (code, subtotal) => {
  const response = await fetch('http://localhost:8000/api/coupons/validate', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      code: code,
      cart_subtotal: subtotal
    })
  });
  return response.json();
};
```

### Vue Example
```javascript
// Axios interceptor for auth
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Fetch wishlist
const fetchWishlist = () => {
  return axios.get('/api/wishlist');
};

// Add to wishlist
const addToWishlist = (productId) => {
  return axios.post('/api/wishlist', { product_id: productId });
};
```

---

## Pagination

All list endpoints support pagination:
```
GET /api/products?page=2&per_page=20
```

**Response structure:**
```json
{
  "data": [...],
  "links": {
    "first": "http://...",
    "last": "http://...",
    "prev": "http://...",
    "next": "http://..."
  },
  "meta": {
    "current_page": 2,
    "from": 21,
    "last_page": 5,
    "path": "http://...",
    "per_page": 20,
    "to": 40,
    "total": 100
  }
}
```

---

## Filtering & Sorting

### Products Filtering
```
GET /api/products?category=skincare&is_featured=true&sort=-price
```

### Available Filters
- `category` - Filter by category slug
- `brand` - Filter by brand slug
- `is_featured` - Featured products only
- `is_trending` - Trending products only
- `sort` - Sort by field (prefix with `-` for descending)

---

## WebHooks

### Stripe Webhook
```
POST /api/webhooks/stripe
```

**Requires**: Stripe webhook secret configured in environment

---

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Successful request |
| 201 | Created - Resource created |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Auth required |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable - Validation error |
| 429 | Too Many Requests - Rate limited |
| 500 | Server Error |

---

## Important Notes

1. **Stock Status**: Automatically updated based on inventory levels
2. **Pricing**: Sale price takes precedence over regular price in UI
3. **Tax**: Calculated during checkout based on location
4. **Authentication**: Token expires after 60 minutes of inactivity
5. **Inventory**: Stock automatically decrements when order is placed

---

## Support

For API issues or questions, please contact: support@ipurehers.com

---

Generated: June 1, 2026
Version: 1.0
