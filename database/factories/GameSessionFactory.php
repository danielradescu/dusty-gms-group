<?php

namespace Database\Factories;

use App\Enums\GameSessionType;
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
        $randomDate = $this->faker->dateTimeBetween('-1 year', '+1 year');

        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'min_players' => $this->faker->numberBetween(2, 4),
            'max_players' => $this->faker->numberBetween(4, 12),
            'complexity' => $this->faker->randomFloat(2, 0, 5),
            'type' => collect(GameSessionType::cases())->random(),
            'start_at' => $randomDate,
            'organized_by' => User::role(['Organizer', 'Admin'])->inRandomOrder()->first()->id,
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
            if (in_array($gameSession->type, [GameSessionType::FAILED, GameSessionType::SUCCEEDED, GameSessionType::CANCELLED])) {
                $gameSession->note = $this->faker->text();
                $gameSession->save();
            }
        });
    }
}
