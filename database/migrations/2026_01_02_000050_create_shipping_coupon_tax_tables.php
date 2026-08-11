<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('base_cost', 15, 2)->default(0);
            $table->decimal('cost_per_kg', 15, 2)->default(0);
            $table->unsignedInteger('estimated_days_min')->default(3);
            $table->unsignedInteger('estimated_days_max')->default(7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('countries');
            $table->timestamps();
        });

        Schema::create('shipping_method_zone', function (Blueprint $table) {
            $table->foreignId('shipping_method_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();
            $table->decimal('cost', 15, 2)->default(0);

            $table->primary(['shipping_method_id', 'shipping_zone_id']);
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->decimal('rate', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

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

        Schema::create('coupon_user', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('usage_count')->default(0);
            $table->timestamps();

            $table->primary(['coupon_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_user');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('shipping_method_zone');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_methods');
    }
};
