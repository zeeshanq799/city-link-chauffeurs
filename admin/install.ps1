# City Link Chauffeurs - Windows Installation Script
# Run this in PowerShell as Administrator

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "City Link Chauffeurs - Laravel Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if Composer is installed
Write-Host "Checking Composer..." -ForegroundColor Yellow
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Composer is not installed!" -ForegroundColor Red
    Write-Host "Please install Composer from: https://getcomposer.org/download/" -ForegroundColor Yellow
    exit 1
}
Write-Host "Composer found" -ForegroundColor Green

# Check if PHP is installed
Write-Host "Checking PHP..." -ForegroundColor Yellow
if (!(Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: PHP is not installed!" -ForegroundColor Red
    Write-Host "Please install PHP 8.1 or higher" -ForegroundColor Yellow
    exit 1
}

$phpVersion = php -v
Write-Host "PHP found" -ForegroundColor Green
Write-Host ""

# Navigate to admin directory
$adminPath = "d:\xampp8.2\htdocs\city-link-chauffeurs\admin"
Set-Location $adminPath

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Step 1: Installing Laravel..." -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
composer create-project laravel/laravel .

if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Laravel installation failed!" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Step 2: Installing Filament Admin Panel..." -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
composer require filament/filament:"^3.2" -W

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Step 3: Installing Laravel Sanctum..." -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
php artisan install:api --quiet

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Step 4: Installing Additional Packages..." -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan

$packages = @(
    "stripe/stripe-php",
    "intervention/image",
    "barryvdh/laravel-dompdf",
    "maatwebsite/excel",
    "spatie/laravel-permission",
    "spatie/laravel-medialibrary"
)

foreach ($package in $packages) {
    Write-Host "Installing $package..." -ForegroundColor Cyan
    composer require $package --quiet
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Step 5: Setting up Filament..." -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
php artisan filament:install --panels

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Installation Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "1. Edit .env file with your database credentials" -ForegroundColor White
Write-Host "2. Create database: city_link_chauffeurs" -ForegroundColor White
Write-Host "3. Run: php artisan migrate" -ForegroundColor White
Write-Host "4. Create admin user: php artisan make:filament-user" -ForegroundColor White
Write-Host "5. Start server: php artisan serve" -ForegroundColor White
Write-Host "6. Visit: http://localhost:8000/admin" -ForegroundColor White
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan

Read-Host "Press Enter to exit"
