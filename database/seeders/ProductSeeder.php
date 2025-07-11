<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = $this->products();

        foreach ($products as $product) {
            $product = Product::create([
                'name' => $product['name'],
                'price' => $product['price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $product->notes()->createMany(array_map(function () {
                return ['body' => fake()->realText(50)];
            }, range(1, rand(1, 3))));
        }
    }

    private function products(): array
    {
        return [
            ['name' => 'iPhone 15', 'price' => 999],
            ['name' => 'MacBook Air', 'price' => 1299],
            ['name' => 'AirPods Pro', 'price' => 249],
            ['name' => 'iPad Pro', 'price' => 799],
            ['name' => 'Apple Watch', 'price' => 399],
            ['name' => 'Samsung TV', 'price' => 899],
            ['name' => 'PlayStation 5', 'price' => 499],
            ['name' => 'Wireless Keyboard', 'price' => 59],
            ['name' => 'Bluetooth Speaker', 'price' => 129],
            ['name' => 'External SSD', 'price' => 149],
            ['name' => 'Fitness Tracker', 'price' => 79],
            ['name' => 'Smart Thermostat', 'price' => 199],
            ['name' => 'Robot Vacuum', 'price' => 299],
            ['name' => 'Gaming Mouse', 'price' => 49],
            ['name' => '4K Camera', 'price' => 599],
        ];
    }
}
