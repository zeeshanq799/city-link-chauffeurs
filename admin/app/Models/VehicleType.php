<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VehicleType extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'per_km_price',
        'per_hour_price',
        'passenger_capacity',
        'luggage_capacity',
        'icon',
        'image',
        'features',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'per_km_price' => 'decimal:2',
        'per_hour_price' => 'decimal:2',
        'passenger_capacity' => 'integer',
        'luggage_capacity' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the vehicles for this vehicle type.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Scope a query to only include active vehicle types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile()
            ->useFallbackUrl('/images/placeholder-vehicle.jpg');
    }
}
