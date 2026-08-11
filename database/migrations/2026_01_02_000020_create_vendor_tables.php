<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        Schema::create('vendor_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['bank', 'paypal', 'stripe']);
            $table->json('details');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('vendor_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['vendor_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_settings');
        Schema::dropIfExists('vendor_payment_methods');
        Schema::dropIfExists('vendor_documents');
        Schema::dropIfExists('vendors');
    }
};
