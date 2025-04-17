<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            // First add the column without constraint
            if (!Schema::hasColumn('stock_ins', 'stock_order_id')) {
                $table->unsignedBigInteger('stock_order_id')->nullable()->after('product_id');
            }
            
            // Then add the foreign key constraint separately
            if (Schema::hasColumn('stock_ins', 'stock_order_id')) {
                $table->foreign('stock_order_id')
                      ->references('stock_order_id')  // Correct reference to stock_orders PK
                      ->on('stock_orders')
                      ->onDelete('cascade');
            }
            
            // Rename quantity field if needed
            if (Schema::hasColumn('stock_ins', 'quantity_subse')) {
                $table->renameColumn('quantity_subse', 'quantity_added');
            }
        });
    }

    public function down()
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropForeign(['stock_order_id']);
            $table->dropColumn('stock_order_id');
            
            // Optional: revert quantity field rename if needed
            if (Schema::hasColumn('stock_ins', 'quantity_added')) {
                $table->renameColumn('quantity_added', 'quantity_subse');
            }
        });
    }
};