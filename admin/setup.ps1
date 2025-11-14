# 🚀 Complete Setup Script for Vehicle Types Module
# Run this in a FRESH PowerShell terminal

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  City Link Chauffeurs - Setup Script  " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Change to admin directory
Set-Location "d:\xampp8.2\htdocs\city-link-chauffeurs\admin"

# Step 1: Create Admin User
Write-Host "Step 1: Creating Admin User..." -ForegroundColor Yellow
php artisan db:seed --class=AdminUserSeeder
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Admin user created!" -ForegroundColor Green
} else {
    Write-Host "⚠️  Admin user might already exist or needs manual creation" -ForegroundColor Yellow
}
Write-Host ""

# Step 2: Run Migrations
Write-Host "Step 2: Running Migrations..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Migrations completed!" -ForegroundColor Green
} else {
    Write-Host "❌ Migration failed" -ForegroundColor Red
    exit 1
}
Write-Host ""

# Step 3: Seed Vehicle Types
Write-Host "Step 3: Seeding Vehicle Types..." -ForegroundColor Yellow
php artisan db:seed --class=VehicleTypeSeeder
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Vehicle types created!" -ForegroundColor Green
} else {
    Write-Host "❌ Seeding failed" -ForegroundColor Red
}
Write-Host ""

# Step 3b: Seed Vehicles
Write-Host "Step 3b: Seeding Vehicles..." -ForegroundColor Yellow
php artisan db:seed --class=VehicleSeeder
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Vehicles created!" -ForegroundColor Green
} else {
    Write-Host "❌ Seeding failed" -ForegroundColor Red
}
Write-Host ""

# Step 4: Clear Cache
Write-Host "Step 4: Clearing Cache..." -ForegroundColor Yellow
php artisan optimize:clear
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Cache cleared!" -ForegroundColor Green
}
Write-Host ""

# Step 5: Create Storage Link
Write-Host "Step 5: Creating Storage Link..." -ForegroundColor Yellow
php artisan storage:link
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Storage link created!" -ForegroundColor Green
}
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete! 🎉                   " -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Admin Login Credentials:" -ForegroundColor Green
Write-Host "  Email: admin@citylinkdrivers.com" -ForegroundColor White
Write-Host "  Password: Admin@123" -ForegroundColor White
Write-Host ""
Write-Host "To start the development server, run:" -ForegroundColor Yellow
Write-Host "  php artisan serve" -ForegroundColor White
Write-Host ""
Write-Host "Then visit: http://localhost:8000/admin" -ForegroundColor Cyan
Write-Host ""
