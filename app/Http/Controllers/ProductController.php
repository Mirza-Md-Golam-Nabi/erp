<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    public function create()
    {
        return view('product.create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $product = Product::create($data);

            $product->notes()->create([
                'body' => $data['note'],
            ]);

            DB::commit();

            session()->flash('success', 'Product added successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            session()->flash('error', 'Product does not add.');
        }

        return $this->create();
    }

    public function list()
    {
        $products = Product::with('notes')->get();

        return view('product.list')->with([
            'products' => $products
        ]);
    }

    public function note(Request $request)
    {
        $product_id = $request->product_id;
        $note = $request->note;

        $product = Product::find($product_id);

        $product->notes()->create([
            'body' => $note
        ]);

        session()->flash('success', 'Note added successfully.');

        return redirect()->route('product.list');
    }
}
