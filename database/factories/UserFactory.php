<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'office_id' => null,
            'role_id' => null,
            'is_active' => true,
            'verified' => true,
            'two_factor_authentication' => false,
            'remember_token' => Str::random(10),
        ];
    }

    public function citizen(int $roleId): static
    {
        return $this->state(fn () => [
            'office_id' => null,
            'role_id' => $roleId,
            'is_active' => true,
            'verified' => true,
            'email_verified_at' => now(),
            'two_factor_authentication' => false,
            'password' => 'password',
        ]);
    }

    public function officeStaff(int $roleId, int $officeId): static
    {
        return $this->state(fn () => [
            'office_id' => $officeId,
            'role_id' => $roleId,
            'is_active' => true,
            'verified' => true,
            'email_verified_at' => now(),
            'two_factor_authentication' => false,
            'password' => 'password',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
            'verified' => false,
        ]);
    }
}
