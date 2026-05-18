<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_name',
        'table_number',
        'visit_time',
        'rating',
        'comment',
    ];
}