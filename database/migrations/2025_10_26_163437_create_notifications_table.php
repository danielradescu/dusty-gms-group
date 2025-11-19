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

            // Who receives it
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Deduplication key
            $table->string('hash', 64)->unique();

            // ENUM: NotificationType (casted in model)
            $table->unsignedTinyInteger('type');

            // JSON payload
            $table->json('data')->nullable();

            // Scheduling
            $table->timestamp('send_at')->index();

            // ENUM: NotificationStatus (casted in model)
            $table->unsignedTinyInteger('status')->default(0);

            // Retry count
            $table->unsignedTinyInteger('attempts')->default(0);

            $table->string('message')->nullable();   // short explanation
            $table->text('error')->nullable();       // detailed error

            $table->timestamps();

            // Useful indexes
            $table->index(['user_id', 'type']);
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
