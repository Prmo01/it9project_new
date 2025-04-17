<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'name',
        'contact_number',
        'location',
        // Remove 'is_active' if not needed
    ];

    // Relationships
    public function stockOrders()
    {
        return $this->hasMany(StockOrder::class, 'supplier_id');
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'supplier_id');
    }
}