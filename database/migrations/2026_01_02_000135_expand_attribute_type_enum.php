<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE attributes DROP CONSTRAINT IF EXISTS attributes_type_check');
            DB::statement("ALTER TABLE attributes ADD CONSTRAINT attributes_type_check CHECK (type IN ('text', 'select', 'radio', 'checkbox', 'color', 'button'))");
        } else {
            Schema::table('attributes', function ($table) {
                $table->enum('type', ['text', 'select', 'radio', 'checkbox', 'color', 'button'])
                    ->default('select')
                    ->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE attributes DROP CONSTRAINT IF EXISTS attributes_type_check');
            DB::statement("ALTER TABLE attributes ADD CONSTRAINT attributes_type_check CHECK (type IN ('text', 'select', 'color', 'button'))");
        } else {
            Schema::table('attributes', function ($table) {
                $table->enum('type', ['text', 'select', 'color', 'button'])
                    ->default('select')
                    ->change();
            });
        }
    }
};
