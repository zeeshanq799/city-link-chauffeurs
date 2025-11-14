<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [
            [
                'name' => 'Sedan',
                'description' => 'Comfortable and economical option for city rides. Perfect for airport transfers and business meetings.',
                'base_price' => 30.00,
                'per_km_price' => 1.50,
                'per_hour_price' => 25.00,
                'passenger_capacity' => 4,
                'luggage_capacity' => 2,
                'icon' => 'heroicon-o-building-office',
                'features' => ['AC', 'GPS', 'Professional Driver', 'Child Seat Available'],
                'is_active' => true,
            ],
            [
                'name' => 'SUV',
                'description' => 'Spacious SUV perfect for families or groups. Extra luggage space for longer trips.',
                'base_price' => 45.00,
                'per_km_price' => 2.00,
                'per_hour_price' => 35.00,
                'passenger_capacity' => 6,
                'luggage_capacity' => 4,
                'icon' => 'heroicon-o-truck',
                'features' => ['AC', 'GPS', 'WiFi', 'Professional Driver', 'Extra Luggage Space', 'Child Seats Available'],
                'is_active' => true,
            ],
            [
                'name' => 'Luxury',
                'description' => 'Premium luxury vehicles for special occasions and VIP transport. High-end comfort and style.',
                'base_price' => 75.00,
                'per_km_price' => 3.50,
                'per_hour_price' => 60.00,
                'passenger_capacity' => 4,
                'luggage_capacity' => 3,
                'icon' => 'heroicon-o-star',
                'features' => ['AC', 'GPS', 'WiFi', 'Premium Sound System', 'Leather Seats', 'Refreshments', 'Professional Driver'],
                'is_active' => true,
            ],
            [
                'name' => 'Executive Van',
                'description' => 'Perfect for corporate groups or family outings. Comfortable seating for up to 8 passengers.',
                'base_price' => 60.00,
                'per_km_price' => 2.50,
                'per_hour_price' => 45.00,
                'passenger_capacity' => 8,
                'luggage_capacity' => 6,
                'icon' => 'heroicon-o-user-group',
                'features' => ['AC', 'GPS', 'WiFi', 'Professional Driver', 'Extra Luggage Space', 'USB Charging Ports'],
                'is_active' => true,
            ],
            [
                'name' => 'Economy',
                'description' => 'Budget-friendly option for short trips and daily commutes. Clean and reliable.',
                'base_price' => 20.00,
                'per_km_price' => 1.00,
                'per_hour_price' => 18.00,
                'passenger_capacity' => 4,
                'luggage_capacity' => 1,
                'icon' => 'heroicon-o-banknotes',
                'features' => ['AC', 'GPS', 'Professional Driver'],
                'is_active' => true,
            ],
            [
                'name' => 'Business Class',
                'description' => 'Ideal for business travelers. Professional service with premium comfort.',
                'base_price' => 55.00,
                'per_km_price' => 2.75,
                'per_hour_price' => 50.00,
                'passenger_capacity' => 4,
                'luggage_capacity' => 3,
                'icon' => 'heroicon-o-briefcase',
                'features' => ['AC', 'GPS', 'WiFi', 'Leather Seats', 'Professional Driver', 'Bottled Water', 'Phone Charger'],
                'is_active' => true,
            ],
        ];

        foreach ($vehicleTypes as $type) {
            VehicleType::create($type);
        }

        $this->command->info('✅ Created ' . count($vehicleTypes) . ' vehicle types successfully!');
    }
}
