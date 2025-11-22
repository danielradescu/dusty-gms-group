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

        Schema::create('in_app_notifications', function (Blueprint $table) {
            $table->id();

            // Recipient
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Enum - NotificationType (like SESSION_CONFIRMED, etc.)
            $table->unsignedTinyInteger('type')
                ->comment('See App\Enums\NotificationType');

            // Content
            $table->string('title');
            $table->text('message')->nullable();

            // Read status
            $table->timestamp('read_at')->nullable();

            // Source tracking — optional
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Useful indexes
            $table->index(['user_id', 'read_at']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_app_notifications');
    }
};
