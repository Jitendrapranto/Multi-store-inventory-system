<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = [
      'qty',
      'sale_price',
      'user_id',
      'product_id',
      'invoice_id',
      'buy_price',
      'discount',
      'unit_price',
      'subtotal',
      'item_profit'
    ];

    public function product(){
        return $this-> belongsTo(Product::class);
    }
}
