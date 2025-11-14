#!/bin/bash

# City Link Chauffeurs - Laravel Installation Script
# Run this script after installing Laravel

echo "=================================="
echo "City Link Chauffeurs Setup"
echo "=================================="

# Check if composer is installed
if ! command -v composer &> /dev/null
then
    echo "Error: Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null
then
    echo "Error: PHP is not installed. Please install PHP 8.1 or higher."
    exit 1
fi

echo "Step 1: Installing Laravel..."
composer create-project laravel/laravel backend

cd backend

echo "Step 2: Installing Filament Admin Panel..."
composer require filament/filament:"^3.2" -W

echo "Step 3: Installing Laravel Sanctum for API..."
php artisan install:api

echo "Step 4: Installing additional packages..."
composer require stripe/stripe-php
composer require intervention/image
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary

echo "Step 5: Installing Filament Panels..."
php artisan filament:install --panels

echo "Step 6: Publishing configuration files..."
php artisan vendor:publish --tag=filament-config
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

echo "=================================="
echo "Setup Complete!"
echo "=================================="
echo ""
echo "Next Steps:"
echo "1. Configure your .env file with database credentials"
echo "2. Run: php artisan migrate"
echo "3. Run: php artisan make:filament-user (to create admin)"
echo "4. Run: php artisan serve"
echo "5. Visit: http://localhost:8000/admin"
echo ""
echo "=================================="
