<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nickname' => fake()->boolean(50) ? fake()->userName() : 'Anonim',
            'registered_at' => now(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password123'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have the default nickname "Anonim".
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // public function anonim()
    // {
    //     return $this->state(function (array $attributes) {
    //         return [
    //             'nickname' => 'Anonim',
    //         ];
    //     });
    // }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
                'nickname' => 'admin',
                'registered_at' => now(),
                'email' => 'admin@example.com',
                'password' => static::$password ??= Hash::make('password123'),

            ];
        });
    }

    public function testUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Test User',
                'nickname' => 'test',
                'registered_at' => now(),
                'email' => 'test@example.com',
                'password' => static::$password ??= Hash::make('password123'),

            ];
        });
    }
}
