<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('downloadables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedInteger('download_limit')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
        });

        Schema::create('file_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('downloadable_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_downloads');
        Schema::dropIfExists('downloadables');
    }
};
