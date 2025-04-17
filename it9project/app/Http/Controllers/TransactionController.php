<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    // Display the transaction page (cart)
    public function index()
    {
        $cart = Session::get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('transaction.index', compact('cart', 'total'));
    }

    // Add product to the cart by barcode or name
    public function addProduct(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|max:255',
        ]);

        $product = Product::where('barcode', $request->product_code)
                        ->orWhere('name', 'like', '%' . $request->product_code . '%')
                        ->first();

        if (!$product) {
            return redirect()->route('transaction.index')->with('success', 'Product not found!');
        }

        if ($product->quantity < 1) {
            return redirect()->route('transaction.index')->with('success', 'Product is out of stock!');
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->product_id])) {
            $currentQty = $cart[$product->product_id]['quantity'];
            if ($currentQty + 1 > $product->quantity) {
                return redirect()->route('transaction.index')->with('success', 'Cannot exceed available stock!');
            }
            $cart[$product->product_id]['quantity'] += 1;
        } else {
            $cart[$product->product_id] = [
                'id'       => $product->product_id,
                'name'     => $product->name,
                'price'    => $product->sell_price,
                'quantity' => 1,
            ];
        }

        Session::put('cart', $cart);
        return redirect()->route('transaction.index')->with('success', 'Product added!');
    }

    // Update quantity of a product in the cart
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($request->quantity > $product->quantity) {
            return response()->json(['error' => 'Quantity exceeds available stock'], 400);
        }

        $cart = Session::get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            Session::put('cart', $cart);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Product not in cart'], 404);
    }

    // Remove a product from the cart
    public function removeProduct($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);

        return redirect()->route('transaction.index')->with('success', 'Product removed!');
    }

    // Complete the transaction (purchase)
    public function completeTransaction(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('transaction.index')->with('success', 'Cart is empty!');
        }

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $totalWithTax = $total * 1.12; // 12% Tax

        if ($request->payment_amount < $totalWithTax) {
            return redirect()->route('transaction.index')->with('error', 'Insufficient payment amount!');
        }

        // Deduct stock and complete the transaction
        foreach ($cart as $item) {
            $product = Product::find($item['id']);

            if ($product) {
                if ($product->quantity < $item['quantity']) {
                    return redirect()->route('transaction.index')->with('success', "Not enough stock for {$product->name}");
                }

                $product->quantity -= $item['quantity'];
                $product->save();
            }
        }

        // (Optional) Save transaction to DB here...

        Session::forget('cart');
        return redirect()->route('transaction.index')->with('success', 'Transaction completed!');
    }
}
