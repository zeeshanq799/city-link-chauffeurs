<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+44 7700 900001',
                'password' => Hash::make('password123'),
                'address' => '123 Baker Street, London, NW1 6XE',
                'date_of_birth' => '1985-03-15',
                'preferred_payment_method' => 'card',
                'loyalty_points' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+44 7700 900002',
                'password' => Hash::make('password123'),
                'address' => '456 Oxford Street, London, W1D 1BS',
                'date_of_birth' => '1990-07-22',
                'preferred_payment_method' => 'card',
                'loyalty_points' => 320,
                'is_active' => true,
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@example.com',
                'phone' => '+44 7700 900003',
                'password' => Hash::make('password123'),
                'address' => '789 King\'s Road, London, SW3 4LY',
                'date_of_birth' => '1982-11-08',
                'preferred_payment_method' => 'cash',
                'loyalty_points' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'phone' => '+44 7700 900004',
                'password' => Hash::make('password123'),
                'address' => '321 Regent Street, London, W1B 2HW',
                'date_of_birth' => '1995-02-14',
                'preferred_payment_method' => 'wallet',
                'loyalty_points' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => '+44 7700 900005',
                'password' => Hash::make('password123'),
                'address' => '654 Bond Street, London, W1S 1DW',
                'date_of_birth' => '1978-09-30',
                'preferred_payment_method' => 'card',
                'loyalty_points' => 500,
                'notes' => 'VIP customer - prefers luxury vehicles',
                'is_active' => true,
            ],
            [
                'name' => 'Inactive Customer',
                'email' => 'inactive@example.com',
                'phone' => '+44 7700 900006',
                'password' => Hash::make('password123'),
                'address' => null,
                'date_of_birth' => null,
                'preferred_payment_method' => 'card',
                'loyalty_points' => 0,
                'is_active' => false,
                'notes' => 'Account suspended due to payment issues',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info('✅ Created ' . count($customers) . ' test customers');
    }
}

