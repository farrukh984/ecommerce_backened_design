<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'old_price', 'image', 'brand', 'rating', 'category_id', 'condition_id',
        'supplier_id', 'is_negotiable', 'is_verified', 'in_stock', 'stock_quantity', 'sold_count', 'is_active', 'type', 'material', 
        'design_style', 'customization', 'protection', 'warranty', 
        'model_number', 'item_number', 'size', 'memory', 'certificate', 'style'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function priceTiers()
    {
        return $this->hasMany(ProductPriceTier::class)->orderBy('min_qty');
    }

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

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->latest();
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true)->latest();
    }


public function getDiscountAttribute()
{
    if($this->old_price){
        return round((($this->old_price - $this->price) / $this->old_price) * 100);
    }
    return null;
}





}
