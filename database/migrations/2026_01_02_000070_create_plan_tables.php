<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['subscription', 'one_time_digital'])->default('subscription');
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->timestamps();
        });

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

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('plan_tier_feature', function (Blueprint $table) {
            $table->foreignId('plan_tier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->boolean('is_included')->default(true);

            $table->primary(['plan_tier_id', 'feature_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_tier_feature');
        Schema::dropIfExists('features');
        Schema::dropIfExists('plan_tiers');
        Schema::dropIfExists('plans');
    }
};
