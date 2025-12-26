<?php

namespace App\Services\Bgg;

use App\Models\User;
use App\Models\Boardgame;
use App\Services\XP;
use Illuminate\Support\Facades\DB;

class BggSyncService
{
    public function __construct(
        protected BggCollectionService $collectionService,
        protected BggThingService $thingService,
    ) {}

    public function syncUserCollection(User $user): void
    {
        if (!$user->canSyncBgg()) {
            throw new \Exception("User cannot sync more than once per day.");
        }

        $collection = $this->collectionService->getUserCollection($user->bgg_username);
        if (empty($collection)) {
            return;
        }

        $bggIds = array_column($collection, 'bgg_id');

        DB::transaction(function () use ($user, $collection, $bggIds) {

            $existingIds = Boardgame::pluck('bgg_id')->all();

            Boardgame::upsert(
                Boardgame::sanitizeForUpsert($collection),
                ['bgg_id'],
                [
                    'name',
                    'year_published',
                    'min_players',
                    'max_players',
                    'thumbnail',
                    'image',
                    'rank_boardgame',
                ]
            );

            $existingIds = array_map('intval', $existingIds);
            $bggIds = array_map('intval', $bggIds);
            $newlyInserted = array_diff($bggIds, $existingIds);

            if (!empty($newlyInserted)) {
                $details = $this->thingService->getDetails($newlyInserted);

                if (!empty($details)) {
                    foreach ($details as $id => $data) {
                        Boardgame::where('bgg_id', $id)->update($data);
                    }
                } else {
                    Log::warning("BGG Thing API returned empty response for IDs: " . implode(',', $newlyInserted));
                }
            }

            // Reset pivot links
            $user->boardgames()->detach();

            // Reattach
            $gameIds = Boardgame::whereIn('bgg_id', $bggIds)->pluck('id')->toArray();
            $user->boardgames()->attach($gameIds);

            // Update sync time
            $user->update(['last_bgg_sync_at' => now()]);

            //reward for having profile linked:
            XP::grantOncePerWeek($user, 'link_bgg_profile_weekly');

        });
    }
}
