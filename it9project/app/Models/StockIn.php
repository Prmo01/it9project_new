<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    protected $primaryKey = 'stockin_id'; // Specify custom primary key
    
    protected $fillable = [
        'product_id',
        'stock_order_id', // Make sure this is included
        'supplier_id',
        'quantity_added'
    ];

    public function order()
    {
        return $this->belongsTo(StockOrder::class, 'stock_order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}