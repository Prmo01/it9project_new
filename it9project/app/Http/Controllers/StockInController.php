<?php

namespace App\Http\Controllers;

use App\Models\StockOrder;
use App\Models\StockIn;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockInController extends Controller
{
    public function index(Request $request)
{
    $query = StockOrder::with(['supplier', 'items.product'])
        ->orderBy('created_at', 'desc');

    // Search filter
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('reference_number', 'like', "%{$search}%")
              ->orWhereHas('supplier', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
              });
        });
    }

    // Date filter
    if ($request->has('date') && !empty($request->date)) {
        $query->whereDate('created_at', $request->date);
    }

    // Status filter
    if ($request->has('status') && !empty($request->status)) {
        $query->where('status', $request->status);
    }

    // Keep old input values for the form
    $filters = [
        'search' => $request->search,
        'date' => $request->date,
        'status' => $request->status
    ];

    $stockOrders = $query->paginate(6)
        ->appends($filters); // Preserve filters in pagination links

    $suppliers = Supplier::all();
    $products = Product::all();

    return view('stockin.index', compact('stockOrders', 'suppliers', 'products', 'filters'));
}

    public function create()
    {
        $products = Product::get(['product_id', 'name', 'cost_price']);
        $suppliers = Supplier::get(['supplier_id', 'name', 'location']);

        return view('stockin.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $order = StockOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'status' => 'draft',
                'reference_number' => 'PO-' . strtoupper(uniqid()),
            ]);

            foreach ($validated['products'] as $product) {
                StockIn::create([
                    'stock_order_id' => $order->stock_order_id,
                    'product_id' => $product['product_id'],
                    'supplier_id' => $validated['supplier_id'],
                    'quantity_added' => $product['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('stockin.index')
                ->with('success', 'Order #' . $order->reference_number . ' created!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    public function show($id)
{
    try {
        $order = StockOrder::with([
                'items.product' => function($query) {
                    $query->select('product_id', 'name', 'cost_price');
                },
                'supplier' => function($query) {
                    $query->select('supplier_id', 'name', 'location');
                },
                'user' => function($query) {
                    $query->select('id', 'name');
                }
            ])
            ->findOrFail($id);
            
        if(request()->ajax()) {
            $items = $order->items->map(function($item) {
                return [
                    'product' => $item->product,
                    'quantity_added' => $item->quantity_added,
                    'total' => $item->product ? ($item->product->cost_price * $item->quantity_added) : 0
                ];
            });
            
            $totalQuantity = $order->items->sum('quantity_added');
            $totalCost = $order->items->sum(function($item) {
                return $item->product ? ($item->product->cost_price * $item->quantity_added) : 0;
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'order' => [
                        'reference_number' => $order->reference_number,
                        'status' => $order->status,
                        'created_at' => $order->created_at->toDateTimeString(),
                        'supplier' => $order->supplier,
                        'user' => $order->user,
                    ],
                    'items' => $items,
                    'totals' => [
                        'quantity' => $totalQuantity,
                        'cost' => $totalCost
                    ]
                ]
            ]);
        }
        
        return view('stockin.show', compact('order'));
        
    } catch (\Exception $e) {
        Log::error('Failed to load order details', [
            'error' => $e->getMessage(),
            'order_id' => $id,
            'trace' => $e->getTraceAsString()
        ]);

        if(request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading order details: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'Error loading order details');
    }
}

    public function edit($id)
    {
        $order = StockOrder::with([
                'items.product' => function($query) {
                    $query->select('product_id', 'name', 'cost_price');
                }
            ])
            ->findOrFail($id);
            
        $products = Product::get(['product_id', 'name', 'cost_price']);
        $suppliers = Supplier::get(['supplier_id', 'name', 'location']);
        
        return view('stockin.edit', compact('order', 'products', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $order = StockOrder::findOrFail($id);
        
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,product_id',
            'products.*.quantity' => 'required|integer|min:1|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $order->update(['supplier_id' => $validated['supplier_id']]);

            $order->items()->delete();

            foreach ($validated['products'] as $product) {
                StockIn::create([
                    'stock_order_id' => $order->stock_order_id,
                    'product_id' => $product['product_id'],
                    'supplier_id' => $validated['supplier_id'],
                    'quantity_added' => $product['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('stockin.index')
                ->with('success', 'Order updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order update failed', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $order = StockOrder::with('items')->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:draft,ordered,received,partial,cancelled'
        ]);
        
        DB::beginTransaction();

        try {
            if ($request->status === 'received' && $order->status !== 'received') {
                foreach ($order->items as $item) {
                    Product::where('product_id', $item->product_id)
                        ->increment('quantity', $item->quantity_added);
                }
            } elseif ($order->status === 'received' && $request->status !== 'received') {
                foreach ($order->items as $item) {
                    Product::where('product_id', $item->product_id)
                        ->decrement('quantity', $item->quantity_added);
                }
            }
            
            $order->update(['status' => $request->status]);
            
            DB::commit();

            return redirect()->route('stockin.index')
                ->with('success', 'Order status updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Status update failed', ['error' => $e->getMessage()]);
            return back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $order = StockOrder::with('items')->findOrFail($id);
        
        DB::beginTransaction();

        try {
            if ($order->status === 'received') {
                foreach ($order->items as $item) {
                    Product::where('product_id', $item->product_id)
                        ->decrement('quantity', $item->quantity_added);
                }
            }
            
            $order->delete();
            
            DB::commit();

            return redirect()->route('stockin.index')
                ->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order deletion failed', ['error' => $e->getMessage()]);
            return back()
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }
}