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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['airport', 'wedding', 'corporate', 'city_tour', 'group', 'custom']);
            $table->string('short_description', 200)->nullable();
            $table->text('description');
            $table->integer('duration_hours');
            $table->integer('passenger_capacity');
            $table->integer('luggage_capacity')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->enum('pricing_type', ['fixed', 'hourly', 'daily'])->default('fixed');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->integer('min_booking_hours')->nullable();
            $table->boolean('availability_24_7')->default(true);
            $table->text('terms_conditions')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('quick_facts')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index('category');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
