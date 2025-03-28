<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add cost_price if not already added
            if (!Schema::hasColumn('products', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->after('barcode');
            }

            // Rename price to sell_price
            if (Schema::hasColumn('products', 'price')) {
                $table->renameColumn('price', 'sell_price');
            }

            // Make quantity unsigned and set default to 0
            $table->integer('quantity')->unsigned()->default(0)->change();

            // ✅ Remove this since the unique constraint already exists
            // $table->string('barcode', 50)->unique()->change(); 
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }

            if (Schema::hasColumn('products', 'sell_price')) {
                $table->renameColumn('sell_price', 'price');
            }

            $table->integer('quantity')->change(); // Remove unsigned constraint
            // $table->string('barcode', 50)->change(); // No need to reset unique constraint
        });
    }
};


