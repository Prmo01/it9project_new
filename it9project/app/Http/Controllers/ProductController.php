<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; 
use Illuminate\Foundation\Validation\ValidatesRequests;

class ProductController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Product::query()->with('category');

    // Search by name or barcode
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    // Filter by category (only if a valid category is selected)
    if ($request->filled('category') && $request->input('category') !== '') {
        $query->where('category_id', $request->input('category'));
    }

    // Paginate results
    $products = $query->paginate(7);

    $categories = Category::all();

    return view('products.index', compact('products', 'categories'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'barcode' => 'required|string|size:12|unique:products,barcode',
        'category_id' => 'required|exists:categories,category_id', // ✅ Use category_id, not id
    ]);
    

    $product = new Product();
    $product->name = $request->input('name');
    $product->price = $request->input('price');
    $product->quantity = 0; // Default quantity
    $product->barcode = $request->input('barcode');
    $product->category_id = $request->input('category_id'); // Assign category_id
    $product->save();

    return redirect()->back()->with('success', 'Product created successfully');
}




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'required|numeric|min:0',
            'barcode' => 'required|string|size:12|unique:products,barcode,' . $id . ',product_id',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'barcode' => $request->barcode,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('products.index')->with('success', 'Product deleted successfully');
}
}
