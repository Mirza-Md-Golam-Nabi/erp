<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => rand(1, 10),
            'sale_date' => $this->faker->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
