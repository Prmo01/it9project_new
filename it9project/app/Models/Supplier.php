<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id'; // Set custom primary key

    protected $fillable = [
        'name',
        'contact_number',
        'location',

    ];
}
