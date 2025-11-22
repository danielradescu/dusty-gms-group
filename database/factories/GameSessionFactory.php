<?php

namespace Database\Factories;

use App\Enums\GameSessionStatus;
use App\Enums\Role;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a random datetime between -1 year and +1 year
        $randomDate = $this->faker->dateTimeBetween('-1 week', '+1 week');

        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'min_players' => $this->faker->numberBetween(2, 4),
            'max_players' => $this->faker->numberBetween(4, 12),
            'complexity' => $this->faker->randomFloat(2, 0, 5),
            'type' => collect(GameSessionStatus::cases())->random(),
            'start_at' => $randomDate,
            'organized_by' => User::role([Role::ORGANIZER->value, Role::ADMIN->value])->inRandomOrder()->first()->id,
            'location' => $this->faker->company(),

            // Randomized timestamps
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (GameSession $gameSession) {
            // Assign random note in certain cases
            if (in_array($gameSession->status, [GameSessionStatus::FAILED, GameSessionStatus::SUCCEEDED, GameSessionStatus::CANCELLED])) {
                $gameSession->note = $this->faker->text();
                $gameSession->save();
            }
            // Add between 3 and 5 comments using the factory
            \App\Models\Comment::factory()
                ->count(rand(3, 5))
                ->create([
                    'game_session_id' => $gameSession->id,
                ]);


        });
    }
}
