<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        Schema::create('vendor_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['sale_credit', 'commission', 'payout', 'refund', 'adjustment']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->string('description')->nullable();
            $table->nullableMorphs('reference');
            $table->timestamps();

            $table->index(['vendor_id', 'created_at']);
            $table->index(['vendor_id', 'type']);
        });

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
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('payout_requests');
        Schema::dropIfExists('vendor_ledgers');
        Schema::dropIfExists('commissions');
    }
};
