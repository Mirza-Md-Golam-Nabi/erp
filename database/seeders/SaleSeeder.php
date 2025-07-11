<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sale::factory()
            ->count(10)
            ->create()
            ->each(function ($sale) {

                $items = SaleItem::factory()
                    ->count(rand(1, 5))
                    ->make();

                $totalAmount = 0;
                foreach ($items as $item) {
                    $item->sale_id = $sale->id;
                    $item->save();
                    $totalAmount += $item->subtotal;
                }

                $sale->update(['total_amount' => $totalAmount]);

                $sale->notes()->create([
                    'body' => fake()->realText(50),
                ]);
            });
    }
}
