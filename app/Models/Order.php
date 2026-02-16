<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'total_amount', 'discount_amount', 'shipping_amount', 'tax_amount',
        'name', 'email', 'phone', 'address', 'city', 'state', 'zip_code', 'country', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
