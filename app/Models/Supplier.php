<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line for HasFactory

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'location', 'country_flag', 'is_verified', 'has_worldwide_shipping'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
