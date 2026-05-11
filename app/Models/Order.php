<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Order has many Order Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Order belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}