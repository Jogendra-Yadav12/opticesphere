<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_tier_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['active', 'trialing', 'past_due', 'cancelled', 'expired', 'paused'])->default('trialing');
            $table->dateTime('current_period_start');
            $table->dateTime('current_period_end');
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

        Schema::create('subscription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_tier_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();

            $table->unique(['subscription_id', 'plan_tier_id']);
        });

        Schema::create('subscription_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('event');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'created_at']);
        });

        Schema::create('usage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('metric');
            $table->unsignedBigInteger('quantity')->default(0);
            $table->date('recorded_on');
            $table->timestamps();

            $table->unique(['subscription_id', 'metric', 'recorded_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_records');
        Schema::dropIfExists('subscription_histories');
        Schema::dropIfExists('subscription_items');
        Schema::dropIfExists('subscriptions');
    }
};
