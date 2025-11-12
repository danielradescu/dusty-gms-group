<?php

namespace Database\Factories;

use App\Models\FeaturedMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeaturedMember>
 */
class FeaturedMemberFactory extends Factory
{
    protected $model = FeaturedMember::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'description' => $this->faker->sentence(12),
            'bgg_profile_url' => $this->faker->optional(0.7)->url(), // ~70% will have a profile link
        ];
    }
}
