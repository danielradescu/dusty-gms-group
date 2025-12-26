<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Bgg\BggSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncBggCollections extends Command
{
    protected $signature = 'bgg:weekly-sync';
    protected $description = 'Synchronize all users’ BGG collections once per week safely.';

    public function handle(BggSyncService $sync): int
    {
        $originalLogger = Log::getLogger();
        Log::swap(Log::channel('bggsync'));
        try {

            $users = User::whereNotNull('bgg_username')->get();

            foreach ($users as $user) {
                Log::info("[BGG Sync] Starting for {$user->bgg_username}");

                try {
                    $sync->syncUserCollection($user);
                    Log::info("[BGG Sync] Finished for {$user->bgg_username}");
                } catch (\Throwable $e) {
                    Log::error("[BGG Sync] Failed for {$user->bgg_username}: {$e->getMessage()}");
                }

                // small pause between users so we don’t hammer BGG
                sleep(60);
            }

            Log::info('[BGG Sync] Weekly sync completed.');
            return self::SUCCESS;

        } finally {
            Log::swap($originalLogger); // restore the default logger
        }
    }
}
