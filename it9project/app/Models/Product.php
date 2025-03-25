<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // Import the Category model

class Product extends Model
{
    protected $primaryKey = 'product_id';
    use HasFactory;

    protected $fillable = [
        'name', 
        'price', 
        'quantity', 
        'barcode', 
        'category_id' // ✅ Add this
    ];
    

 

    /**
     * Define the relationship with the Category model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

}
