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
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Sedan", "SUV", "Luxury"
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(0); // Base booking price
            $table->decimal('per_km_price', 10, 2)->default(0); // Price per kilometer
            $table->decimal('per_hour_price', 10, 2)->default(0); // Price per hour
            $table->integer('passenger_capacity')->default(4); // Number of passengers
            $table->integer('luggage_capacity')->default(2); // Number of luggage pieces
            $table->string('icon')->nullable(); // Icon name or path
            $table->string('image')->nullable(); // Vehicle type image
            $table->json('features')->nullable(); // JSON array of features ["WiFi", "AC", etc.]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('is_active');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
