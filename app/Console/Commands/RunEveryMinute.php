<?php

namespace App\Console\Commands;

use _PHPStan_781aefaf6\Nette\Neon\Exception;
use App\Enums\NotificationStatus;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use App\Services\Notifications\NotificationHandlerFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RunEveryMinute extends Command
{
    protected $signature = 'run:every-minute';
    protected $description = 'Processes due scheduled notifications every minute.';

    public function handle(): int
    {
        $originalLogger = Log::getLogger();
        Log::swap(Log::channel('notifications'));
        try {


            $start = microtime(true);
            $now = now();

            $processedCount = 0;

            // Log heartbeat
            Log::info("[Scheduler] RunEveryMinute executed at {$now}");

            $query = Notification::whereIn('status', [
                NotificationStatus::SCHEDULED,
                NotificationStatus::RETRY
            ]);

            if (!app()->environment('local')) {
                $query->where('send_at', '<=', $now);
            }
            $query->orderBy('send_at')
                ->chunkById(100, function ($notifications) use (&$processedCount) {
                    foreach ($notifications as $notification) {
                        try {
                            $handler = (new NotificationHandlerFactory())->make($notification->type);
                            if ($handler) {
                                $handler->process($notification);
                            } else {
                                throw new \Exception("Notification #{$notification->id} has no handler to resolve it.");
                            }
                        } catch (\Throwable $e) {
                            Log::error("Notification #{$notification->id} failed: " . $e->getMessage());
                            $notification->update([
                                'status' => NotificationStatus::FAILED,
                                'error' => $e->getMessage(),
                            ]);
                        }
                        $processedCount++;
                    }
                });

            $duration = round(microtime(true) - $start, 2);
            Log::info("[Scheduler] Dispatched {$processedCount} notifications in {$duration}s.");

            return self::SUCCESS;


        } finally {
            Log::swap($originalLogger); // restore the default logger
        }

    }
}
