<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /** @use HasFactory<\Database\Factories\ProductFactory> */
    protected $fillable = [
        'name',
        'price',
        'stock_quantity',
    ];
}
