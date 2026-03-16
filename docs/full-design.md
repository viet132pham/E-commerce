# Full E-commerce Design (Backend-First)

This document describes the full feature set, data model, and API surface for the e-commerce platform. The MVP scope is defined separately in docs/mvp-scope.md.

## Goals
- Cover a complete e-commerce workflow: catalog -> cart -> checkout -> order -> fulfillment -> post-purchase.
- Provide a clean API that can scale to new features without breaking.
- Be compatible with Laravel 11 + Postgres.

## Roles
- Guest: browse catalog
- Customer: cart + checkout + order history
- Admin: manage catalog, orders, users, promotions, content
- Staff (optional): limited admin role (fulfillment, support)

## Core Domains
1. Identity & Access
2. Catalog
3. Pricing & Promotions
4. Cart
5. Checkout & Orders
6. Payments
7. Shipping & Fulfillment
8. Inventory
9. Content (CMS-lite)
10. Support/Returns
11. Analytics/Events

## Data Model (High Level)
### Identity & Access
- users
- roles
- permissions
- user_roles (pivot)
- password_resets

### Catalog
- categories
- products
- product_variants
- product_images
- product_attributes
- product_attribute_values
- product_reviews
- brands (optional)

### Pricing & Promotions
- price_lists (optional for B2B)
- promotions
- promotion_rules
- promotion_actions
- coupons
- coupon_redemptions

### Cart
- carts
- cart_items

### Checkout & Orders
- orders
- order_items
- order_addresses (billing/shipping)
- order_status_history
- order_discounts
- order_taxes

### Payments
- payment_methods
- payment_intents
- payment_transactions
- refunds

### Shipping & Fulfillment
- shipping_methods
- shipments
- shipment_items
- tracking_events

### Inventory
- inventory_locations
- inventory_items (per variant)
- inventory_movements
- inventory_reservations

### Content (CMS-lite)
- pages
- menus
- banners

### Support/Returns
- returns
- return_items
- return_reasons
- support_tickets
- ticket_messages

### Analytics/Events
- events
- event_properties

## Key Flows
### 1) Browse -> Cart
- User browses categories/products
- Adds product variant to cart
- Cart persists for user or guest session

### 2) Checkout -> Order
- Validate cart items, pricing, stock
- Create order + order_items
- Create payment intent
- If payment success -> mark paid + create shipment

### 3) Fulfillment
- Admin updates order status
- Create shipments and tracking
- Update inventory movements

### 4) Returns
- Customer requests return
- Admin approves + processes refund

## API Surface (High Level)
### Auth
- POST /api/auth/register
- POST /api/auth/login
- POST /api/auth/logout
- GET /api/auth/me

### Catalog
- GET /api/categories
- GET /api/products
- GET /api/products/{slug}
- GET /api/products/{id}/variants
- GET /api/products/{id}/reviews

### Cart
- GET /api/cart
- POST /api/cart/items
- PATCH /api/cart/items/{id}
- DELETE /api/cart/items/{id}

### Checkout & Orders
- POST /api/checkout
- GET /api/orders
- GET /api/orders/{id}

### Payments
- POST /api/payments/intents
- POST /api/payments/webhook

### Admin
- CRUD for products, categories, variants, orders, users, promotions

## Non-Functional
- Audit logging for admin actions
- Rate limiting on auth and checkout endpoints
- Observability: logs + metrics
- Backups for DB

## Future Extensions
- Multi-vendor
- Multi-currency and price lists
- Loyalty points
- Subscriptions
