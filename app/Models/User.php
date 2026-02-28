<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name', 'email', 'password', 'role', 'profile_image', 'cover_image', 'phone', 'address', 'city', 'state', 'zip_code', 'country'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function getRankAttribute()
    {
        $totalSpent = $this->orders()->where('status', '!=', 'canceled')->sum('total_amount');
        
        if ($totalSpent > 6000) return 'Silver';
        if ($totalSpent > 5000) return 'Bronze';
        return 'Classic';
    }

    public function getIsCouponEligibleAttribute()
    {
        // Must have 6000+ delivered shopping
        $deliveredSpent = $this->orders()->where('status', 'delivered')->sum('total_amount');
        return $deliveredSpent >= 6000;
    }

    public function getUniqueCouponCodeAttribute()
    {
        // Generate a unique code like LOYAL-NAME-ID
        $namePart = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $this->name), 0, 3));
        return "LOYAL-" . $namePart . "-" . ($this->id + 1000);
    }

    public function getProfileCompletionAttribute()
    {
        $fields = [
            'name', 'email', 'phone', 'address', 'city', 
            'state', 'zip_code', 'country', 'profile_image', 'cover_image'
        ];
        
        $filledCount = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filledCount++;
            }
        }
        
        return round(($filledCount / count($fields)) * 100);
    }

    /**
     * Check if user is online
     */
    public function isOnline()
    {
        return $this->last_seen_at && $this->last_seen_at->diffInMinutes(now()) < 5;
    }
}
