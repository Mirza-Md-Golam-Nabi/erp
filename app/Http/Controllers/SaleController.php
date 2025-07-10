<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create()
    {
        $customers = Customer::pluck('name', 'id');
        $products = Product::all();

        return view('sale.create')->with([
            'customers' => $customers,
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $products = $request->products;

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'sale_date' => $request->sale_date,
                'total_amount' => 0
            ]);

            if ($request->note) {
                $sale->notes()->create([
                    'body' => $request->note,
                ]);
            }

            $total_price = 0;
            foreach ($products as $product) {
                $discount = $product['discount'] ?? 0;
                $subtotal = (($product['quantity'] * $product['price']) - $discount);
                $total_price += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $discount,
                    'subtotal' => $subtotal
                ]);
            }

            $sale->total_amount = $total_price;
            $sale->save();

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Created successfully']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Please try again.']);
        }
    }
}
