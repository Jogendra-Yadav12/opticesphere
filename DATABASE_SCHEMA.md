# Laravel Multivendor Marketplace & Tiered-Product Platform — Complete Database Schema & Migrations

This document contains **every table** for the project, grouped by module, with column names, data types, indexes, foreign keys, and the **full migration code** for each.

> Run migrations in the order presented here to satisfy foreign-key dependencies.
> Tables marked (package) are installed automatically by their Laravel package.

---

## Table of Contents

1. [Auth & Users](#1-auth--users)
2. [Roles & Permissions](#2-roles--permissions--package)
3. [Sanctum & Passport](#3-sanctum--passport--package)
4. [Vendor Management](#4-vendor-management)
5. [Catalog](#5-catalog)
6. [Cart & Checkout](#6-cart--checkout)
7. [Orders & Payments](#7-orders--payments)
8. [Coupons & Shipping](#8-coupons--shipping)
9. [Subscriptions (Tiered Products)](#9-subscriptions-tiered-products)
10. [Payouts & Commissions](#10-payouts--commissions)
11. [Reviews & Wishlists](#11-reviews--wishlists)
12. [Messaging & Support](#12-messaging--support)
13. [Notifications](#13-notifications)
14. [Content & CMS](#14-content--cms)
15. [System Tables](#15-system-tables)
16. [Relationship Summary](#16-eloquent-relationship-summary)
17. [Complete Migration File Tree](#17-complete-migration-file-tree)

---

## 1. Auth & Users

### Table: `users`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | auto-increment |
| name | varchar(191) | |
| email | varchar(191) | unique |
| email_verified_at | timestamp | nullable |
| password | varchar(191) | hashed |
| phone | varchar(30) | nullable |
| avatar | varchar(191) | nullable |
| status | enum('active','banned','suspended') | default 'active' |
| remember_token | varchar(100) | nullable |
| last_login_at | timestamp | nullable |
| created_at / updated_at | timestamp | |

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'banned', 'suspended'])->default('active');
            $table->rememberToken();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### Table: `admins`

Separate admin table with its own guard so super-admin accounts are isolated from store users.

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | varchar(191) | |
| email | varchar(191) | unique |
| password | varchar(191) | hashed |
| role | enum('super_admin','support','finance','content') | default 'support' |
| status | boolean | default true |
| two_factor_secret | text | nullable |
| last_login_at | timestamp | nullable |
| created_at / updated_at | | |

```php
Schema::create('admins', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['super_admin', 'support', 'finance', 'content'])->default('support');
    $table->boolean('status')->default(true);
    $table->text('two_factor_secret')->nullable();
    $table->timestamp('last_login_at')->nullable();
    $table->timestamps();
});
```

### Table: `password_reset_tokens`

```php
Schema::create('password_reset_tokens', function (Blueprint $table) {
    $table->string('email')->primary();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});
```

### Table: `sessions`

```php
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
```

### Table: `oauth_accounts` (social login)

```php
Schema::create('oauth_accounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('provider');                 // google, facebook, apple
    $table->string('provider_id');
    $table->text('token')->nullable();
    $table->timestamps();
    $table->unique(['provider', 'provider_id']);
});
```

---

## 2. Roles & Permissions (package)

Installed by `spatie/laravel-permission`. Publish with:

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('guard_name');
    $table->timestamps();
    $table->unique(['name', 'guard_name']);
});

Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('guard_name');
    $table->timestamps();
    $table->unique(['name', 'guard_name']);
});

Schema::create('model_has_permissions', function (Blueprint $table) {
    $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
    $table->string('model_type');
    $table->unsignedBigInteger('model_id');
    $table->index(['model_id', 'model_type']);
    $table->primary(['permission_id', 'model_id', 'model_type']);
});

Schema::create('model_has_roles', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->string('model_type');
    $table->unsignedBigInteger('model_id');
    $table->index(['model_id', 'model_type']);
    $table->primary(['role_id', 'model_id', 'model_type']);
});

Schema::create('role_has_permissions', function (Blueprint $table) {
    $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->primary(['permission_id', 'role_id']);
});
```

### Suggested roles
`customer`, `vendor`, `vendor_admin` (web guard) — plus `admin` permissions on the `admins` guard.

---

## 3. Sanctum & Passport (package)

### `personal_access_tokens` (Sanctum)

```php
Schema::create('personal_access_tokens', function (Blueprint $table) {
    $table->id();
    $table->morphs('tokenable');
    $table->string('name');
    $table->string('token', 64)->unique();
    $table->text('abilities')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
});
```

### OAuth tables (Passport)

```php
Schema::create('oauth_auth_codes', function (Blueprint $table) {
    $table->string('id', 100)->primary();
    $table->unsignedBigInteger('user_id')->index();
    $table->unsignedBigInteger('client_id');
    $table->text('scopes')->nullable();
    $table->boolean('revoked');
    $table->dateTime('expires_at')->nullable();
});

Schema::create('oauth_access_tokens', function (Blueprint $table) {
    $table->string('id', 100)->primary();
    $table->unsignedBigInteger('user_id')->nullable()->index();
    $table->unsignedBigInteger('client_id');
    $table->string('name')->nullable();
    $table->text('scopes')->nullable();
    $table->boolean('revoked');
    $table->timestamps();
    $table->dateTime('expires_at')->nullable();
});

Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
    $table->string('id', 100)->primary();
    $table->string('access_token_id', 100)->index();
    $table->boolean('revoked');
    $table->dateTime('expires_at')->nullable();
});

Schema::create('oauth_clients', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('user_id')->nullable()->index();
    $table->string('name');
    $table->string('secret', 100)->nullable();
    $table->string('provider')->nullable();
    $table->text('redirect');
    $table->boolean('personal_access_client');
    $table->boolean('password_client');
    $table->boolean('revoked');
    $table->timestamps();
});

Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->unsignedBigInteger('client_id');
    $table->timestamps();
});
```

---

## 4. Vendor Management

### Table: `vendors`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| user_id | FK → users | unique |
| store_name | varchar(191) | |
| slug | varchar(191) | unique |
| description | text | nullable |
| logo / banner | varchar(191) | nullable |
| status | enum('pending','approved','suspended','rejected') | default 'pending' |
| commission_rate | decimal(5,2) | default 10.00 |
| commission_type | enum('percentage','flat') | default 'percentage' |
| tax_number | varchar(191) | nullable |
| address / city / state / postal_code / country | varchar | nullable |
| phone | varchar(30) | nullable |
| rating_avg | decimal(3,2) | default 0.00 |
| total_sales | decimal(15,2) | default 0.00 |
| approved_at | timestamp | nullable |
| created_at / updated_at | | |

```php
Schema::create('vendors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('store_name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('logo')->nullable();
    $table->string('banner')->nullable();
    $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
    $table->decimal('commission_rate', 5, 2)->default(10.00);
    $table->enum('commission_type', ['percentage', 'flat'])->default('percentage');
    $table->string('tax_number')->nullable();
    $table->string('address')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('postal_code')->nullable();
    $table->string('country')->nullable();
    $table->string('phone', 30)->nullable();
    $table->decimal('rating_avg', 3, 2)->default(0.00);
    $table->decimal('total_sales', 15, 2)->default(0.00);
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();

    $table->index(['status', 'commission_rate']);
});
```

### Table: `vendor_documents`

```php
Schema::create('vendor_documents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['business_license', 'id_proof', 'tax_certificate', 'bank_proof']);
    $table->string('file_path');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->string('notes')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();
});
```

### Table: `vendor_payment_methods`

```php
Schema::create('vendor_payment_methods', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['bank', 'paypal', 'stripe']);
    $table->json('details');                 // encrypted account details
    $table->boolean('is_default')->default(false);
    $table->timestamps();
});
```

### Table: `vendor_settings`

```php
Schema::create('vendor_settings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->string('key');
    $table->text('value')->nullable();
    $table->timestamps();
    $table->unique(['vendor_id', 'key']);
});
```

---

## 5. Catalog

### Table: `categories`

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('image')->nullable();
    $table->string('icon')->nullable();
    $table->unsignedInteger('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['parent_id', 'is_active']);
});
```

### Table: `brands`

```php
Schema::create('brands', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('logo')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Table: `products`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| vendor_id | FK → vendors | |
| brand_id | FK → brands | nullable |
| name | varchar(191) | |
| slug | varchar(191) | unique |
| sku | varchar(191) | |
| short_description | text | nullable |
| description | longText | nullable |
| price | decimal(15,2) | |
| compare_at_price | decimal(15,2) | nullable |
| cost_price | decimal(15,2) | nullable |
| stock_quantity | integer | default 0 |
| low_stock_threshold | integer | default 5 |
| weight / height / width / length | decimal(10,2) | nullable |
| product_type | enum('simple','variable','digital','tiered_subscription') | default 'simple' |
| status | enum('draft','active','inactive','archived') | default 'draft' |
| approval_status | enum('pending','approved','rejected') | default 'pending' |
| is_featured | boolean | default false |
| is_taxable | boolean | default true |
| meta_title / meta_description | varchar/text | nullable |
| approved_by | FK → admins | nullable |
| approved_at | timestamp | nullable |
| created_at / updated_at | | |

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('sku');
    $table->text('short_description')->nullable();
    $table->longText('description')->nullable();
    $table->decimal('price', 15, 2);
    $table->decimal('compare_at_price', 15, 2)->nullable();
    $table->decimal('cost_price', 15, 2)->nullable();
    $table->integer('stock_quantity')->default(0);
    $table->integer('low_stock_threshold')->default(5);
    $table->decimal('weight', 10, 2)->nullable();
    $table->decimal('height', 10, 2)->nullable();
    $table->decimal('width', 10, 2)->nullable();
    $table->decimal('length', 10, 2)->nullable();
    $table->enum('product_type', ['simple', 'variable', 'digital', 'tiered_subscription'])->default('simple');
    $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
    $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->boolean('is_featured')->default(false);
    $table->boolean('is_taxable')->default(true);
    $table->string('meta_title')->nullable();
    $table->text('meta_description')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();

    $table->index(['vendor_id', 'status', 'approval_status']);
    $table->index(['slug', 'sku']);
    $table->index('product_type');
});
```

### Table: `category_product` (pivot)

```php
Schema::create('category_product', function (Blueprint $table) {
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->primary(['category_id', 'product_id']);
});
```

### Table: `product_tag` (pivot)

```php
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});

Schema::create('product_tag', function (Blueprint $table) {
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
    $table->primary(['product_id', 'tag_id']);
});
```

### Table: `attributes`

```php
Schema::create('attributes', function (Blueprint $table) {
    $table->id();
    $table->string('name');                    // Color, Size, Storage
    $table->string('slug')->unique();
    $table->enum('type', ['text', 'select', 'color', 'button'])->default('select');
    $table->boolean('is_global')->default(false);
    $table->timestamps();
});
```

### Table: `attribute_values`

```php
Schema::create('attribute_values', function (Blueprint $table) {
    $table->id();
    $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
    $table->string('value');                   // Red, Medium, 256GB
    $table->string('color_code')->nullable();  // #FF0000
    $table->unsignedInteger('sort_order')->default(0);
    $table->timestamps();
    $table->unique(['attribute_id', 'value']);
});
```

### Table: `product_variants`

```php
Schema::create('product_variants', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('name');                    // "Red / Medium"
    $table->string('sku')->unique();
    $table->decimal('price', 15, 2);
    $table->decimal('compare_at_price', 15, 2)->nullable();
    $table->integer('stock_quantity')->default(0);
    $table->string('barcode')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();

    $table->index(['product_id', 'status']);
});
```

### Table: `variant_attribute_value` (pivot)

```php
Schema::create('variant_attribute_value', function (Blueprint $table) {
    $table->foreignId('variant_id')->constrained('product_variants')->cascadeOnDelete();
    $table->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();
    $table->primary(['variant_id', 'attribute_value_id']);
});
```

### Table: `product_images`

```php
Schema::create('product_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
    $table->string('path');
    $table->string('alt_text')->nullable();
    $table->unsignedInteger('sort_order')->default(0);
    $table->boolean('is_primary')->default(false);
    $table->timestamps();

    $table->index(['product_id', 'is_primary']);
});
```

### Table: `stock_movements` (inventory audit log)

```php
Schema::create('stock_movements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
    $table->enum('type', ['in', 'out', 'adjust', 'reserved', 'released']);
    $table->integer('quantity_change');
    $table->integer('quantity_after');
    $table->string('reason')->nullable();
    $table->nullableMorphs('reference');       // order_item, return, etc.
    $table->foreignId('causer_id')->nullable();
    $table->string('causer_type')->nullable();
    $table->timestamps();

    $table->index(['product_id', 'created_at']);
});
```

### Table: `product_translations` (localized fields)

```php
Schema::create('product_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('locale');                  // en, ar
    $table->string('name');
    $table->text('short_description')->nullable();
    $table->longText('description')->nullable();
    $table->timestamps();
    $table->unique(['product_id', 'locale']);
});
```

---

## 6. Cart & Checkout

### Table: `carts`

```php
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
    $table->string('session_id')->nullable();
    $table->timestamps();

    $table->index(['user_id']);
    $table->index(['session_id']);
});
```

### Table: `cart_items`

```php
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
    $table->unsignedInteger('quantity')->default(1);
    $table->decimal('unit_price', 15, 2);
    $table->decimal('line_total', 15, 2);
    $table->timestamps();

    $table->unique(['cart_id', 'product_id', 'variant_id']);
});
```

### Table: `addresses`

```php
Schema::create('addresses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['billing', 'shipping'])->default('shipping');
    $table->string('full_name');
    $table->string('phone', 30);
    $table->string('address_line1');
    $table->string('address_line2')->nullable();
    $table->string('city');
    $table->string('state');
    $table->string('postal_code');
    $table->string('country');
    $table->boolean('is_default')->default(false);
    $table->timestamps();

    $table->index(['user_id', 'type']);
});
```

### Table: `wishlists`

```php
Schema::create('wishlists', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['user_id', 'product_id']);
});
```

---

## 7. Orders & Payments

### Table: `orders`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| order_number | varchar(30) | unique, e.g. `ORD-2026-000123` |
| user_id | FK → users | |
| subtotal | decimal(15,2) | |
| discount_amount | decimal(15,2) | default 0 |
| tax_amount | decimal(15,2) | default 0 |
| shipping_amount | decimal(15,2) | default 0 |
| total_amount | decimal(15,2) | |
| currency | char(3) | default 'USD' |
| coupon_code | varchar(50) | nullable |
| status | enum('pending','processing','shipped','delivered','cancelled','refunded') | default 'pending' |
| payment_status | enum('unpaid','paid','failed','refunded','partially_refunded') | default 'unpaid' |
| payment_method | varchar(50) | nullable |
| shipping_address_id | FK → addresses | nullable |
| billing_address_id | FK → addresses | nullable |
| notes | text | nullable |
| gateway_charge_id | varchar(191) | nullable |
| placed_at | timestamp | nullable |
| created_at / updated_at | | |

```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number', 30)->unique();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->decimal('subtotal', 15, 2);
    $table->decimal('discount_amount', 15, 2)->default(0);
    $table->decimal('tax_amount', 15, 2)->default(0);
    $table->decimal('shipping_amount', 15, 2)->default(0);
    $table->decimal('total_amount', 15, 2);
    $table->char('currency', 3)->default('USD');
    $table->string('coupon_code', 50)->nullable();
    $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
    $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'refunded', 'partially_refunded'])->default('unpaid');
    $table->string('payment_method', 50)->nullable();
    $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
    $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();
    $table->text('notes')->nullable();
    $table->string('gateway_charge_id')->nullable();
    $table->timestamp('placed_at')->nullable();
    $table->timestamps();

    $table->index(['user_id', 'status', 'payment_status']);
    $table->index(['status', 'placed_at']);
});
```

### Table: `order_items`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| order_id | FK → orders | cascade |
| product_id | FK → products | restrict |
| variant_id | FK → product_variants | nullable |
| vendor_id | FK → vendors | for split payouts |
| product_name | varchar(191) | snapshot |
| sku | varchar(191) | snapshot |
| quantity | integer | |
| unit_price | decimal(15,2) | |
| line_total | decimal(15,2) | |
| tax_amount | decimal(15,2) | default 0 |
| discount_amount | decimal(15,2) | default 0 |
| commission_rate | decimal(5,2) | snapshot at sale time |
| commission_amount | decimal(15,2) | default 0 |
| vendor_earning | decimal(15,2) | default 0 |
| refunded_quantity | integer | default 0 |
| created_at / updated_at | | |

```php
Schema::create('order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->restrictOnDelete();
    $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->string('product_name');
    $table->string('sku');
    $table->unsignedInteger('quantity');
    $table->decimal('unit_price', 15, 2);
    $table->decimal('line_total', 15, 2);
    $table->decimal('tax_amount', 15, 2)->default(0);
    $table->decimal('discount_amount', 15, 2)->default(0);
    $table->decimal('commission_rate', 5, 2);
    $table->decimal('commission_amount', 15, 2)->default(0);
    $table->decimal('vendor_earning', 15, 2)->default(0);
    $table->unsignedInteger('refunded_quantity')->default(0);
    $table->timestamps();

    $table->index(['vendor_id', 'order_id']);
});
```

### Table: `order_status_histories`

```php
Schema::create('order_status_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->string('status');
    $table->string('comment')->nullable();
    $table->nullableMorphs('causer');          // admin or vendor who changed it
    $table->timestamps();

    $table->index(['order_id', 'created_at']);
});
```

### Table: `shipments`

```php
Schema::create('shipments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('shipping_method_id')->nullable()->constrained()->nullOnDelete();
    $table->string('carrier')->nullable();
    $table->string('tracking_number')->nullable();
    $table->enum('status', ['pending', 'picked', 'in_transit', 'out_for_delivery', 'delivered', 'failed'])->default('pending');
    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();

    $table->index(['status', 'tracking_number']);
});
```

### Table: `payments`

```php
Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('payment_method');          // stripe, razorpay, paypal, cod
    $table->string('gateway_transaction_id')->nullable();
    $table->string('reference_id')->nullable();
    $table->decimal('amount', 15, 2);
    $table->char('currency', 3)->default('USD');
    $table->enum('status', ['pending', 'processing', 'succeeded', 'failed', 'refunded'])->default('pending');
    $table->json('payload')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();

    $table->index(['order_id', 'status']);
    $table->index('gateway_transaction_id');
});
```

### Table: `refunds`

```php
Schema::create('refunds', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_item_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
    $table->decimal('amount', 15, 2);
    $table->string('reason')->nullable();
    $table->enum('status', ['requested', 'approved', 'processed', 'rejected'])->default('requested');
    $table->foreignId('processed_by')->nullable()->constrained('admins')->nullOnDelete();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();
});
```

### Table: `returns`

```php
Schema::create('returns', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
    $table->unsignedInteger('quantity');
    $table->enum('reason', ['wrong_item', 'defective', 'not_as_described', 'other']);
    $table->text('notes')->nullable();
    $table->enum('status', ['requested', 'approved', 'received', 'refunded', 'rejected'])->default('requested');
    $table->timestamps();
});
```

---

## 8. Coupons & Shipping

### Table: `coupons`

```php
Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code', 50)->unique();
    $table->enum('type', ['fixed', 'percent', 'free_shipping']);
    $table->decimal('value', 15, 2);
    $table->unsignedInteger('max_uses')->nullable();
    $table->unsignedInteger('used_count')->default(0);
    $table->decimal('min_order_amount', 15, 2)->nullable();
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->index(['code', 'is_active', 'expires_at']);
});
```

### Table: `coupon_user`

```php
Schema::create('coupon_user', function (Blueprint $table) {
    $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->unsignedInteger('usage_count')->default(0);
    $table->timestamps();
    $table->primary(['coupon_id', 'user_id']);
});
```

### Table: `shipping_methods`

```php
Schema::create('shipping_methods', function (Blueprint $table) {
    $table->id();
    $table->string('name');                     // Standard, Express
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('base_cost', 15, 2)->default(0);
    $table->decimal('cost_per_kg', 15, 2)->default(0);
    $table->unsignedInteger('estimated_days_min')->default(3);
    $table->unsignedInteger('estimated_days_max')->default(7);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Table: `shipping_zones`

```php
Schema::create('shipping_zones', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->json('countries');                  // ["US","CA"]
    $table->timestamps();
});
```

### Table: `shipping_method_zone` (pivot)

```php
Schema::create('shipping_method_zone', function (Blueprint $table) {
    $table->foreignId('shipping_method_id')->constrained()->cascadeOnDelete();
    $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();
    $table->decimal('cost', 15, 2)->default(0);
    $table->primary(['shipping_method_id', 'shipping_zone_id']);
});
```

### Table: `tax_rates`

```php
Schema::create('tax_rates', function (Blueprint $table) {
    $table->id();
    $table->string('name');                     // VAT, GST, Sales Tax
    $table->string('country')->nullable();
    $table->string('state')->nullable();
    $table->decimal('rate', 5, 2);              // 18.00 = 18%
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

## 9. Subscriptions (Tiered Products)

### Table: `plans`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| name | varchar(191) | e.g. "Software Suite" |
| slug | varchar(191) | unique |
| description | text | nullable |
| type | enum('subscription','one_time_digital') | default 'subscription' |
| status | enum('active','inactive','archived') | default 'active' |
| created_at / updated_at | | |

```php
Schema::create('plans', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->enum('type', ['subscription', 'one_time_digital'])->default('subscription');
    $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
    $table->timestamps();
});
```

### Table: `plan_tiers`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| plan_id | FK → plans | |
| name | varchar(191) | Basic / Pro / Enterprise |
| slug | varchar(191) | |
| description | text | nullable |
| price | decimal(15,2) | |
| billing_period | enum('monthly','yearly') | |
| trial_days | integer | default 0 |
| sort_order | integer | default 0 |
| is_active | boolean | default true |
| created_at / updated_at | | |

```php
Schema::create('plan_tiers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug');
    $table->text('description')->nullable();
    $table->decimal('price', 15, 2);
    $table->enum('billing_period', ['monthly', 'yearly']);
    $table->unsignedInteger('trial_days')->default(0);
    $table->unsignedInteger('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['plan_id', 'slug', 'billing_period']);
});
```

### Table: `features`

```php
Schema::create('features', function (Blueprint $table) {
    $table->id();
    $table->string('name');                     // "Unlimited products"
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

### Table: `plan_tier_feature` (pivot — feature matrix)

```php
Schema::create('plan_tier_feature', function (Blueprint $table) {
    $table->foreignId('plan_tier_id')->constrained()->cascadeOnDelete();
    $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
    $table->string('value')->nullable();        // "1000", "true", "unlimited"
    $table->boolean('is_included')->default(true);
    $table->primary(['plan_tier_id', 'feature_id']);
});
```

### Table: `subscriptions`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| user_id | FK → users | |
| plan_tier_id | FK → plan_tiers | |
| status | enum('active','trialing','past_due','cancelled','expired','paused') | default 'trialing' |
| current_period_start | timestamp | |
| current_period_end | timestamp | |
| trial_ends_at | timestamp | nullable |
| ends_at | timestamp | nullable (scheduled cancellation) |
| cancel_at_period_end | boolean | default false |
| gateway | varchar(50) | stripe / razorpay / paypal |
| gateway_subscription_id | varchar(191) | nullable |
| gateway_customer_id | varchar(191) | nullable |
| price | decimal(15,2) | snapshot |
| billing_period | enum('monthly','yearly') | |
| created_at / updated_at | | |

```php
Schema::create('subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('plan_tier_id')->constrained()->cascadeOnDelete();
    $table->enum('status', ['active', 'trialing', 'past_due', 'cancelled', 'expired', 'paused'])->default('trialing');
    $table->timestamp('current_period_start');
    $table->timestamp('current_period_end');
    $table->timestamp('trial_ends_at')->nullable();
    $table->timestamp('ends_at')->nullable();
    $table->boolean('cancel_at_period_end')->default(false);
    $table->string('gateway', 50)->nullable();
    $table->string('gateway_subscription_id')->nullable();
    $table->string('gateway_customer_id')->nullable();
    $table->decimal('price', 15, 2);
    $table->enum('billing_period', ['monthly', 'yearly']);
    $table->timestamps();

    $table->index(['user_id', 'status']);
    $table->index('gateway_subscription_id');
});
```

### Table: `subscription_items`

```php
Schema::create('subscription_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
    $table->foreignId('plan_tier_id')->constrained()->cascadeOnDelete();
    $table->unsignedInteger('quantity')->default(1);
    $table->timestamps();
    $table->unique(['subscription_id', 'plan_tier_id']);
});
```

### Table: `subscription_histories` (audit trail)

```php
Schema::create('subscription_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
    $table->string('event');                    // subscribed, upgraded, downgraded, renewed, cancelled, payment_failed
    $table->json('data')->nullable();
    $table->timestamps();

    $table->index(['subscription_id', 'created_at']);
});
```

### Table: `invoices`

```php
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('invoice_number')->unique();
    $table->decimal('amount', 15, 2);
    $table->char('currency', 3)->default('USD');
    $table->enum('status', ['draft', 'paid', 'open', 'void', 'refunded'])->default('draft');
    $table->string('gateway_invoice_id')->nullable();
    $table->timestamp('period_start')->nullable();
    $table->timestamp('period_end')->nullable();
    $table->string('invoice_pdf')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();

    $table->index(['user_id', 'status']);
});
```

### Table: `webhook_calls`

```php
Schema::create('webhook_calls', function (Blueprint $table) {
    $table->id();
    $table->string('type');                     // stripe.payment_intent.succeeded
    $table->string('gateway');
    $table->string('event_id')->nullable();
    $table->json('payload')->nullable();
    $table->json('headers')->nullable();
    $table->enum('status', ['received', 'processed', 'failed'])->default('received');
    $table->text('error')->nullable();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();

    $table->index(['gateway', 'event_id']);
});
```

### Table: `trial_usage` / `usage_records`

```php
Schema::create('usage_records', function (Blueprint $table) {
    $table->id();
    $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
    $table->string('metric');                   // api_calls, storage_gb
    $table->unsignedBigInteger('quantity')->default(0);
    $table->date('recorded_on');
    $table->timestamps();
    $table->unique(['subscription_id', 'metric', 'recorded_on']);
});
```

---

## 10. Payouts & Commissions

### Table: `vendor_ledgers`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| vendor_id | FK → vendors | |
| type | enum('sale_credit','commission','payout','refund','adjustment') | |
| amount | decimal(15,2) | signed |
| balance_after | decimal(15,2) | |
| description | string | nullable |
| reference_type / reference_id | morphs | order_item, payout_request |
| created_at | | (append-only ledger) |

```php
Schema::create('vendor_ledgers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['sale_credit', 'commission', 'payout', 'refund', 'adjustment']);
    $table->decimal('amount', 15, 2);           // positive = credit, negative = debit
    $table->decimal('balance_after', 15, 2);
    $table->string('description')->nullable();
    $table->nullableMorphs('reference');
    $table->timestamps();

    $table->index(['vendor_id', 'created_at']);
    $table->index(['vendor_id', 'type']);
});
```

### Table: `commissions`

```php
Schema::create('commissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->enum('rate_type', ['percentage', 'flat'])->default('percentage');
    $table->decimal('rate', 5, 2);
    $table->decimal('amount', 15, 2);
    $table->enum('status', ['pending', 'settled', 'refunded'])->default('pending');
    $table->timestamp('settled_at')->nullable();
    $table->timestamps();

    $table->index(['vendor_id', 'status']);
});
```

### Table: `payout_requests`

| Column | Type | Notes |
|---|---|---|
| id | bigint UNSIGNED PK | |
| vendor_id | FK → vendors | |
| amount | decimal(15,2) | |
| fee | decimal(15,2) | default 0 |
| method | enum('bank','paypal','stripe') | |
| account_details | json | encrypted |
| status | enum('pending','approved','processing','completed','failed','cancelled') | default 'pending' |
| gateway_transaction_id | varchar | nullable |
| processed_by | FK → admins | nullable |
| processed_at | timestamp | nullable |
| created_at / updated_at | | |

```php
Schema::create('payout_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount', 15, 2);
    $table->decimal('fee', 15, 2)->default(0);
    $table->enum('method', ['bank', 'paypal', 'stripe']);
    $table->json('account_details');
    $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
    $table->string('gateway_transaction_id')->nullable();
    $table->foreignId('processed_by')->nullable()->constrained('admins')->nullOnDelete();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();

    $table->index(['vendor_id', 'status']);
    $table->index('status');
});
```

### Table: `wallets` (optional customer wallet/credit)

```php
Schema::create('wallets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->decimal('balance', 15, 2)->default(0);
    $table->timestamps();
});

Schema::create('wallet_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['credit', 'debit']);
    $table->decimal('amount', 15, 2);
    $table->decimal('balance_after', 15, 2);
    $table->string('description');
    $table->nullableMorphs('reference');
    $table->timestamps();
});
```

---

## 11. Reviews & Wishlists

### Table: `reviews` (polymorphic)

```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->morphs('reviewable');               // product, vendor, tier
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->unsignedTinyInteger('rating');      // 1-5
    $table->string('title')->nullable();
    $table->text('body')->nullable();
    $table->boolean('is_verified_purchase')->default(false);
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->timestamps();

    $table->index(['reviewable_type', 'reviewable_id', 'status']);
});
```

### Table: `review_images`

```php
Schema::create('review_images', function (Blueprint $table) {
    $table->id();
    $table->foreignId('review_id')->constrained()->cascadeOnDelete();
    $table->string('path');
    $table->timestamps();
});
```

### Table: `review_replies`

```php
Schema::create('review_replies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('review_id')->constrained()->cascadeOnDelete();
    $table->nullableMorphs('replier');          // vendor or admin
    $table->text('body');
    $table->timestamps();
});
```

---

## 12. Messaging & Support

### Table: `conversations`

```php
Schema::create('conversations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
    $table->timestamp('last_message_at')->nullable();
    $table->timestamps();
});
```

### Table: `conversation_user` (pivot)

```php
Schema::create('conversation_user', function (Blueprint $table) {
    $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->timestamp('last_read_at')->nullable();
    $table->primary(['conversation_id', 'user_id']);
});
```

### Table: `messages`

```php
Schema::create('messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
    $table->nullableMorphs('sender');           // user, vendor, admin
    $table->text('body')->nullable();
    $table->enum('type', ['text', 'image', 'file'])->default('text');
    $table->timestamp('read_at')->nullable();
    $table->timestamps();

    $table->index(['conversation_id', 'created_at']);
});
```

### Table: `message_attachments`

```php
Schema::create('message_attachments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('message_id')->constrained()->cascadeOnDelete();
    $table->string('path');
    $table->string('mime_type');
    $table->unsignedBigInteger('size');
    $table->timestamps();
});
```

### Table: `support_tickets`

```php
Schema::create('support_tickets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
    $table->string('subject');
    $table->text('message');
    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
    $table->enum('status', ['open', 'answered', 'on_hold', 'closed'])->default('open');
    $table->foreignId('assigned_to')->nullable()->constrained('admins')->nullOnDelete();
    $table->timestamps();

    $table->index(['user_id', 'status']);
});
```

### Table: `ticket_replies`

```php
Schema::create('ticket_replies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
    $table->nullableMorphs('replier');
    $table->text('body');
    $table->boolean('is_staff')->default(false);
    $table->timestamps();
});
```

---

## 13. Notifications

### Table: `notifications` (package)

```php
Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('type');
    $table->morphs('notifiable');
    $table->text('data');
    $table->timestamp('read_at')->nullable();
    $table->timestamps();

    $table->index(['notifiable_type', 'notifiable_id']);
});
```

### Table: `notification_preferences`

```php
Schema::create('notification_preferences', function (Blueprint $table) {
    $table->id();
    $table->nullableMorphs('notifiable');
    $table->string('event');                    // order_placed, payout_processed
    $table->enum('channel', ['mail', 'database', 'broadcast', 'sms'])->default('mail');
    $table->boolean('enabled')->default(true);
    $table->timestamps();
    $table->unique(['notifiable_type', 'notifiable_id', 'event', 'channel']);
});
```

### Table: `announcements`

```php
Schema::create('announcements', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('body');
    $table->enum('audience', ['all', 'customers', 'vendors', 'admins'])->default('all');
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('ends_at')->nullable();
    $table->boolean('is_published')->default(false);
    $table->timestamps();
});
```

---

## 14. Content & CMS

### Table: `pages`

```php
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->longText('body');
    $table->string('meta_title')->nullable();
    $table->text('meta_description')->nullable();
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->timestamps();
});
```

### Table: `banners`

```php
Schema::create('banners', function (Blueprint $table) {
    $table->id();
    $table->string('title')->nullable();
    $table->string('image');
    $table->string('link')->nullable();
    $table->enum('position', ['hero', 'sidebar', 'top_bar', 'bottom'])->default('hero');
    $table->unsignedInteger('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('ends_at')->nullable();
    $table->timestamps();
});
```

### Table: `blog_posts` (optional)

```php
Schema::create('blog_posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('excerpt')->nullable();
    $table->longText('content');
    $table->string('cover_image')->nullable();
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### Table: `newsletters` (subscribers)

```php
Schema::create('newsletters', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();
    $table->boolean('is_subscribed')->default(true);
    $table->string('token')->nullable();
    $table->timestamps();
});
```

### Table: `settings`

```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->nullable();
    $table->string('group')->default('general');   // general, payment, mail, seo
    $table->string('type')->default('string');     // string, json, boolean, number
    $table->timestamps();
});
```

### Table: `currency_rates`

```php
Schema::create('currency_rates', function (Blueprint $table) {
    $table->id();
    $table->char('base_currency', 3);          // USD
    $table->char('target_currency', 3);        // EUR
    $table->decimal('rate', 15, 6);
    $table->timestamp('updated_at')->useCurrent();
    $table->unique(['base_currency', 'target_currency']);
});
```

### Table: `countries` / `states` / `cities`

```php
Schema::create('countries', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->char('code', 2)->unique();
    $table->char('phone_code', 6)->nullable();
    $table->timestamps();
});

Schema::create('states', function (Blueprint $table) {
    $table->id();
    $table->foreignId('country_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('code')->nullable();
    $table->timestamps();
});

Schema::create('cities', function (Blueprint $table) {
    $table->id();
    $table->foreignId('state_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->timestamps();
});
```

---

## 15. System Tables

### Tables: `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` (package)

```php
Schema::create('cache', function (Blueprint $table) {
    $table->string('key')->primary();
    $table->mediumText('value');
    $table->integer('expiration');
});

Schema::create('cache_locks', function (Blueprint $table) {
    $table->string('key')->primary();
    $table->string('owner');
    $table->integer('expiration');
});

Schema::create('jobs', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('queue')->index();
    $table->longText('payload');
    $table->unsignedTinyInteger('attempts');
    $table->unsignedInteger('reserved_at')->nullable();
    $table->unsignedInteger('available_at');
    $table->unsignedInteger('created_at');
});

Schema::create('job_batches', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->string('name');
    $table->integer('total_jobs');
    $table->integer('pending_jobs');
    $table->integer('failed_jobs');
    $table->longText('failed_job_ids');
    $table->mediumText('options')->nullable();
    $table->integer('cancelled_at')->nullable();
    $table->integer('created_at');
    $table->integer('finished_at')->nullable();
});

Schema::create('failed_jobs', function (Blueprint $table) {
    $table->id();
    $table->string('uuid')->unique();
    $table->text('connection');
    $table->text('queue');
    $table->longText('payload');
    $table->longText('exception');
    $table->timestamp('failed_at')->useCurrent();
});
```

### Table: `activity_logs` (spatie/activitylog)

```php
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->string('log_name')->nullable();
    $table->text('description');
    $table->nullableMorphs('subject');
    $table->nullableMorphs('causer');
    $table->json('properties')->nullable();
    $table->uuid('batch_uuid')->nullable();
    $table->string('event')->nullable();
    $table->timestamps();
    $table->index('log_name');
});
```

### Table: `audit_logs` (admin actions)

```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete();
    $table->string('action');                    // product.approved, payout.approved
    $table->nullableMorphs('subject');
    $table->json('before')->nullable();
    $table->json('after')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamps();

    $table->index(['admin_id', 'created_at']);
    $table->index(['action', 'created_at']);
});
```

### Table: `media` (spatie/laravel-medialibrary)

```php
Schema::create('media', function (Blueprint $table) {
    $table->id();
    $table->morphs('model');
    $table->uuid('uuid')->nullable()->unique();
    $table->string('collection_name');
    $table->string('name');
    $table->string('file_name');
    $table->string('mime_type')->nullable();
    $table->string('disk');
    $table->string('conversions_disk')->nullable();
    $table->unsignedBigInteger('size');
    $table->json('manipulations');
    $table->json('custom_properties');
    $table->json('generated_conversions');
    $table->json('responsive_images');
    $table->unsignedInteger('order_column')->nullable();
    $table->timestamps();
    $table->index(['model_type', 'model_id']);
});
```

### Table: `search_logs`

```php
Schema::create('search_logs', function (Blueprint $table) {
    $table->id();
    $table->string('query');
    $table->unsignedInteger('results_count')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->timestamps();
    $table->index(['query', 'created_at']);
});
```

### Table: `downloadables` (digital products)

```php
Schema::create('downloadables', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->string('file_path');
    $table->unsignedBigInteger('size')->default(0);
    $table->unsignedInteger('download_limit')->nullable();
    $table->unsignedInteger('download_count')->default(0);
    $table->timestamps();
});
```

### Table: `file_downloads` (digital product access log)

```php
Schema::create('file_downloads', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('downloadable_id')->constrained()->cascadeOnDelete();
    $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
    $table->string('ip_address', 45)->nullable();
    $table->timestamps();
});
```

---

## 16. Eloquent Relationship Summary

| Model | Relationships |
|---|---|
| **User** | hasOne Vender, hasMany Address/Order/Review/Wishlist/Subscription, belongsToMany Role/Permission |
| **Admin** | hasMany AuditLog, acts as reviewer on VendorDocument/Order |
| **Vendor** | belongsTo User, hasMany Product/OrderItem/VendorDocument/LedgerEntry/PayoutRequest, hasManyThrough Order (via Product) |
| **Category** | belongsTo parent, hasMany children, belongsToMany Product |
| **Product** | belongsTo Vendor/Brand, belongsToMany Category/Tag, hasMany Variant/Image/StockMovement/Translation/Review, morphMany Media |
| **ProductVariant** | belongsTo Product, belongsToMany AttributeValue, hasMany StockMovement |
| **Attribute** | hasMany AttributeValue |
| **Cart** | belongsTo User (nullable), hasMany CartItem |
| **Order** | belongsTo User, hasMany OrderItem/StatusHistory/Payment/Refund/Shipment, hasOne ShippingAddress |
| **OrderItem** | belongsTo Order/Product/Variant/Vendor, hasOne Commission |
| **Plan** | hasMany PlanTier |
| **PlanTier** | belongsTo Plan, belongsToMany Feature (pivot with value), hasMany Subscription |
| **Subscription** | belongsTo User/PlanTier, hasMany SubscriptionItem/History/Invoice |
| **Review** | morphTo reviewable, hasMany Image/Reply |
| **Message** | belongsTo Conversation, morphTo sender |
| **VendorLedger** | belongsTo Vendor, morphTo reference |
| **PayoutRequest** | belongsTo Vendor, belongsTo Admin (processor) |
| **Notification** | morphTo notifiable (package) |
| **ActivityLog** | morphTo subject/causer (package) |
| **Media** | morphTo model (package) |

---

## 17. Complete Migration File Tree

Run order (dependency-safe). Use `php artisan make:model` flags to generate models simultaneously.

```
database/migrations/
├── 2026_01_01_000000_create_users_table.php
├── 2026_01_01_000001_create_password_reset_tokens_table.php
├── 2026_01_01_000002_create_sessions_table.php
├── 2026_01_01_000003_create_admins_table.php
├── 2026_01_01_000004_create_oauth_accounts_table.php
│── 2026_01_01_000010_create_permission_tables.php          (spatie, published)
├── 2026_01_01_000011_create_personal_access_tokens_table.php (sanctum)
├── 2026_01_01_000012_create_oauth_auth_codes_table.php      (passport)
├── 2026_01_01_000013_create_oauth_access_tokens_table.php
├── 2026_01_01_000014_create_oauth_refresh_tokens_table.php
├── 2026_01_01_000015_create_oauth_clients_table.php
├── 2026_01_01_000016_create_oauth_personal_access_clients_table.php
├── 2026_01_01_000020_create_countries_states_cities_tables.php
├── 2026_01_01_000021_create_vendors_table.php
├── 2026_01_01_000022_create_vendor_documents_table.php
├── 2026_01_01_000023_create_vendor_payment_methods_table.php
├── 2026_01_01_000024_create_vendor_settings_table.php
├── 2026_01_01_000030_create_categories_table.php
├── 2026_01_01_000031_create_brands_table.php
├── 2026_01_01_000032_create_products_table.php
├── 2026_01_01_000033_create_category_product_table.php
├── 2026_01_01_000034_create_attributes_table.php
├── 2026_01_01_000035_create_attribute_values_table.php
├── 2026_01_01_000036_create_product_variants_table.php
├── 2026_01_01_000037_create_variant_attribute_value_table.php
├── 2026_01_01_000038_create_product_images_table.php
├── 2026_01_01_000039_create_product_translations_table.php
├── 2026_01_01_000040_create_tags_and_product_tag_tables.php
├── 2026_01_01_000041_create_stock_movements_table.php
├── 2026_01_01_000042_create_downloadables_and_file_downloads_tables.php
├── 2026_01_01_000050_create_addresses_table.php
├── 2026_01_01_000051_create_carts_table.php
├── 2026_01_01_000052_create_cart_items_table.php
├── 2026_01_01_000053_create_wishlists_table.php
├── 2026_01_01_000060_create_shipping_methods_zones_tables.php
├── 2026_01_01_000061_create_tax_rates_table.php
├── 2026_01_01_000062_create_coupons_and_coupon_user_tables.php
├── 2026_01_01_000070_create_orders_table.php
├── 2026_01_01_000071_create_order_items_table.php
├── 2026_01_01_000072_create_order_status_histories_table.php
├── 2026_01_01_000073_create_shipments_table.php
├── 2026_01_01_000074_create_payments_table.php
├── 2026_01_01_000075_create_refunds_and_returns_tables.php
├── 2026_01_01_000080_create_plans_table.php
├── 2026_01_01_000081_create_plan_tiers_table.php
├── 2026_01_01_000082_create_features_and_plan_tier_feature_tables.php
├── 2026_01_01_000083_create_subscriptions_table.php
├── 2026_01_01_000084_create_subscription_items_table.php
├── 2026_01_01_000085_create_subscription_histories_table.php
├── 2026_01_01_000086_create_invoices_table.php
├── 2026_01_01_000087_create_webhook_calls_table.php
├── 2026_01_01_000088_create_usage_records_table.php
├── 2026_01_01_000090_create_commissions_table.php
├── 2026_01_01_000091_create_vendor_ledgers_table.php
├── 2026_01_01_000092_create_payout_requests_table.php
├── 2026_01_01_000093_create_wallets_and_wallet_transactions_tables.php
├── 2026_01_01_000100_create_reviews_and_review_images_tables.php
├── 2026_01_01_000101_create_review_replies_table.php
├── 2026_01_01_000110_create_conversations_and_messages_tables.php
├── 2026_01_01_000111_create_message_attachments_table.php
├── 2026_01_01_000112_create_support_tickets_and_replies_tables.php
├── 2026_01_01_000120_create_notifications_table.php
├── 2026_01_01_000121_create_notification_preferences_table.php
├── 2026_01_01_000122_create_announcements_table.php
├── 2026_01_01_000130_create_pages_table.php
├── 2026_01_01_000131_create_banners_table.php
├── 2026_01_01_000132_create_blog_posts_table.php
├── 2026_01_01_000133_create_newsletters_table.php
├── 2026_01_01_000140_create_settings_table.php
├── 2026_01_01_000141_create_currency_rates_table.php
├── 2026_01_01_000150_create_cache_and_jobs_tables.php           (framework)
├── 2026_01_01_000151_create_activity_logs_table.php            (spatie)
├── 2026_01_01_000152_create_audit_logs_table.php
├── 2026_01_01_000153_create_media_table.php                    (spatie)
└── 2026_01_01_000154_create_search_logs_table.php
```

### Install command template

```bash
# Project scaffold
laravel new marketplace
cd marketplace

# Packages
composer require spatie/laravel-permission spatie/laravel-medialibrary \
  spatie/laravel-query-builder laravel/cashier laravel/sanctum laravel/passport \
  laravel/horizon laravel/reverb laravel/pulse pestphp/pest barryvdh/laravel-dompdf \
  spatie/laravel-activitylog

# Publish & install
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan sanctum:install
php artisan passport:install
php artisan horizon:install
php artisan reverb:install
php artisan pulse:install
php artisan install:api
```
