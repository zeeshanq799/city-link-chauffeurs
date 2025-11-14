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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')->constrained()->onDelete('restrict');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            
            // Vehicle Identification
            $table->string('license_plate')->unique();
            $table->string('make'); // e.g., Toyota, BMW, Mercedes
            $table->string('model'); // e.g., Camry, 5 Series, S-Class
            $table->integer('year');
            $table->string('color')->nullable();
            $table->string('vin')->unique()->nullable(); // Vehicle Identification Number
            
            // Registration & Insurance
            $table->date('registration_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('insurance_policy_number')->nullable();
            
            // Maintenance & Condition
            $table->integer('mileage')->default(0); // Current mileage in km
            $table->date('last_maintenance_date')->nullable();
            $table->integer('next_maintenance_mileage')->nullable();
            $table->text('maintenance_notes')->nullable();
            
            // Features & Specifications
            $table->json('features')->nullable(); // ["GPS", "Dashcam", "WiFi"]
            $table->text('description')->nullable();
            
            // Status & Availability
            $table->enum('status', [
                'available',      // Ready for booking
                'in_service',     // Currently on a trip
                'maintenance',    // Under maintenance
                'out_of_service', // Not available
                'retired'         // No longer in fleet
            ])->default('available');
            
            $table->boolean('is_active')->default(true);
            
            // Purchase Information (optional)
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // Soft delete support
            
            // Indexes
            $table->index('vehicle_type_id');
            $table->index('driver_id');
            $table->index('status');
            $table->index('is_active');
            $table->index('license_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
