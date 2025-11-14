<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get vehicle types
        $sedan = VehicleType::where('name', 'Sedan')->first();
        $suv = VehicleType::where('name', 'SUV')->first();
        $luxury = VehicleType::where('name', 'Luxury')->first();
        $van = VehicleType::where('name', 'Executive Van')->first();
        $economy = VehicleType::where('name', 'Economy')->first();
        $business = VehicleType::where('name', 'Business Class')->first();

        $vehicles = [
            // Sedans
            [
                'vehicle_type_id' => $sedan?->id,
                'license_plate' => 'ABC-1234',
                'make' => 'Toyota',
                'model' => 'Camry',
                'year' => 2023,
                'color' => 'Silver',
                'vin' => '1HGBH41JXMN109186',
                'registration_expiry' => Carbon::now()->addMonths(8),
                'insurance_expiry' => Carbon::now()->addMonths(6),
                'insurance_policy_number' => 'POL-2024-001',
                'mileage' => 15000,
                'last_maintenance_date' => Carbon::now()->subMonths(2),
                'next_maintenance_mileage' => 20000,
                'features' => ['GPS', 'Bluetooth', 'Backup Camera', 'AC'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subYear(),
                'purchase_price' => 28000,
            ],
            [
                'vehicle_type_id' => $sedan?->id,
                'license_plate' => 'DEF-5678',
                'make' => 'Honda',
                'model' => 'Accord',
                'year' => 2022,
                'color' => 'Black',
                'vin' => '2HGBH41JXMN109187',
                'registration_expiry' => Carbon::now()->addMonths(10),
                'insurance_expiry' => Carbon::now()->addMonths(8),
                'insurance_policy_number' => 'POL-2024-002',
                'mileage' => 22000,
                'last_maintenance_date' => Carbon::now()->subMonths(1),
                'next_maintenance_mileage' => 25000,
                'features' => ['GPS', 'Bluetooth', 'Sunroof', 'AC'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subYears(2),
                'purchase_price' => 26500,
            ],

            // SUVs
            [
                'vehicle_type_id' => $suv?->id,
                'license_plate' => 'GHI-9012',
                'make' => 'Toyota',
                'model' => 'Highlander',
                'year' => 2023,
                'color' => 'White',
                'vin' => '3HGBH41JXMN109188',
                'registration_expiry' => Carbon::now()->addMonths(11),
                'insurance_expiry' => Carbon::now()->addMonths(9),
                'insurance_policy_number' => 'POL-2024-003',
                'mileage' => 12000,
                'last_maintenance_date' => Carbon::now()->subMonths(3),
                'next_maintenance_mileage' => 15000,
                'features' => ['GPS', 'WiFi Hotspot', 'Bluetooth', 'Third Row', 'AC', 'Parking Sensors'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subMonths(8),
                'purchase_price' => 42000,
            ],
            [
                'vehicle_type_id' => $suv?->id,
                'license_plate' => 'JKL-3456',
                'make' => 'Ford',
                'model' => 'Explorer',
                'year' => 2023,
                'color' => 'Blue',
                'vin' => '4HGBH41JXMN109189',
                'registration_expiry' => Carbon::now()->addMonths(9),
                'insurance_expiry' => Carbon::now()->addMonths(7),
                'insurance_policy_number' => 'POL-2024-004',
                'mileage' => 18000,
                'last_maintenance_date' => Carbon::now()->subWeeks(3),
                'next_maintenance_mileage' => 20000,
                'features' => ['GPS', 'WiFi Hotspot', 'Bluetooth', 'Heated Seats', 'AC'],
                'status' => 'in_service',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subMonths(10),
                'purchase_price' => 45000,
            ],

            // Luxury
            [
                'vehicle_type_id' => $luxury?->id,
                'license_plate' => 'MNO-7890',
                'make' => 'BMW',
                'model' => '5 Series',
                'year' => 2024,
                'color' => 'Black',
                'vin' => '5HGBH41JXMN109190',
                'registration_expiry' => Carbon::now()->addYear(),
                'insurance_expiry' => Carbon::now()->addMonths(11),
                'insurance_policy_number' => 'POL-2024-005',
                'mileage' => 5000,
                'last_maintenance_date' => Carbon::now()->subMonth(),
                'next_maintenance_mileage' => 10000,
                'features' => ['GPS', 'WiFi Hotspot', 'Bluetooth', 'Premium Sound', 'Leather Seats', 'Sunroof', 'AC'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subMonths(4),
                'purchase_price' => 65000,
            ],
            [
                'vehicle_type_id' => $luxury?->id,
                'license_plate' => 'PQR-2345',
                'make' => 'Mercedes-Benz',
                'model' => 'E-Class',
                'year' => 2024,
                'color' => 'Silver',
                'vin' => '6HGBH41JXMN109191',
                'registration_expiry' => Carbon::now()->addYear(),
                'insurance_expiry' => Carbon::now()->addMonths(10),
                'insurance_policy_number' => 'POL-2024-006',
                'mileage' => 3000,
                'last_maintenance_date' => Carbon::now()->subWeeks(2),
                'next_maintenance_mileage' => 10000,
                'features' => ['GPS', 'WiFi Hotspot', 'Bluetooth', 'Premium Sound', 'Leather Seats', 'Massage Seats', 'AC'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subMonths(3),
                'purchase_price' => 72000,
            ],

            // Executive Van
            [
                'vehicle_type_id' => $van?->id,
                'license_plate' => 'STU-6789',
                'make' => 'Mercedes-Benz',
                'model' => 'Sprinter',
                'year' => 2023,
                'color' => 'White',
                'vin' => '7HGBH41JXMN109192',
                'registration_expiry' => Carbon::now()->addMonths(10),
                'insurance_expiry' => Carbon::now()->addMonths(8),
                'insurance_policy_number' => 'POL-2024-007',
                'mileage' => 25000,
                'last_maintenance_date' => Carbon::now()->subMonths(1),
                'next_maintenance_mileage' => 30000,
                'features' => ['GPS', 'WiFi Hotspot', 'Bluetooth', 'USB Ports', 'AC', 'Captain Chairs'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subYear(),
                'purchase_price' => 55000,
            ],

            // Economy
            [
                'vehicle_type_id' => $economy?->id,
                'license_plate' => 'VWX-0123',
                'make' => 'Hyundai',
                'model' => 'Elantra',
                'year' => 2022,
                'color' => 'Gray',
                'vin' => '8HGBH41JXMN109193',
                'registration_expiry' => Carbon::now()->addMonths(7),
                'insurance_expiry' => Carbon::now()->addMonths(5),
                'insurance_policy_number' => 'POL-2024-008',
                'mileage' => 28000,
                'last_maintenance_date' => Carbon::now()->subWeeks(2),
                'next_maintenance_mileage' => 30000,
                'features' => ['GPS', 'Bluetooth', 'AC'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subYears(2),
                'purchase_price' => 18000,
            ],
            [
                'vehicle_type_id' => $economy?->id,
                'license_plate' => 'YZA-4567',
                'make' => 'Kia',
                'model' => 'Forte',
                'year' => 2021,
                'color' => 'White',
                'vin' => '9HGBH41JXMN109194',
                'registration_expiry' => Carbon::now()->addMonths(6),
                'insurance_expiry' => Carbon::now()->addMonths(4),
                'insurance_policy_number' => 'POL-2024-009',
                'mileage' => 35000,
                'last_maintenance_date' => Carbon::now()->subMonths(4),
                'next_maintenance_mileage' => 40000,
                'features' => ['GPS', 'Bluetooth', 'AC'],
                'status' => 'maintenance',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subYears(3),
                'purchase_price' => 16500,
            ],

            // Business Class
            [
                'vehicle_type_id' => $business?->id,
                'license_plate' => 'BCD-8901',
                'make' => 'Audi',
                'model' => 'A6',
                'year' => 2023,
                'color' => 'Black',
                'vin' => '0HGBH41JXMN109195',
                'registration_expiry' => Carbon::now()->addMonths(11),
                'insurance_expiry' => Carbon::now()->addMonths(9),
                'insurance_policy_number' => 'POL-2024-010',
                'mileage' => 10000,
                'last_maintenance_date' => Carbon::now()->subMonths(2),
                'next_maintenance_mileage' => 15000,
                'features' => ['GPS', 'WiFi Hotspot', 'Bluetooth', 'Leather Seats', 'Premium Sound', 'AC'],
                'status' => 'available',
                'is_active' => true,
                'purchase_date' => Carbon::now()->subMonths(6),
                'purchase_price' => 58000,
            ],
        ];

        foreach ($vehicles as $vehicle) {
            if ($vehicle['vehicle_type_id']) {
                Vehicle::create($vehicle);
            }
        }

        $this->command->info('✅ Created ' . count($vehicles) . ' vehicles successfully!');
    }
}
