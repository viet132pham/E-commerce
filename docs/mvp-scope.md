# MVP Scope (Backend)

The MVP prioritizes core commerce flows while keeping the data model extensible.

## MVP Features
1. Auth (register/login/logout, profile)
2. Catalog (categories, products, product variants)
3. Cart (add/remove/update items)
4. Checkout (create order)
5. Orders (view order history)
6. Admin (basic CRUD for products, categories, orders)

## Out of Scope (for MVP)
- Promotions/coupons
- Payments gateway integration (can be stubbed)
- Shipping carrier integrations
- Returns and refunds
- Reviews and ratings
- Content pages and CMS
- Analytics/events

## MVP Data Model (Minimal)
- users
- roles
- permissions (optional for MVP)
- categories
- products
- product_variants
- product_images
- carts
- cart_items
- orders
- order_items
- order_addresses

## MVP API Endpoints
Auth
- POST /api/auth/register
- POST /api/auth/login
- POST /api/auth/logout
- GET /api/auth/me

Catalog
- GET /api/categories
- GET /api/products
- GET /api/products/{slug}

Cart
- GET /api/cart
- POST /api/cart/items
- PATCH /api/cart/items/{id}
- DELETE /api/cart/items/{id}

Orders
- POST /api/checkout
- GET /api/orders
- GET /api/orders/{id}

Admin
- CRUD /api/admin/products
- CRUD /api/admin/categories
- CRUD /api/admin/orders
