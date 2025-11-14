<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'bio',
        'license_number',
        'license_expiry',
        'experience_years',
        'languages',
        'status',
        'verification_status',
        'is_available',
        'background_check_status',
        'rating_avg',
        'total_trips',
        'total_earnings',
        'acceptance_rate',
        'cancellation_rate',
        'documents',
        'approved_at',
        'approved_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'license_expiry' => 'date',
        'languages' => 'array',
        'documents' => 'array',
        'is_available' => 'boolean',
        'experience_years' => 'integer',
        'rating_avg' => 'decimal:2',
        'total_trips' => 'integer',
        'total_earnings' => 'decimal:2',
        'acceptance_rate' => 'decimal:2',
        'cancellation_rate' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the vehicles assigned to this driver.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get the user who approved this driver.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include active drivers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include available drivers.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include verified drivers.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Get the driver's full status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>',
            'inactive' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>',
            'suspended' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    /**
     * Get the driver's verification badge.
     */
    public function getVerificationBadgeAttribute(): string
    {
        return match($this->verification_status) {
            'verified' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✓ Verified</span>',
            'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">⏳ Pending</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">✗ Rejected</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    /**
     * Get the driver's availability badge.
     */
    public function getAvailabilityBadgeAttribute(): string
    {
        return $this->is_available 
            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">🟢 Online</span>'
            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">⚫ Offline</span>';
    }

    /**
     * Get profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('profile_photos') ?: null;
    }

    /**
     * Get license photo URL.
     */
    public function getLicensePhotoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('license_photos') ?: null;
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photos')
             ->singleFile()
             ->useFallbackUrl('/images/default-avatar.png');

        $this->addMediaCollection('license_photos')
             ->singleFile();

        $this->addMediaCollection('documents');
    }
}
