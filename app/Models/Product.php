<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',        
        'price',
        'unit',
        'img_url',
        'user_id',
        'buy_price',
        'stock_qty',
        'category_id'
    ];
}
