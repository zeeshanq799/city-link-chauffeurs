# City Link Chauffeurs - Laravel Admin Panel Setup Guide

## Step 1: Install Laravel

Since we have the frontend already in this directory, we'll create a Laravel backend in a separate folder structure.

### Option A: Create Backend in Subfolder (Recommended)

```bash
# Navigate to your project root
cd d:\xampp8.2\htdocs\city-link-chauffeurs

# Create a backend folder and install Laravel
composer create-project laravel/laravel backend

# After installation, move into backend directory
cd backend
```

### Option B: Fresh Installation in New Directory

```bash
# Go back to htdocs
cd d:\xampp8.2\htdocs

# Create new Laravel project
composer create-project laravel/laravel city-link-backend

# Move into the new directory
cd city-link-backend
```

## Step 2: Configure Environment

After Laravel installation, configure your `.env` file:

```env
APP_NAME="City Link Chauffeurs"
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=city_link_chauffeurs
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Payment Gateways
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=

PAYPAL_CLIENT_ID=
PAYPAL_SECRET=
PAYPAL_MODE=sandbox

# Google Maps
GOOGLE_MAPS_API_KEY=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@citylinkchauffers.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Step 3: Create Database

1. Open phpMyAdmin or MySQL command line
2. Create the database:

```sql
CREATE DATABASE city_link_chauffeurs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Step 4: Install Required Packages

```bash
# Install Filament (Admin Panel)
composer require filament/filament:"^3.2" -W

# Install Laravel Sanctum for API authentication
php artisan install:api

# Install additional packages
composer require stripe/stripe-php
composer require laravel/cashier
composer require intervention/image
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel

# Install Spatie packages for permissions and media
composer require spatie/laravel-permission
composer require spatie/laravel-medialibrary
```

## Step 5: Install Filament Admin Panel

```bash
# Install Filament panels
php artisan filament:install --panels

# Create admin user
php artisan make:filament-user
# Enter: admin@citylinkchauffers.com / password (or your choice)
```

## Next Steps

After completing the above steps, we'll proceed with:
1. Creating all database migrations
2. Setting up models and relationships
3. Building Filament resources (CRUD interfaces)
4. Creating API endpoints
5. Integrating with frontend

## Verification

To verify installation:

```bash
# Start Laravel development server
php artisan serve

# Visit admin panel at:
# http://localhost:8000/admin

# API will be at:
# http://localhost:8000/api
```

---

**Ready to proceed?** Let me know when you've completed these installation steps, and I'll create the database migrations!
