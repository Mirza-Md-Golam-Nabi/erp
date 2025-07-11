<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleItem>
 */
class SaleItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->numberBetween(50, 200);
        $discount = $this->faker->numberBetween(5, 20);
        $subtotal = ($quantity * $price) - $discount;

        return [
            'product_id' => Product::inRandomOrder()->value('id') ?? 1,
            'quantity' => $quantity,
            'price' => $price,
            'discount' => $discount,
            'subtotal' => $subtotal,
        ];
    }
}
