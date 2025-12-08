<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResetPlayableDaysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan app:reset-playable-days
     */
    protected $signature = 'app:reset-playable-days';

    /**
     * The console command description.
     */
    protected $description = 'Reset today\'s day as not playable and clear playable days cache.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = strtolower(Carbon::now()->format('l'));

        $this->info("Checking if '$today' should be reset...");

        // Skip Saturday and Sunday
        if (in_array($today, ['saturday', 'sunday'])) {
            $this->info("Skipping reset for '$today' — it's an immutable playable day.");
            return Command::SUCCESS;
        }

        $this->info("Resetting '$today' to not playable...");

        DB::table('days_we_play')
            ->where('day_of_week', $today)
            ->update([
                'playable'   => false,
                'changed_by' => '-system-',
                'updated_at' => now(),
            ]);

        // 🧹 Clear the cache
        Cache::forget('current_playable_days');

        $this->info('Current playable days cache cleared.');
        $this->info('Reset complete.');

        return Command::SUCCESS;
    }

}
