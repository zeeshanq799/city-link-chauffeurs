<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Vehicle Type Permissions
            'vehicle-types.view',
            'vehicle-types.create',
            'vehicle-types.edit',
            'vehicle-types.delete',
            
            // Vehicle Permissions
            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',
            'vehicles.delete',
            
            // Driver Permissions
            'drivers.view',
            'drivers.create',
            'drivers.edit',
            'drivers.delete',
            
            // Booking Permissions
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'bookings.delete',
            
            // Payment Permissions
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
            
            // Customer Permissions
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',
            
            // User Management Permissions
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Role Management Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            
            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $operatorRole = Role::firstOrCreate(['name' => 'Operator']);

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign specific permissions to Manager
        $managerRole->syncPermissions([
            'dashboard.view',
            'vehicle-types.view',
            'vehicle-types.create',
            'vehicle-types.edit',
            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',
            'drivers.view',
            'drivers.create',
            'drivers.edit',
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'payments.view',
            'customers.view',
            'customers.edit',
        ]);

        // Assign specific permissions to Operator
        $operatorRole->syncPermissions([
            'dashboard.view',
            'vehicle-types.view',
            'vehicles.view',
            'drivers.view',
            'bookings.view',
            'bookings.create',
            'bookings.edit',
            'customers.view',
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@citylinkdrivers.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role
        $admin->assignRole('Super Admin');

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: admin@citylinkdrivers.com');
        $this->command->info('🔑 Password: Admin@123');
        $this->command->info('✅ Created 3 roles: Super Admin, Manager, Operator');
        $this->command->info('✅ Created ' . count($permissions) . ' permissions');
    }
}
