<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vehicle extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_type_id',
        'driver_id',
        'license_plate',
        'make',
        'model',
        'year',
        'color',
        'vin',
        'registration_expiry',
        'insurance_expiry',
        'insurance_policy_number',
        'mileage',
        'last_maintenance_date',
        'next_maintenance_mileage',
        'maintenance_notes',
        'features',
        'description',
        'status',
        'is_active',
        'purchase_date',
        'purchase_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vehicle_type_id' => 'integer',
        'driver_id' => 'integer',
        'year' => 'integer',
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
        'mileage' => 'integer',
        'last_maintenance_date' => 'date',
        'next_maintenance_mileage' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    /**
     * Get the vehicle type that this vehicle belongs to.
     */
    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * Get the driver assigned to this vehicle.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the bookings for this vehicle.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the maintenance records for this vehicle.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include available vehicles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    /**
     * Scope a query to only include vehicles in service.
     */
    public function scopeInService($query)
    {
        return $query->where('status', 'in_service');
    }

    /**
     * Scope a query to only include vehicles under maintenance.
     */
    public function scopeUnderMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Check if vehicle registration is expired or expiring soon.
     */
    public function isRegistrationExpiringSoon(int $days = 30): bool
    {
        if (!$this->registration_expiry) {
            return false;
        }
        
        return $this->registration_expiry->diffInDays(now(), false) >= -$days;
    }

    /**
     * Check if vehicle insurance is expired or expiring soon.
     */
    public function isInsuranceExpiringSoon(int $days = 30): bool
    {
        if (!$this->insurance_expiry) {
            return false;
        }
        
        return $this->insurance_expiry->diffInDays(now(), false) >= -$days;
    }

    /**
     * Check if vehicle needs maintenance based on mileage.
     */
    public function needsMaintenance(): bool
    {
        if (!$this->next_maintenance_mileage) {
            return false;
        }
        
        return $this->mileage >= $this->next_maintenance_mileage;
    }

    /**
     * Get full vehicle name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->year} {$this->make} {$this->model} ({$this->license_plate})";
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/placeholder-vehicle.jpg');

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);
    }
}
