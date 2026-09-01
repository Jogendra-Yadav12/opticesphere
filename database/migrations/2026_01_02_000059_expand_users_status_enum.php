<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('active', 'pending', 'approved', 'rejected', 'banned', 'suspended'))");
        } else {
            Schema::table('users', function ($table) {
                $table->enum('status', ['active', 'pending', 'approved', 'rejected', 'banned', 'suspended'])
                    ->default('active')
                    ->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            $statuses = ['active', 'banned', 'suspended'];
            DB::statement('UPDATE users SET status = CASE WHEN status = \'pending\' OR status = \'approved\' THEN \'active\' WHEN status = \'rejected\' THEN \'suspended\' ELSE status END');
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_status_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_status_check CHECK (status IN ('" . implode("', '", $statuses) . "'))");
        } else {
            Schema::table('users', function ($table) {
                $table->enum('status', ['active', 'banned', 'suspended'])
                    ->default('active')
                    ->change();
            });
        }
    }
};
