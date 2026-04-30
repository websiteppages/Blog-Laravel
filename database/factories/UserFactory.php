<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            // Schema: users(name, email, password, bio, avatar)
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), // Default password
            'bio'               => fake()->optional(0.7)->paragraph(),
            'avatar'            => null, // Storage file இல்லை → null
            'remember_token'    => Str::random(10),
        ];
    }

    // ── States ────────────────────────────────────────────

    // Email verify ஆகாத user
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // Known test admin user
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'name'  => 'Admin User',
            'email' => 'admin@inkwell.app',
        ]);
    }

    // Known test author
    public function author(): static
    {
        return $this->state(fn(array $attributes) => [
            'name'  => 'Test Author',
            'email' => 'author@inkwell.app',
        ]);
    }
}
