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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('game_session_id')->constrained()->onDelete('cascade');

            // Enum stored as tiny integer
            $table->tinyInteger('status')->unsigned()->default(0);

            // Prevent duplicate registration per user-session pair
            $table->unique(['user_id', 'game_session_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
