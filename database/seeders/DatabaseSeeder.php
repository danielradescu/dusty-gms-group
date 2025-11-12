<?php

namespace Database\Seeders;

use App\Models\FeaturedMember;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\Role as EnumRole;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $env = app()->environment();

        Role::findOrCreate(EnumRole::ADMIN->value);
        Role::findOrCreate(EnumRole::ORGANIZER->value);
        Role::findOrCreate(EnumRole::PARTICIPANT->value);

        Permission::findOrCreate('create event');

        if ($env == 'local') {
            User::factory()->count(100)->create();
            GameSession::factory()->count(100)->create();
            FeaturedMember::factory()->count(5)->create();
        }

    }

}
