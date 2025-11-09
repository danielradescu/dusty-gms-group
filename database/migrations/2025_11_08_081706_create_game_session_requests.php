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
        Schema::create('game_session_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('preferred_time')->nullable();
            $table->boolean('auto_join')->default(false);
            $table->boolean('notified')->default(false); // if a notification was already sent

            $table->timestamp('fulfilled_at')->nullable(); // when a session was created and matched or wghen notification was sent

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_session_requests');
    }
};
