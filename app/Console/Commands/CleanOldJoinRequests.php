<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JoinRequest;
use App\Enums\JoinRequestStatus;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanOldJoinRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Run with:
     * php artisan app:clean-old-join-requests
     */
    protected $signature = 'app:clean-old-join-requests';

    /**
     * The console command description.
     */
    protected $description = '🧹 Delete JoinRequest records older than 7 days that are not pending.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(7);

        $this->info("Cleaning JoinRequest records older than {$cutoff->toDateTimeString()} (excluding pending)...");

        // Query: older than 7 days AND not pending
        $count = JoinRequest::where('created_at', '<', $cutoff)
            ->where('status', '!=', JoinRequestStatus::PENDING)
            ->delete();

        if ($count > 0) {
            $this->info("✅ Deleted {$count} old join requests.");
            Log::info("CleanOldJoinRequests: deleted {$count} old join requests older than 7 days (non-pending).");
        } else {
            $this->info("No old non-pending join requests found.");
        }

        return Command::SUCCESS;
    }
}
