<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','description','price','old_price','image','brand','rating','category_id','condition_id'
    ];

    protected $casts = [
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_features');
    }


public function getDiscountAttribute()
{
    if($this->old_price){
        return round((($this->old_price - $this->price) / $this->old_price) * 100);
    }
    return null;
}





}

