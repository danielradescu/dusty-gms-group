<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MagicLink;
use Illuminate\Support\Facades\Log;

class CleanupMagicLinks extends Command
{
    protected $signature = 'magic-links:cleanup';
    protected $description = 'Delete expired magic login links';

    public function handle()
    {
        $count = MagicLink::where('expires_at', '<', now())->delete();
        Log::info("Deleted {$count} expired links.");
    }
}
