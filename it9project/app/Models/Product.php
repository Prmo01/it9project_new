<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id'; // Set primary key to product_id

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'barcode',
        'category_id',
        'cost_price',
        'sell_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
