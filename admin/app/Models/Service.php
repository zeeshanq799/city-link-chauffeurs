<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'is_active',
        'is_featured',
        'sort_order',
        'short_description',
        'description',
        'features',
        'inclusions',
        'exclusions',
        'terms_conditions',
        'cancellation_policy',
        'pricing_type',
        'base_price',
        'min_price',
        'hourly_rate',
        'per_mile_rate',
        'min_hours',
        'max_hours',
        'additional_hour_rate',
        'tiered_pricing',
        'available_days',
        'available_from',
        'available_to',
        'advance_booking_hours',
        'max_advance_days',
        'max_passengers',
        'max_luggage',
        'free_waiting_time',
        'waiting_charge_per_min',
        'service_areas',
        'max_distance_miles',
        'airport_service',
        'supported_airports',
        'vehicle_types',
        'amenities',
        'icon',
        'thumbnail',
        'gallery',
        'quick_facts',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'total_bookings',
        'total_revenue',
        'avg_rating',
        'total_reviews',
        'last_booking_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'airport_service' => 'boolean',
        'base_price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'per_mile_rate' => 'decimal:2',
        'additional_hour_rate' => 'decimal:2',
        'waiting_charge_per_min' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'avg_rating' => 'decimal:2',
        'tiered_pricing' => 'array',
        'available_days' => 'array',
        'service_areas' => 'array',
        'supported_airports' => 'array',
        'vehicle_types' => 'array',
        'amenities' => 'array',
        'gallery' => 'array',
        'quick_facts' => 'array',
        'meta_keywords' => 'array',
        'last_booking_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });

        static::updating(function ($service) {
            if ($service->isDirty('name') && empty($service->slug)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    /**
     * Get the bookings for the service.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the service.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured services.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to get popular services.
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('total_bookings', 'desc')->limit($limit);
    }

    /**
     * Get formatted base price.
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->base_price) {
            return '$' . number_format($this->base_price, 2);
        }
        return 'N/A';
    }

    /**
     * Get price display text.
     */
    public function getPriceDisplayAttribute()
    {
        switch ($this->pricing_type) {
            case 'flat_rate':
                return 'From ' . $this->formatted_price;
            case 'hourly':
                return 'Starting at $' . number_format($this->hourly_rate ?? 0, 2) . '/hr';
            case 'distance_based':
                return '$' . number_format($this->per_mile_rate ?? 0, 2) . ' per mile';
            case 'custom':
                return 'Custom Quote';
            case 'tiered':
                return 'Tiered Pricing';
            default:
                return $this->formatted_price;
        }
    }

    /**
     * Check if service is available on a specific day.
     */
    public function isAvailableOnDay($day)
    {
        if (!$this->available_days) {
            return true;
        }
        return in_array(strtolower($day), array_map('strtolower', $this->available_days));
    }

    /**
     * Check if service is available at a specific time.
     */
    public function isAvailableAt($time)
    {
        if (!$this->available_from || !$this->available_to) {
            return true;
        }
        $checkTime = strtotime($time);
        $fromTime = strtotime($this->available_from);
        $toTime = strtotime($this->available_to);
        
        return $checkTime >= $fromTime && $checkTime <= $toTime;
    }

    /**
     * Check if booking can be made in advance for a specific date.
     */
    public function canBookInAdvance($date)
    {
        $hoursInAdvance = now()->diffInHours($date);
        
        if ($hoursInAdvance < $this->advance_booking_hours) {
            return false;
        }
        
        if ($this->max_advance_days) {
            $daysInAdvance = now()->diffInDays($date);
            if ($daysInAdvance > $this->max_advance_days) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get category display name.
     */
    public function getCategoryDisplayAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->category));
    }

    /**
     * Get pricing type display name.
     */
    public function getPricingTypeDisplayAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->pricing_type));
    }
}
