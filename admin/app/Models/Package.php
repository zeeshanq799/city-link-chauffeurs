<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Package extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'short_description',
        'description',
        'duration_hours',
        'passenger_capacity',
        'luggage_capacity',
        'base_price',
        'discount_price',
        'discount_percentage',
        'pricing_type',
        'is_active',
        'is_featured',
        'sort_order',
        'min_booking_hours',
        'availability_24_7',
        'terms_conditions',
        'cancellation_policy',
        'inclusions',
        'exclusions',
        'quick_facts',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'inclusions' => 'array',
        'exclusions' => 'array',
        'quick_facts' => 'array',
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'duration_hours' => 'integer',
        'passenger_capacity' => 'integer',
        'luggage_capacity' => 'integer',
        'min_booking_hours' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'availability_24_7' => 'boolean',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessors
    public function getFeaturedImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('featured_images') ?: null;
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->base_price, 2);
    }

    public function getDiscountAmountAttribute()
    {
        if ($this->discount_price) {
            return $this->base_price - $this->discount_price;
        }
        
        if ($this->discount_percentage) {
            return $this->base_price * ($this->discount_percentage / 100);
        }
        
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount_price) {
            return $this->discount_price;
        }
        
        if ($this->discount_percentage) {
            return $this->base_price - $this->discount_amount;
        }
        
        return $this->base_price;
    }

    public function getCategoryBadgeAttribute()
    {
        $colors = [
            'airport' => 'blue',
            'wedding' => 'pink',
            'corporate' => 'purple',
            'city_tour' => 'green',
            'group' => 'orange',
            'custom' => 'yellow',
        ];
        
        $color = $colors[$this->category] ?? 'gray';
        $icon = $this->getCategoryIcon();
        $label = ucwords(str_replace('_', ' ', $this->category));
        
        return "<span class=\"inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800\">
                    <i class=\"fas {$icon}\"></i> {$label}
                </span>";
    }

    public function getFeaturedBadgeAttribute()
    {
        if (!$this->is_featured) {
            return '';
        }
        
        return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-star"></i> Featured
                </span>';
    }

    // Methods
    public function calculateFinalPrice()
    {
        return $this->final_price;
    }

    public function getCategoryIcon()
    {
        $icons = [
            'airport' => 'fa-plane',
            'wedding' => 'fa-heart',
            'corporate' => 'fa-briefcase',
            'city_tour' => 'fa-city',
            'group' => 'fa-users',
            'custom' => 'fa-star',
        ];
        
        return $icons[$this->category] ?? 'fa-tag';
    }

    public function getCategoryColor()
    {
        $colors = [
            'airport' => 'blue',
            'wedding' => 'pink',
            'corporate' => 'purple',
            'city_tour' => 'green',
            'group' => 'orange',
            'custom' => 'yellow',
        ];
        
        return $colors[$this->category] ?? 'gray';
    }

    // Media Library Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_images')
            ->singleFile()
            ->useFallbackUrl('/images/default-package.jpg');
    }
}
