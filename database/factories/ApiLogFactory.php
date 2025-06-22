<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiLog>
 */
class ApiLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'url' => $this->faker->url,
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
            'ip' => $this->faker->ipv4,
        ];
    }
}
