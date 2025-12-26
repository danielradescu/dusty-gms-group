<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Safely clear all Laravel log files in storage/logs.';

    public function handle(): int
    {
        $logPath = storage_path('logs');

        if (!File::exists($logPath)) {
            $this->warn("No logs directory found at: {$logPath}");
            return self::SUCCESS;
        }

        $files = File::files($logPath);
        $cleared = 0;

        foreach ($files as $file) {
            try {
                file_put_contents($file->getRealPath(), '');
                $this->info("✅ Cleared {$file->getFilename()}");
                $cleared++;
            } catch (\Throwable $e) {
                $this->error("❌ Failed to clear {$file->getFilename()}: {$e->getMessage()}");
            }
        }

        Log::info("[System] Manual log cleanup completed — {$cleared} files cleared.");
        $this->info("🎉 {$cleared} log files cleared successfully!");

        return self::SUCCESS;
    }
}
