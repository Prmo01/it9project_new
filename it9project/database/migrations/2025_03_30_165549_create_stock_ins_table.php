<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id('stockin_id'); // Auto-incrementing primary key
            
            // Foreign key for product_id (explicitly referencing product_id in products table)
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('set null');

            // Foreign key for supplier_id (explicitly referencing supplier_id in suppliers table)
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null');

            $table->unsignedInteger('quantity_added');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_ins');
    }
};
