<?php

namespace Database\Seeders;

use App\Models\GameSession;
use App\Models\GameSessionType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $env = app()->environment();

        $roles = \App\Enums\Role::cases();
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        if ($env == 'local') {
            User::factory()->count(100)->create();
            GameSession::factory()->count(100)->create();
        }

    }

}
