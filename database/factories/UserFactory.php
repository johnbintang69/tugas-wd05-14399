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
            'nama' => fake()->name(),
            'alamat' => fake()->address(),
            'no_hp' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['dokter', 'pasien']),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Set the user role as doctor.
     */
    public function dokter(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'dokter',
        ]);
    }

    /**
     * Set the user role as patient.
     */
    public function pasien(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pasien',
        ]);
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
}