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
        Schema::table('game_session_requests', function (Blueprint $table) {
            $table->date('preferred_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_session_requests', function (Blueprint $table) {
            $table->dateTime('preferred_time')->nullable()->change();
        });
    }
};
