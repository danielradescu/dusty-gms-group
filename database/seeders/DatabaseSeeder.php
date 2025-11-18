<?php

namespace Database\Seeders;

use App\Models\FeaturedMember;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $env = app()->environment();


        if ($env == 'local') {
            User::factory()->count(100)->create();
//            GameSession::factory()->count(100)->create();
            FeaturedMember::factory()->count(5)->create();
        }

    }

}
