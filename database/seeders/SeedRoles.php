<?php

namespace Database\Seeders;

use App\Enums\Role as EnumRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SeedRoles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::findOrCreate(EnumRole::ADMIN->value);
        Role::findOrCreate(EnumRole::ORGANIZER->value);
        Role::findOrCreate(EnumRole::PARTICIPANT->value);
    }
}
