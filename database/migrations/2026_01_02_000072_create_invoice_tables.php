<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        Schema::create('webhook_calls', function (Blueprint $table) {
            $table->id();
            $table->string('type');
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
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_calls');
        Schema::dropIfExists('invoices');
    }
};
