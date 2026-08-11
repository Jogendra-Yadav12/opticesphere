<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('notifiable');
            $table->string('event');
            $table->enum('channel', ['mail', 'database', 'broadcast', 'sms'])->default('mail');
            $table->boolean('enabled')->default(true);
            $table->timestamps();

            $table->unique(['notifiable_type', 'notifiable_id', 'event', 'channel'], 'notif_prefs_unique');
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->enum('audience', ['all', 'customers', 'vendors', 'admins'])->default('all');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
};
