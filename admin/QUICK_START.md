# Quick Start Guide

## 1. Enable PHP intl Extension ⚠️ REQUIRED

```
1. Open: C:\xampp\php\php.ini
2. Find: ;extension=intl
3. Change to: extension=intl
4. Save file
5. Restart Apache in XAMPP
```

## 2. Setup Database

### Option A: Keep SQLite (Current - Good for testing)
No changes needed. Database file is already at `database/database.sqlite`

### Option B: Switch to MySQL (Recommended for production)

**Create database in phpMyAdmin:**
```sql
CREATE DATABASE city_link_chauffeurs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Edit admin/.env:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=city_link_chauffeurs
DB_USERNAME=root
DB_PASSWORD=
```

**Run migrations:**
```bash
cd admin
php artisan migrate:fresh
```

## 3. Install Filament Panel

```bash
cd d:\xampp8.2\htdocs\city-link-chauffeurs\admin
php artisan filament:install --panels
```

## 4. Create Admin User

```bash
php artisan make:filament-user
```

Enter:
- Name: Admin
- Email: admin@example.com  
- Password: (your secure password)

## 5. Start Server

```bash
php artisan serve
```

## 6. Access Admin Panel

Open browser: **http://localhost:8000/admin**

Login with credentials from step 4.

---

## What You'll See

After login, you'll see the Filament dashboard with:
- Dashboard (home page)
- No resources yet (we'll create them next!)

---

## Next: Let's Build!

Once you're logged in, we can start creating:

1. **Vehicle Types** Resource (Sedan, SUV, Luxury, etc.)
2. **Vehicles** Resource (Manage fleet)
3. **Drivers** Resource (Driver management)
4. **Bookings** Resource (Booking management)

Just let me know when you're ready, and I'll create the first migration and Filament resource!

---

## Common Commands Reference

```bash
# Navigate to admin directory
cd d:\xampp8.2\htdocs\city-link-chauffeurs\admin

# Start development server
php artisan serve

# Stop server: Press Ctrl+C

# Create new admin resource
php artisan make:filament-resource VehicleType

# Run migrations
php artisan migrate

# Clear cache (if needed)
php artisan optimize:clear
```
