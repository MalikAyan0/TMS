<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserStatus>
 */
class UserStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info'];
        $statuses = ['Active', 'Inactive', 'Pending', 'Suspended', 'Verified', 'Blocked', 'Trial', 'Expired'];

        return [
            'label' => $this->faker->unique()->randomElement($statuses),
            'color' => $this->faker->randomElement($colors),
            'description' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the status is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the status is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    /**
     * Set a specific color for the status.
     */
    public function withColor(string $color): static
    {
        return $this->state(fn (array $attributes) => [
            'color' => $color,
        ]);
    }
}
