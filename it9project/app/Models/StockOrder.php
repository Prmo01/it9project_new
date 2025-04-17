<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'stock_order_id';

    protected $fillable = [
        'supplier_id',
        'user_id',
        'reference_number',
        'status',
        'notes',
        'expected_delivery_date'
    ];

    protected $casts = [
        'expected_delivery_date' => 'date',
    ];

    /**
     * Get all items in this order
     */
    public function items()
    {
        return $this->hasMany(StockIn::class, 'stock_order_id', 'stock_order_id');
    }

    /**
     * Get the supplier for this order
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the user who created this order
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Calculate total quantity of all items in this order
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity_added');
    }

    /**
     * Calculate total cost of this order
     */
    public function getTotalCostAttribute()
    {
        return $this->items->sum(function($item) {
            return $item->quantity_added * $item->product->cost_price;
        });
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for received orders
     */
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }
}