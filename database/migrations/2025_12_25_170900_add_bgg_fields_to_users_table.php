<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bgg_username')->nullable()->unique()->after('email');
            $table->timestamp('last_bgg_sync_at')->nullable()->after('bgg_username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bgg_username', 'last_bgg_sync_at']);
        });
    }
};
