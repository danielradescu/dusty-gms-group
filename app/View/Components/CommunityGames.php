<?php

namespace App\View\Components;

use App\Models\Boardgame;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class CommunityGames extends Component
{
    public function render()
    {
        $games = Cache::remember('community_games_home', now()->addHours(6), function () {
            // Step 1: Get the top 45 ranked base games owned by users
            $topGames = Boardgame::baseGames()
                ->whereHas('users')
                ->where('is_expansion', false)
                ->orderedByRank()
                ->take(45)
                ->get(['id', 'name', 'bgg_id', 'thumbnail']);

            // Step 2: Shuffle and pick 15 random unique ones
            return $topGames->shuffle()->take(15)->values();
        });

        return view('components.community-games', [
            'games' => $games,
        ]);
    }
}
