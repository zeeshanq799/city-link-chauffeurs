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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('bio')->nullable();
            
            // License Information
            $table->string('license_number')->unique();
            $table->date('license_expiry')->nullable();
            $table->integer('experience_years')->default(0);
            $table->json('languages')->nullable(); // e.g., ["English", "Spanish", "French"]
            
            // Status & Verification
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->boolean('is_available')->default(false); // Online/Offline
            $table->string('background_check_status')->default('pending'); // pending, passed, failed
            
            // Performance Metrics
            $table->decimal('rating_avg', 3, 2)->default(0.00); // Average rating (0.00 to 5.00)
            $table->integer('total_trips')->default(0);
            $table->decimal('total_earnings', 10, 2)->default(0.00);
            $table->decimal('acceptance_rate', 5, 2)->default(100.00); // Percentage
            $table->decimal('cancellation_rate', 5, 2)->default(0.00); // Percentage
            
            // Documents & Media (JSON for flexibility)
            $table->json('documents')->nullable(); // Store document references
            
            // Approval Tracking
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('verification_status');
            $table->index('is_available');
            $table->index('rating_avg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
