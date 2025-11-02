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

            // Link directly to registration
            $table->foreignId('registration_id')
                ->constrained()
                ->onDelete('cascade');

            // Type of notification
            $table->tinyInteger('type')->unsigned();

            // Whether the notification was sent
            $table->boolean('sent')->default(false);

            // Optional custom message (for updated sessions)
            $table->longText('custom_message')->nullable();

            // Prevent duplicate notifications of the same type for one registration
            $table->unique(['registration_id', 'type']);

            $table->timestamps();
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
