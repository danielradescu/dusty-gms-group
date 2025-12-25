<?php

namespace App\Http\Controllers;

use App\Models\Boardgame;
use App\Services\Bgg\BggSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BoardgameController extends Controller
{
    public function __construct(
        protected BggSyncService $bggSyncService
    ) {}

    /**
     * Display the community collection with optional filters.
     */
    public function index(Request $request)
    {
        $query = Boardgame::with('users')
            ->whereHas('users'); // only games owned by at least one user

        // Filter: Search by name
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter: Number of players (min ≤ X ≤ max)
        if ($players = $request->input('players')) {
            $query->where(function ($q) use ($players) {
                $q->where('min_players', '<=', $players)
                    ->where('max_players', '>=', $players);
            });
        }

        // Filter: Games owned by a specific user
        if ($bggUsername = $request->input('user')) {
            $query->whereHas('users', function ($q) use ($bggUsername) {
                $q->where('users.bgg_username', $bggUsername);
            });
        }

        //  Filter: Include expansions or not
        $includeExpansions = $request->boolean('include_expansions', false);

        if (!$includeExpansions) {
            // By default exclude expansions
            $query->where('is_expansion', false);
        }

        //  Ordering: expansions last, then rank ascending
        $query->orderBy('is_expansion') // false (0) before true (1)
                ->orderedByRank();

        $boardgames = $query->paginate(18)->appends($request->query());

        // Auth user and dropdown users
        $user = auth()->user();

        $usersWithGames = \App\Models\User::whereNotNull('bgg_username')
            ->whereHas('boardgames')
            ->orderBy('name')
            ->get(['name', 'bgg_username']);

        return view('boardgames.index', compact('boardgames', 'user', 'usersWithGames', 'includeExpansions'));
    }


    /**
     * Save BGG username for authenticated user and trigger collection sync.
     */
    public function saveBggProfile(Request $request)
    {


        $request->validate([
            'bgg_username' => ['nullable', 'string', 'max:50'],
        ]);

        $user = auth()->user();



        $bggUsername = $request->input('bgg_username');

        // Save the username first
        $user->bgg_username = $bggUsername;
        $user->save();

        if (!$user->canSyncBgg()) {
            return redirect()
                ->route('boardgames.index')
                ->with('success', 'Your BGG profile was saved, but we could not sync your collection more than once per day. Please try tomorrow or wait for the weekly synchronisation of you BGG profile.');
        }

        // If a username is provided, trigger sync
        if ($bggUsername) {
            try {
                $this->bggSyncService->syncUserCollection($user);
                $message = 'Your BoardGameGeek collection has been successfully synchronized!';
            } catch (\Throwable $e) {
                Log::error("BGG Sync failed for {$bggUsername}: {$e->getMessage()}");
                $message = 'Your BGG profile was saved, but we could not sync your collection. Please check profile name try again later.';
            }
        } else {
            $message = 'Your BoardGameGeek profile has been successfully updated!';
        }

        return redirect()
            ->route('boardgames.index')
            ->with('success', $message);
    }
}
