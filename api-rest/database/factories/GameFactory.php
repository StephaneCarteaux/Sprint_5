<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    //protected $model = Game::class;

    public function definition(): array
    {
        //$user = User::role('player')->inRandomOrder()->first();

        return [
            'dice_1' => $this->faker->numberBetween(1, 6),
            'dice_2' => $this->faker->numberBetween(1, 6),
            'user_id' => User::factory(),
        ];
    }
}
