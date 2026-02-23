<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'title', 'description', 'discount_percent',
        'start_date', 'end_date', 'is_active', 'product_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the currently active deal (first active deal whose end_date is in the future).
     */
    public static function activeDeal()
    {
        return static::where('is_active', true)
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'asc')
            ->first();
    }
}
