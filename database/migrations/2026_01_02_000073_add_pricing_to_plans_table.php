<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0)->after('type');
            $table->unsignedInteger('duration_days')->default(30)->after('price');
            $table->unsignedInteger('product_limit')->default(0)->after('duration_days');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['price', 'duration_days', 'product_limit']);
        });
    }
};
