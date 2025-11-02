<?php

namespace Database\Seeders;

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

        $admin = Role::findOrCreate(EnumRole::ADMIN->value);
        $organizer = Role::findOrCreate(EnumRole::ORGANIZER->value);
        $participant = Role::findOrCreate(EnumRole::PARTICIPANT->value);

        Permission::findOrCreate('create event');

        $admin->givePermissionTo(Permission::all());
        $organizer->givePermissionTo(['create event']);

        if ($env == 'local') {
            User::factory()->count(100)->create();
            GameSession::factory()->count(100)->create();
        }

    }

}
