<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->paginate(7);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gt:cost_price',
            'category_id' => 'required|exists:categories,category_id',
            'barcode' => 'required|string|size:12|unique:products,barcode',
        ]);

        Product::create([
            'name' => $request->name,
            'cost_price' => $request->cost_price,
            'sell_price' => $request->sell_price,
            'quantity' => 0,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully');
    }

    public function update(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gt:cost_price',
            'category_id' => 'required|exists:categories,category_id',
            'barcode' => 'required|string|size:12|unique:products,barcode,'.$product->product_id.',product_id',
        ]);

        $product->update([
            'name' => $request->name,
            'cost_price' => $request->cost_price,
            'sell_price' => $request->sell_price,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}