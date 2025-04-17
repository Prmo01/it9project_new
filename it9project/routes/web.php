<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product routes
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    
    // Resource routes
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    
    // Stock In routes
    Route::resource('stockin', StockInController::class);
    Route::put('/stockin/{stockin}/status', [StockInController::class, 'updateStatus'])
        ->name('stockin.update-status');

    // Transaction (POS) routes
    // Transaction (POS) routes
    Route::get('/transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transaction/add', [TransactionController::class, 'addProduct'])->name('transaction.add');
    Route::delete('/transaction/remove/{id}', [TransactionController::class, 'removeProduct'])->name('transaction.remove');
    Route::put('/transaction/update-quantity/{id}', [TransactionController::class, 'updateQuantity'])->name('transaction.updateQuantity'); // ← ADD THIS
    Route::post('/transaction/complete', [TransactionController::class, 'completeTransaction'])->name('transaction.complete');

});


require __DIR__.'/auth.php';