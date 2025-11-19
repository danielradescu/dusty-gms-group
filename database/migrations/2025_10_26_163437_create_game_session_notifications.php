<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            // JSON payload: may contain session_id, target_date, extra metadata
            $table->json('data')->nullable();

            // tinyint enum (we’ll build PHP enums next)
            $table->unsignedTinyInteger('type');   // NotificationType enum
            $table->unsignedTinyInteger('status'); // NotificationStatus enum

            // When the notification should be processed
            $table->dateTime('send_at')->index();

            // Track retries
            $table->unsignedTinyInteger('attempts')->default(0);

            // Hash for uniqueness (per user/type/session/date)
            $table->string('hash', 64)->unique();

            $table->timestamps();

            // optional but recommended
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            // composite index for worker efficiency
            $table->index(['status', 'send_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
