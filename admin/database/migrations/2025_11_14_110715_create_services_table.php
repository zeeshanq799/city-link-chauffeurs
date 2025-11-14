<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            // Primary Fields
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->enum('category', [
                'point-to-point',
                'hourly-charter', 
                'airport-transfer',
                'corporate',
                'events',
                'tours'
            ])->default('point-to-point');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            
            // Description & Content
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->longText('features')->nullable();
            $table->longText('inclusions')->nullable();
            $table->longText('exclusions')->nullable();
            $table->longText('terms_conditions')->nullable();
            $table->longText('cancellation_policy')->nullable();
            
            // Pricing Configuration
            $table->enum('pricing_type', [
                'flat_rate',
                'hourly',
                'distance_based',
                'custom',
                'tiered'
            ])->default('flat_rate');
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('min_price', 10, 2)->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('per_mile_rate', 10, 2)->nullable();
            $table->integer('min_hours')->nullable();
            $table->integer('max_hours')->nullable();
            $table->decimal('additional_hour_rate', 10, 2)->nullable();
            $table->json('tiered_pricing')->nullable();
            
            // Availability & Booking
            $table->json('available_days')->nullable();
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
            $table->integer('advance_booking_hours')->default(24);
            $table->integer('max_advance_days')->nullable();
            $table->integer('max_passengers')->nullable();
            $table->integer('max_luggage')->nullable();
            $table->integer('free_waiting_time')->nullable();
            $table->decimal('waiting_charge_per_min', 10, 2)->nullable();
            
            // Service Areas
            $table->json('service_areas')->nullable();
            $table->integer('max_distance_miles')->nullable();
            $table->boolean('airport_service')->default(false);
            $table->json('supported_airports')->nullable();
            
            // Vehicle Associations
            $table->json('vehicle_types')->nullable();
            $table->json('amenities')->nullable();
            
            // Media
            $table->string('icon', 100)->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('gallery')->nullable();
            
            // Quick Facts
            $table->json('quick_facts')->nullable();
            
            // SEO
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Statistics
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('avg_rating', 3, 2)->nullable();
            $table->integer('total_reviews')->default(0);
            $table->timestamp('last_booking_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('category');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('sort_order');
            $table->index(['category', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
