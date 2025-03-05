<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'products',
        'total_price',
        'status',
        'city',
        'address',
        'building_number',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'products'    => 'array',
        'total_price' => 'float',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
