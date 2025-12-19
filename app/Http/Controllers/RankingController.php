<?php

namespace App\Http\Controllers;

use App\Enums\GameSessionStatus;
use App\Enums\Role;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index()
    {
        $participantTitles = [
            1 => '👑💀 The Final Boss',
            2 => '🧙 Grand Game Master',
            3 => '🏰 Legendary Strategist',
            4 => '🎯 Master Tactician',
            5 => '⚔️ Veteran Competitor',
            6 => '🧠 Brainstorm Baron',
            7 => '🎲 Dice Whisperer',
            8 => '🏹 Board Knight',
            9 => '🧃 Casual Champion',
            10 => '🌟 Rising Star',
        ];

        $organizerTitles = [
            1 => '👑💀 Master of Meeples',
            2 => '🏗️ Session Architect',
            3 => '🪑 Table Host',
        ];

        $organizers = GameSession::select('organized_by', DB::raw('COUNT(*) as sessions_count'))
            ->where('status', GameSessionStatus::SUCCEEDED)
            ->groupBy('organized_by')
            ->orderByDesc('sessions_count')
            ->with('organizer')
            ->get()
            ->map(function ($session) {
                $session->organizer->sessions_count = $session->sessions_count;
                return $session->organizer;
            });

        $toReturn = [
            'users' => User::where('role', Role::Participant)->orderBy('xp', 'desc')->orderBy('created_at', 'asc')->get(),
            'organizers' => $organizers,
            'participantTitles' => $participantTitles,
            'organizerTitles' => $organizerTitles,
        ];


        return view('ranking.index')->with($toReturn);
    }
}
