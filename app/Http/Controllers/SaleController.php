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

    public function list(Request $request)
    {
        $query = Sale::query();

        if (filled($request->input('customer_name'))) {
            $customer_id = Customer::where('name', 'like', '%' . $request->input('customer_name') . '%')
                ->pluck('id');

            if ($customer_id) {
                $query->whereIn('customer_id', $customer_id);
            }
        }

        if (filled($request->input('date_from')) && filled($request->input('date_to'))) {
            $query->whereBetween('sale_date', [
                $request->input('date_from'),
                $request->input('date_to')
            ])->orderBy('sale_date', 'desc');
        }

        $sales = $query->with('customer', 'sale_items', 'sale_items.product', 'notes')
            ->paginate(5);

        return view('sale.list')->with([
            'sales' => $sales
        ]);
    }

    public function delete(Sale $sale)
    {
        $sale->delete();

        session()->flash('success', 'Successfully removed.');

        return redirect()->route('sale.list');
    }

    public function removed()
    {
        $sales = Sale::onlyTrashed()
            ->with('customer', 'sale_items', 'sale_items.product', 'notes')
            ->paginate(3);

        return view('sale.remove_list')->with([
            'sales' => $sales
        ]);
    }

    public function restore($sale_id)
    {
        $sale = Sale::onlyTrashed()->find($sale_id);

        if (! $sale) {
            session()->flash('error', 'Sale ID does not find out.');
            return redirect()->route('sale.removed');
        }

        $sale->restore();

        session()->flash('success', 'Successfully restored the sale');

        $sales = Sale::onlyTrashed()
            ->with('customer', 'sale_items', 'sale_items.product', 'notes')
            ->paginate(3);

        return view('sale.remove_list')->with([
            'sales' => $sales
        ]);
    }
}
