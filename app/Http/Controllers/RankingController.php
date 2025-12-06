<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        $honorificTitles = [
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


        $toReturn = [
            'users' => User::where('role', Role::Participant)->orderBy('xp', 'desc')->get(),
            'honorificTitles' => $honorificTitles,
        ];


        return view('ranking.index')->with($toReturn);
    }
}
