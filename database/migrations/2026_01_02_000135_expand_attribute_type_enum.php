<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->enum('type', ['text', 'select', 'radio', 'checkbox', 'color', 'button'])
                ->default('select')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->enum('type', ['text', 'select', 'color', 'button'])
                ->default('select')
                ->change();
        });
    }
};
