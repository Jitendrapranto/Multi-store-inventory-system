<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'total',        
        'discount',
        'vat',
        'payable',
        'total_profit',
        'user_id',
        'customer_id'
    ];

    protected $attributes=[
       'discount'=> 0,
       'vat'=> 0,
    ];

    public function customer(){
        return $this-> belongsTo(Customer::class);
    }
}
