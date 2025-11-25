<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('community_join_requests', 'join_requests');
    }

    public function down(): void
    {
        Schema::rename('join_requests', 'community_join_requests');
    }
};
