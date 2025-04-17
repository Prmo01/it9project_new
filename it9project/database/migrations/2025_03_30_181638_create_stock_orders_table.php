<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_orders', function (Blueprint $table) {
            $table->id('stock_order_id');  // Primary key
            
            // Foreign key to users table (matches users.id)
            $table->unsignedBigInteger('user_id');
            
            // Foreign key to suppliers table (matches suppliers.supplier_id)
            $table->unsignedBigInteger('supplier_id');
            
            $table->string('reference_number')->unique();
            $table->enum('status', ['draft', 'ordered', 'received', 'partial', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add foreign key constraints separately for better error handling
        Schema::table('stock_orders', function (Blueprint $table) {
            // Reference to users table
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Reference to suppliers table
            $table->foreign('supplier_id')
                  ->references('supplier_id')  // Matches your suppliers PK
                  ->on('suppliers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('stock_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['supplier_id']);
        });
        
        Schema::dropIfExists('stock_orders');
    }
};