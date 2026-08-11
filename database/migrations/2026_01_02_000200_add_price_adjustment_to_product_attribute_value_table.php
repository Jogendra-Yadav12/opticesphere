<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_attribute_value', function (Blueprint $table) {
            $table->decimal('price_adjustment', 15, 2)->nullable()->after('attribute_value_id');
        });
    }

    public function down(): void
    {
        Schema::table('product_attribute_value', function (Blueprint $table) {
            $table->dropColumn('price_adjustment');
        });
    }
};
