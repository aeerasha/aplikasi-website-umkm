<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Order Item belongs to Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Order Item belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}