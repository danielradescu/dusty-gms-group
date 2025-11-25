<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Enums\JoinRequestStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Define map for existing string statuses
        $map = [
            'pending' => JoinRequestStatus::PENDING->value,
            'rejected' => JoinRequestStatus::REJECTED->value,
            'approved' => JoinRequestStatus::APPROVED->value,
        ];

        // Step 2: Add temporary integer column
        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->tinyInteger('new_status')
                ->nullable()
                ->after('status')
                ->comment('Temporary integer-backed status column');
        });

        // Step 3: Migrate data
        DB::table('community_join_requests')->get()->each(function ($record) use ($map) {
            $mappedValue = $map[strtolower($record->status)] ?? JoinRequestStatus::PENDING->value;

            DB::table('community_join_requests')
                ->where('id', $record->id)
                ->update(['new_status' => $mappedValue]);
        });

        // Step 4: Replace old column
        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->renameColumn('new_status', 'status');
        });

        // Step 5: Add note column
        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->longText('note')->nullable()->after('message');
        });

        // Step 6: Set constraints and default
        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->tinyInteger('status')
                ->default(JoinRequestStatus::PENDING->value)
                ->comment('0=pending,2=rejected,3=approved')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop note column
        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->dropColumn('note');
        });

        // Convert integers back to strings
        $reverseMap = [
            JoinRequestStatus::PENDING->value => 'pending',
            JoinRequestStatus::REJECTED->value => 'rejected',
            JoinRequestStatus::APPROVED->value => 'approved',
        ];

        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->string('old_status')->nullable()->after('status');
        });

        DB::table('community_join_requests')->get()->each(function ($record) use ($reverseMap) {
            $mappedValue = $reverseMap[$record->status] ?? 'pending';

            DB::table('community_join_requests')
                ->where('id', $record->id)
                ->update(['old_status' => $mappedValue]);
        });

        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('community_join_requests', function (Blueprint $table) {
            $table->renameColumn('old_status', 'status');
            $table->string('status')->default('pending')->change();
        });
    }
};
