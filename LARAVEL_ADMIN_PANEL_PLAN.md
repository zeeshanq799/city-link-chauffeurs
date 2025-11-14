# City Link Chauffeurs - Laravel Admin Panel Development Plan

## Project Overview
A comprehensive Laravel-based admin panel for managing the City Link Chauffeurs car booking system, including vehicles, packages, services, bookings, drivers, customers, and all business operations.

**Frontend:** HTML/CSS/Bootstrap 5 (Already Completed)  
**Backend:** Laravel 10+ with MySQL  
**Admin Panel:** Laravel + AdminLTE or Filament  
**Payment Integration:** Stripe/PayPal  
**Maps:** Google Maps API  

---

## TABLE OF CONTENTS
1. [System Architecture](#system-architecture)
2. [Database Schema](#database-schema)
3. [Admin Panel Modules](#admin-panel-modules)
4. [API Endpoints](#api-endpoints)
5. [Frontend Integration](#frontend-integration)
6. [Third-party Integrations](#third-party-integrations)
7. [Implementation Roadmap](#implementation-roadmap)

---

## SYSTEM ARCHITECTURE

### Technology Stack
```
Backend Framework: Laravel 10+
Database: MySQL 8.0+
Admin Panel: Filament v3 (Recommended) or Laravel Backpack
Authentication: Laravel Sanctum (API) + Laravel Breeze/Jetstream (Web)
Payment Gateway: Stripe + PayPal
Email Service: Laravel Mail + Mailgun
Maps API: Google Maps Platform
Storage: Laravel Storage (S3 or Local)
```

### Application Structure
```
city-link-chauffeurs/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin panel controllers
│   │   │   ├── Api/             # API controllers for frontend
│   │   │   └── Frontend/        # Web controllers
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Services/                # Business logic
│   ├── Repositories/            # Data access layer
│   └── Notifications/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin panel views
│   │   └── frontend/           # Customer-facing views
│   └── js/
└── routes/
    ├── web.php
    ├── api.php
    └── admin.php
```

---

## DATABASE SCHEMA

### 1. **users** - Customer/Driver/Admin Accounts
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin', 'customer', 'driver', 'support') DEFAULT 'customer',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    phone VARCHAR(20),
    phone_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    profile_photo VARCHAR(255),
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    member_tier ENUM('standard', 'silver', 'gold', 'platinum') DEFAULT 'standard',
    total_bookings INT DEFAULT 0,
    total_spent DECIMAL(10, 2) DEFAULT 0,
    average_rating DECIMAL(3, 2) DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_phone (phone),
    INDEX idx_role (role)
);
```

### 2. **user_addresses** - Saved Addresses
```sql
CREATE TABLE user_addresses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    label VARCHAR(50), -- 'Home', 'Work', 'Other'
    address_line_1 VARCHAR(255) NOT NULL,
    address_line_2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    country VARCHAR(100) DEFAULT 'Pakistan',
    postal_code VARCHAR(20),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    special_instructions TEXT,
    is_default_pickup BOOLEAN DEFAULT FALSE,
    is_default_dropoff BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);
```

### 3. **drivers** - Driver Profiles
```sql
CREATE TABLE drivers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    license_number VARCHAR(50) UNIQUE NOT NULL,
    license_expiry DATE NOT NULL,
    license_photo VARCHAR(255),
    experience_years INT DEFAULT 0,
    languages JSON, -- ['English', 'Urdu', 'Punjabi']
    bio TEXT,
    is_available BOOLEAN DEFAULT TRUE,
    current_latitude DECIMAL(10, 8),
    current_longitude DECIMAL(11, 8),
    total_trips INT DEFAULT 0,
    average_rating DECIMAL(3, 2) DEFAULT 0,
    acceptance_rate DECIMAL(5, 2) DEFAULT 0,
    cancellation_rate DECIMAL(5, 2) DEFAULT 0,
    is_verified BOOLEAN DEFAULT FALSE,
    verified_at TIMESTAMP NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_is_available (is_available),
    INDEX idx_status (status)
);
```

### 4. **vehicle_types** - Vehicle Categories
```sql
CREATE TABLE vehicle_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL, -- 'Business Sedan', 'Luxury SUV', etc.
    slug VARCHAR(100) UNIQUE NOT NULL,
    category ENUM('sedan', 'suv', 'van', 'luxury', 'minibus') NOT NULL,
    description TEXT,
    max_passengers INT NOT NULL,
    max_luggage INT NOT NULL,
    base_fare DECIMAL(10, 2) NOT NULL,
    per_km_rate DECIMAL(10, 2) NOT NULL,
    per_minute_rate DECIMAL(10, 2) NOT NULL,
    hourly_rate DECIMAL(10, 2),
    image VARCHAR(255),
    features JSON, -- ['WiFi', 'AC', 'Leather Seats', 'GPS']
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
);
```

### 5. **vehicles** - Individual Vehicles
```sql
CREATE TABLE vehicles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vehicle_type_id BIGINT UNSIGNED NOT NULL,
    make VARCHAR(100) NOT NULL, -- 'BMW', 'Mercedes', etc.
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    color VARCHAR(50),
    license_plate VARCHAR(50) UNIQUE NOT NULL,
    vin VARCHAR(100),
    registration_expiry DATE,
    insurance_expiry DATE,
    last_service_date DATE,
    next_service_date DATE,
    mileage INT DEFAULT 0,
    condition ENUM('excellent', 'good', 'fair') DEFAULT 'excellent',
    images JSON, -- Array of image URLs
    features JSON, -- ['WiFi', 'Phone Charger', 'Water Bottles']
    status ENUM('available', 'in_service', 'maintenance', 'inactive') DEFAULT 'available',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id) ON DELETE RESTRICT,
    INDEX idx_vehicle_type_id (vehicle_type_id),
    INDEX idx_status (status)
);
```

### 6. **driver_vehicle_assignments** - Driver-Vehicle Mapping
```sql
CREATE TABLE driver_vehicle_assignments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    driver_id BIGINT UNSIGNED NOT NULL,
    vehicle_id BIGINT UNSIGNED NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    assigned_by BIGINT UNSIGNED, -- Admin user ID
    is_current BOOLEAN DEFAULT TRUE,
    notes TEXT,
    
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_driver_vehicle (driver_id, vehicle_id),
    INDEX idx_is_current (is_current)
);
```

### 7. **services** - Service Types
```sql
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category ENUM('airport', 'corporate', 'hourly', 'point_to_point', 'wedding', 'event') NOT NULL,
    description TEXT,
    short_description TEXT,
    features JSON, -- ['Flight Tracking', 'Meet & Greet']
    image VARCHAR(255),
    icon VARCHAR(100),
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_category (category),
    INDEX idx_is_active (is_active),
    INDEX idx_is_featured (is_featured)
);
```

### 8. **packages** - Package Deals
```sql
CREATE TABLE packages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    service_id BIGINT UNSIGNED,
    category ENUM('airport', 'wedding', 'corporate', 'city_tour', 'group', 'custom') NOT NULL,
    description TEXT,
    short_description TEXT,
    duration_hours INT, -- Estimated duration
    max_passengers INT,
    max_luggage INT,
    included_features JSON, -- ['Professional Chauffeur', 'WiFi', 'Water Bottles']
    base_price DECIMAL(10, 2) NOT NULL,
    discount_percentage DECIMAL(5, 2) DEFAULT 0,
    final_price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    images JSON, -- Multiple images
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    valid_from DATE,
    valid_until DATE,
    max_bookings INT, -- Limit bookings
    current_bookings INT DEFAULT 0,
    terms_conditions TEXT,
    cancellation_policy TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    INDEX idx_category (category),
    INDEX idx_is_active (is_active),
    INDEX idx_is_featured (is_featured)
);
```

### 9. **destinations** - Popular Destinations/Routes
```sql
CREATE TABLE destinations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('airport', 'hotel', 'landmark', 'business_district', 'residential', 'custom') NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100),
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    is_popular BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_type (type),
    INDEX idx_is_popular (is_popular)
);
```

### 10. **bookings** - Main Booking Table
```sql
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_number VARCHAR(50) UNIQUE NOT NULL, -- BK-2025-00142
    user_id BIGINT UNSIGNED NOT NULL,
    service_id BIGINT UNSIGNED,
    package_id BIGINT UNSIGNED,
    vehicle_type_id BIGINT UNSIGNED NOT NULL,
    vehicle_id BIGINT UNSIGNED,
    driver_id BIGINT UNSIGNED,
    
    -- Booking Details
    service_type ENUM('point_to_point', 'hourly', 'airport', 'corporate', 'package') NOT NULL,
    
    -- Pickup Information
    pickup_address TEXT NOT NULL,
    pickup_city VARCHAR(100),
    pickup_latitude DECIMAL(10, 8),
    pickup_longitude DECIMAL(11, 8),
    pickup_datetime DATETIME NOT NULL,
    
    -- Dropoff Information
    dropoff_address TEXT,
    dropoff_city VARCHAR(100),
    dropoff_latitude DECIMAL(10, 8),
    dropoff_longitude DECIMAL(11, 8),
    
    -- Stop Points (for multiple stops)
    stops JSON, -- [{'address': '...', 'lat': ..., 'lng': ...}]
    
    -- Trip Details
    estimated_distance DECIMAL(10, 2), -- in km
    estimated_duration INT, -- in minutes
    actual_distance DECIMAL(10, 2),
    actual_duration INT,
    
    -- Pricing
    base_fare DECIMAL(10, 2) NOT NULL,
    distance_fare DECIMAL(10, 2) DEFAULT 0,
    time_fare DECIMAL(10, 2) DEFAULT 0,
    toll_charges DECIMAL(10, 2) DEFAULT 0,
    parking_charges DECIMAL(10, 2) DEFAULT 0,
    waiting_charges DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    promo_code VARCHAR(50),
    subtotal DECIMAL(10, 2) NOT NULL,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2) NOT NULL,
    
    -- Special Requirements
    passengers_count INT DEFAULT 1,
    luggage_count INT DEFAULT 0,
    special_instructions TEXT,
    
    -- Status & Timestamps
    status ENUM('pending', 'confirmed', 'assigned', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    cancelled_by ENUM('customer', 'driver', 'admin'),
    cancellation_reason TEXT,
    confirmed_at TIMESTAMP NULL,
    driver_assigned_at TIMESTAMP NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE SET NULL,
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id) ON DELETE RESTRICT,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL,
    FOREIGN KEY (driver_id) REFERENCES drivers(id) ON DELETE SET NULL,
    
    INDEX idx_user_id (user_id),
    INDEX idx_booking_number (booking_number),
    INDEX idx_status (status),
    INDEX idx_pickup_datetime (pickup_datetime),
    INDEX idx_driver_id (driver_id)
);
```

### 11. **payments** - Payment Transactions
```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    payment_method_id BIGINT UNSIGNED,
    
    amount DECIMAL(10, 2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method ENUM('card', 'cash', 'wallet', 'bank_transfer') NOT NULL,
    payment_gateway ENUM('stripe', 'paypal', 'square', 'cash') NOT NULL,
    
    transaction_id VARCHAR(255), -- From payment gateway
    gateway_response JSON,
    
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded', 'partially_refunded') DEFAULT 'pending',
    
    paid_at TIMESTAMP NULL,
    refunded_at TIMESTAMP NULL,
    refund_amount DECIMAL(10, 2),
    refund_reason TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_booking_id (booking_id),
    INDEX idx_status (status),
    INDEX idx_transaction_id (transaction_id)
);
```

### 12. **payment_methods** - Stored Payment Methods
```sql
CREATE TABLE payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('card', 'bank_account') NOT NULL,
    gateway ENUM('stripe', 'paypal') NOT NULL,
    gateway_payment_method_id VARCHAR(255), -- stripe_pm_xxx
    
    -- Card Details (last 4 digits only)
    card_brand VARCHAR(50), -- 'Visa', 'Mastercard'
    card_last4 VARCHAR(4),
    card_exp_month INT,
    card_exp_year INT,
    
    is_default BOOLEAN DEFAULT FALSE,
    is_verified BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);
```

### 13. **ratings_reviews** - Ratings & Reviews
```sql
CREATE TABLE ratings_reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    reviewer_id BIGINT UNSIGNED NOT NULL, -- User who gives rating
    reviewee_id BIGINT UNSIGNED NOT NULL, -- Driver being rated
    reviewer_type ENUM('customer', 'driver') NOT NULL,
    
    overall_rating DECIMAL(3, 2) NOT NULL, -- 1.00 to 5.00
    
    -- Category Ratings
    driving_skills DECIMAL(3, 2),
    vehicle_cleanliness DECIMAL(3, 2),
    professionalism DECIMAL(3, 2),
    communication DECIMAL(3, 2),
    punctuality DECIMAL(3, 2),
    
    review_text TEXT,
    photos JSON, -- Array of photo URLs
    
    is_approved BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    helpful_count INT DEFAULT 0,
    
    admin_response TEXT,
    responded_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewee_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_reviewee_id (reviewee_id)
);
```

### 14. **promo_codes** - Discount Codes
```sql
CREATE TABLE promo_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percentage', 'fixed', 'free_ride') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    
    description TEXT,
    terms_conditions TEXT,
    
    min_booking_amount DECIMAL(10, 2) DEFAULT 0,
    max_discount_amount DECIMAL(10, 2),
    
    usage_limit INT, -- Total times code can be used
    usage_per_user INT DEFAULT 1,
    current_usage_count INT DEFAULT 0,
    
    applicable_services JSON, -- ['airport', 'corporate']
    applicable_vehicle_types JSON,
    
    valid_from DATETIME NOT NULL,
    valid_until DATETIME NOT NULL,
    
    is_active BOOLEAN DEFAULT TRUE,
    
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_code (code),
    INDEX idx_valid_dates (valid_from, valid_until)
);
```

### 15. **promo_code_usage** - Track Promo Usage
```sql
CREATE TABLE promo_code_usage (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    promo_code_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    booking_id BIGINT UNSIGNED NOT NULL,
    discount_amount DECIMAL(10, 2) NOT NULL,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (promo_code_id) REFERENCES promo_codes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_promo_user (promo_code_id, user_id)
);
```

### 16. **notifications** - User Notifications
```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(100) NOT NULL, -- 'booking_confirmed', 'driver_assigned', etc.
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON, -- Additional data
    
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_read_at (read_at)
);
```

### 17. **settings** - System Settings
```sql
CREATE TABLE settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key VARCHAR(255) UNIQUE NOT NULL,
    value TEXT,
    type ENUM('string', 'integer', 'boolean', 'json', 'text') DEFAULT 'string',
    group_name VARCHAR(100), -- 'general', 'pricing', 'email', etc.
    description TEXT,
    is_public BOOLEAN DEFAULT FALSE, -- Accessible via API
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_group_name (group_name)
);
```

### 18. **activity_logs** - Audit Trail
```sql
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    action VARCHAR(255) NOT NULL, -- 'created', 'updated', 'deleted'
    model_type VARCHAR(255) NOT NULL, -- 'Booking', 'User', 'Vehicle'
    model_id BIGINT UNSIGNED NOT NULL,
    changes JSON, -- Old and new values
    ip_address VARCHAR(45),
    user_agent TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_model (model_type, model_id)
);
```

---

## ADMIN PANEL MODULES

### 1. **Dashboard Module**
- **Overview Statistics**
  - Total Bookings (Today, This Week, This Month, All Time)
  - Revenue Statistics (Daily, Weekly, Monthly, Yearly)
  - Active Drivers Count
  - Available Vehicles Count
  - Pending Bookings
  - Customer Statistics

- **Charts & Graphs**
  - Revenue Trend Chart (Line/Bar Chart)
  - Bookings by Service Type (Pie Chart)
  - Popular Vehicles (Bar Chart)
  - Peak Booking Hours (Heat Map)
  - Top Performing Drivers

- **Recent Activity**
  - Latest Bookings (Real-time)
  - Recent Customer Registrations
  - Recent Driver Signups
  - Recent Reviews

- **Quick Actions**
  - Create New Booking
  - Add New Vehicle
  - Add New Driver
  - View Pending Approvals

---

### 2. **Booking Management Module**

#### A. **All Bookings**
- **List View Features:**
  - Searchable table with filters
  - Filter by: Status, Service Type, Date Range, Driver, Vehicle, Customer
  - Sortable columns
  - Bulk actions (Cancel, Export, Assign Driver)
  - Pagination
  - Quick view modal

- **Booking Details Page:**
  - Complete trip information
  - Customer details with contact options
  - Driver & vehicle information
  - Route map with pickup/dropoff markers
  - Payment breakdown
  - Status timeline
  - Action buttons: Edit, Cancel, Assign Driver, Refund

- **Actions:**
  - Create Manual Booking
  - Edit Booking Details
  - Assign/Reassign Driver
  - Cancel Booking (with reason)
  - Process Refund
  - Generate Invoice/Receipt
  - Send Notifications
  - View Booking Timeline

#### B. **Booking Calendar**
- Full calendar view with bookings
- Color-coded by status
- Filter by driver, vehicle, service type
- Click to view/edit booking
- Drag and drop to reschedule

#### C. **Booking Analytics**
- Booking trends over time
- Revenue analysis
- Peak hours/days analysis
- Service type popularity
- Average booking value
- Cancellation rate analysis

---

### 3. **Vehicle Management Module**

#### A. **Vehicle Types**
- **CRUD Operations:**
  - Create/Edit/Delete vehicle types
  - Set pricing: Base fare, Per KM, Per minute, Hourly rate
  - Set capacity: Max passengers, Max luggage
  - Upload images
  - Manage features/amenities
  - Set display order
  - Activate/Deactivate

- **List View:**
  - All vehicle types with thumbnail
  - Status (Active/Inactive)
  - Pricing overview
  - Number of vehicles of this type
  - Edit/Delete actions

#### B. **Vehicles (Fleet)**
- **List View:**
  - All vehicles with photos
  - Make, Model, Year, License Plate
  - Vehicle Type
  - Status (Available, In Service, Maintenance, Inactive)
  - Assigned Driver (if any)
  - Last service date
  - Mileage
  - Quick actions

- **Add/Edit Vehicle:**
  - Form fields: Make, Model, Year, Color
  - Vehicle Type selection
  - License Plate, VIN
  - Registration expiry, Insurance expiry
  - Service schedule
  - Current mileage
  - Upload multiple images
  - Features checklist
  - Status selection

- **Vehicle Details:**
  - Complete vehicle information
  - Service history
  - Assignment history (which drivers)
  - Booking history
  - Maintenance logs
  - Document uploads (Insurance, Registration)

- **Vehicle Maintenance:**
  - Schedule maintenance
  - Mark vehicle as "In Maintenance"
  - Maintenance log with cost
  - Set next service date

---

### 4. **Driver Management Module**

#### A. **All Drivers**
- **List View:**
  - Driver photo, name, contact
  - License number
  - Rating
  - Total trips
  - Status (Active, Inactive, Suspended)
  - Verification status
  - Availability (Online/Offline)
  - Assigned vehicle
  - Quick actions

- **Add/Edit Driver:**
  - Personal info form (Name, Email, Phone, DOB)
  - License information
  - Experience years
  - Languages spoken
  - Bio
  - Upload license photo
  - Upload profile photo
  - Set status

- **Driver Details:**
  - Complete profile
  - License & verification status
  - Assigned vehicle
  - Performance statistics:
    - Total trips
    - Total earnings
    - Average rating
    - Acceptance rate
    - Cancellation rate
  - Recent bookings
  - Reviews received
  - Document verification

- **Driver Verification:**
  - Verify license
  - Verify identity documents
  - Background check status
  - Approve/Reject driver

- **Driver Assignments:**
  - Assign vehicle to driver
  - View assignment history
  - Unassign vehicle

#### B. **Driver Analytics**
- Top performing drivers
- Driver earnings report
- Driver ratings overview
- Acceptance/Cancellation rates
- Active vs Inactive drivers

---

### 5. **Customer Management Module**

#### A. **All Customers**
- **List View:**
  - Customer name, email, phone
  - Member tier
  - Total bookings
  - Total spent
  - Average rating (as passenger)
  - Registration date
  - Status (Active/Inactive)
  - Last login
  - Quick actions

- **Customer Details:**
  - Personal information
  - Contact details
  - Saved addresses
  - Booking history
  - Payment methods (masked)
  - Reviews given
  - Activity log

- **Customer Actions:**
  - Edit customer details
  - Reset password
  - Activate/Deactivate account
  - Upgrade member tier
  - Send email/SMS
  - View login history

#### B. **Customer Analytics**
- New customers over time
- Customer retention rate
- Customer lifetime value
- Member tier distribution
- Top spending customers

---

### 6. **Service Management Module**

#### A. **Services**
- **List View:**
  - Service name, category, icon
  - Status (Active/Inactive)
  - Featured status
  - Display order
  - Actions

- **Add/Edit Service:**
  - Service name, slug
  - Category selection
  - Description (rich text editor)
  - Short description
  - Features (repeater field)
  - Upload image
  - Icon selection
  - Featured checkbox
  - Display order
  - SEO fields (meta title, description)

- **Service Details:**
  - Complete information
  - Bookings using this service
  - Revenue generated

---

### 7. **Package Management Module**

#### A. **Packages**
- **List View:**
  - Package name, category
  - Base price, discount, final price
  - Duration
  - Status (Active/Inactive)
  - Featured status
  - Validity period
  - Total bookings
  - Actions

- **Add/Edit Package:**
  - Package name, slug
  - Associated service
  - Category selection
  - Description (rich text editor)
  - Short description
  - Duration in hours
  - Max passengers, max luggage
  - Included features (repeater)
  - Pricing:
    - Base price
    - Discount percentage
    - Final price (auto-calculated)
  - Upload images (gallery)
  - Featured checkbox
  - Display order
  - Validity dates
  - Max bookings limit
  - Terms & conditions
  - Cancellation policy

- **Package Details:**
  - Complete information
  - Booking statistics
  - Revenue generated

---

### 8. **Destination Management Module**

#### A. **Destinations**
- **List View:**
  - Destination name, type
  - Address, city
  - Popular status
  - Status (Active/Inactive)
  - Actions

- **Add/Edit Destination:**
  - Name, type
  - Full address with map picker
  - Latitude & longitude (auto-filled)
  - City
  - Description
  - Upload image
  - Popular checkbox (Featured destinations)
  - Active status

- **Popular Routes:**
  - Track most frequent pickup-dropoff combinations
  - Analytics on popular destinations

---

### 9. **Payment Management Module**

#### A. **Transactions**
- **List View:**
  - Transaction ID, booking number
  - Customer name
  - Amount, currency
  - Payment method
  - Gateway
  - Status
  - Date
  - Actions

- **Transaction Details:**
  - Complete payment information
  - Gateway response data
  - Related booking
  - Customer details
  - Refund information (if applicable)

- **Refund Processing:**
  - Select transaction
  - Refund amount (full/partial)
  - Refund reason
  - Process refund button
  - Track refund status

#### B. **Payment Methods**
- **Manage stored payment methods**
- View customer saved cards (last 4 digits only)
- Remove payment methods

#### C. **Payment Reports**
- Daily/Weekly/Monthly revenue
- Payment method breakdown
- Gateway comparison
- Failed transaction analysis
- Refund statistics

---

### 10. **Promo Code Management Module**

#### A. **Promo Codes**
- **List View:**
  - Code, type, value
  - Usage (current/limit)
  - Valid from/until
  - Status
  - Actions

- **Add/Edit Promo Code:**
  - Code (auto-generate option)
  - Type (Percentage, Fixed, Free Ride)
  - Value
  - Description
  - Terms & conditions
  - Min booking amount
  - Max discount amount
  - Usage limits:
    - Total usage limit
    - Per user limit
  - Applicable services (multi-select)
  - Applicable vehicle types (multi-select)
  - Validity dates (from/until)
  - Active status

- **Promo Code Analytics:**
  - Usage statistics
  - Revenue impact
  - Popular promo codes
  - User redemption rate

---

### 11. **Rating & Review Management Module**

#### A. **All Reviews**
- **List View:**
  - Reviewer name, reviewee (driver)
  - Booking number
  - Overall rating
  - Review text (truncated)
  - Date
  - Status (Approved, Pending, Reported)
  - Actions

- **Review Details:**
  - Complete review with all ratings
  - Photos uploaded
  - Booking details
  - Approve/Reject
  - Feature review
  - Admin response
  - Hide/Report

- **Review Moderation:**
  - Approve/Reject reviews
  - Respond to reviews
  - Feature best reviews
  - Handle reported reviews

#### B. **Rating Analytics**
- Average ratings over time
- Driver rating distribution
- Most reviewed drivers
- Sentiment analysis

---

### 12. **User & Role Management Module**

#### A. **Admin Users**
- **List View:**
  - Name, email, role
  - Last login
  - Status
  - Actions

- **Add/Edit Admin:**
  - Name, email, password
  - Role assignment
  - Permissions
  - Status

#### B. **Roles & Permissions**
- **Predefined Roles:**
  - Super Admin (full access)
  - Admin (most access)
  - Accountant (payment access)
  - Fleet Manager (vehicle & driver management)

- **Custom Permissions:**
  - Module-level permissions
  - CRUD operation controls
  - View reports permission
  - Access settings permission

---

### 13. **Reports & Analytics Module**

#### A. **Business Reports**
- Revenue Reports:
  - Daily/Weekly/Monthly/Yearly
  - By service type
  - By vehicle type
  - By driver
- Booking Reports:
  - Total bookings
  - Booking trends
  - Cancellation analysis
  - Peak hours/days
- Customer Reports:
  - New customers
  - Active customers
  - Customer retention
  - Customer lifetime value
- Driver Reports:
  - Driver earnings
  - Driver performance
  - Active vs inactive drivers
- Financial Reports:
  - Payment collection
  - Outstanding payments
  - Refunds issued
  - Commission breakdown

#### B. **Export Options**
- Export to PDF
- Export to Excel
- Export to CSV
- Schedule automated reports (email)

---

### 14. **Settings Module**

#### A. **General Settings**
- Site name, tagline
- Contact information
- Business hours
- Time zone, currency
- Date/time format
- Language settings

#### B. **Booking Settings**
- Default booking time advance
- Cancellation policy
- Cancellation time limit
- Minimum booking amount
- Booking confirmation settings

#### C. **Pricing Settings**
- Base fare by vehicle type
- Per km rate
- Per minute rate
- Hourly rates
- Surge pricing (peak hours)
- Tax percentage
- Service fee

#### D. **Payment Gateway Settings**
- Stripe API keys
- PayPal credentials
- Enabled payment methods
- Currency settings
- Refund policies

#### E. **Email Settings**
- SMTP configuration
- Email templates:
  - Booking confirmation
  - Driver assigned
  - Booking reminder
  - Booking completed
  - Payment receipt
  - Password reset
- Email notifications enable/disable

#### F. **Map Settings**
- Google Maps API key
- Default map center (lat/lng)
- Distance unit (km/miles)

#### G. **Maintenance Mode**
- Enable/disable maintenance mode
- Maintenance message
- Allowed IP addresses

---

### 15. **Content Management Module**

#### A. **Pages**
- Manage static pages:
  - About Us
  - Terms & Conditions
  - Privacy Policy
  - FAQs
  - Contact Page
- Rich text editor
- SEO settings

#### B. **FAQs**
- CRUD operations for FAQs
- Category management
- Display order

#### C. **Testimonials**
- Add/Edit/Delete testimonials
- Featured testimonials
- Display settings

---

### 16. **Marketing Module**

#### A. **Promotions**
- Create promotional banners
- Schedule promotions
- Target specific user segments

#### B. **Email Campaigns**
- Create email campaigns
- Subscriber lists
- Send newsletters
- Campaign analytics

---

### 17. **System Logs Module**

#### A. **Activity Logs**
- View all admin activities
- Filter by user, action, date
- Export logs

#### B. **Error Logs**
- View application errors
- Filter by severity
- Clear logs

#### C. **Login Logs**
- Track admin logins
- Failed login attempts
- IP addresses

---

## API ENDPOINTS

### Public Endpoints (No Auth Required)

#### Authentication
```
POST   /api/register                 - User registration
POST   /api/login                    - User login
POST   /api/forgot-password          - Request password reset
POST   /api/reset-password           - Reset password
POST   /api/verify-email             - Verify email
POST   /api/verify-phone             - Verify phone (OTP)
```

#### General
```
GET    /api/services                 - List all active services
GET    /api/services/{slug}          - Service details
GET    /api/packages                 - List all active packages
GET    /api/packages/{slug}          - Package details
GET    /api/vehicle-types            - List all vehicle types
GET    /api/vehicle-types/{id}       - Vehicle type details
GET    /api/destinations             - List popular destinations
GET    /api/settings/public          - Public settings
```

### Authenticated Endpoints (Require Token)

#### User Profile
```
GET    /api/user/profile             - Get user profile
PUT    /api/user/profile             - Update user profile
POST   /api/user/change-password     - Change password
POST   /api/user/upload-avatar       - Upload profile photo
GET    /api/user/addresses           - List saved addresses
POST   /api/user/addresses           - Add new address
PUT    /api/user/addresses/{id}      - Update address
DELETE /api/user/addresses/{id}      - Delete address
```

#### Bookings
```
POST   /api/bookings/calculate-fare  - Calculate fare estimate
POST   /api/bookings                 - Create new booking
GET    /api/bookings                 - List user bookings
GET    /api/bookings/{id}            - Booking details
PUT    /api/bookings/{id}/cancel     - Cancel booking
GET    /api/bookings/{id}/track      - Real-time tracking
GET    /api/bookings/{id}/invoice    - Download invoice
```

#### Payments
```
GET    /api/payment-methods          - List saved payment methods
POST   /api/payment-methods          - Add payment method
DELETE /api/payment-methods/{id}     - Remove payment method
POST   /api/payments/process         - Process payment
GET    /api/payments/history         - Payment history
```

#### Promo Codes
```
POST   /api/promo-codes/validate     - Validate promo code
POST   /api/promo-codes/apply        - Apply promo code to booking
```

#### Ratings & Reviews
```
POST   /api/bookings/{id}/rate       - Rate driver after trip
GET    /api/ratings                  - User's ratings history
PUT    /api/ratings/{id}             - Update rating
DELETE /api/ratings/{id}             - Delete rating
```

#### Notifications
```
GET    /api/notifications            - List notifications
POST   /api/notifications/{id}/read  - Mark as read
DELETE /api/notifications/{id}       - Delete notification
```

### Driver Endpoints

```
POST   /api/driver/register          - Driver registration
GET    /api/driver/bookings          - Available bookings
PUT    /api/driver/bookings/{id}/accept - Accept booking
PUT    /api/driver/bookings/{id}/start  - Start trip
PUT    /api/driver/bookings/{id}/complete - Complete trip
POST   /api/driver/location          - Update location
GET    /api/driver/earnings          - Earnings report
POST   /api/driver/toggle-availability - Toggle online/offline
```

### Admin Endpoints

```
All CRUD operations for:
- /api/admin/users
- /api/admin/drivers
- /api/admin/vehicles
- /api/admin/vehicle-types
- /api/admin/bookings
- /api/admin/services
- /api/admin/packages
- /api/admin/destinations
- /api/admin/payments
- /api/admin/promo-codes
- /api/admin/reviews
- /api/admin/settings
- /api/admin/reports
- /api/admin/analytics
```

---

## FRONTEND INTEGRATION

### Pages Requiring Backend Integration

1. **index.html**
   - Quick booking form → POST /api/bookings/calculate-fare
   - Service cards → GET /api/services
   - Vehicle cards → GET /api/vehicle-types
   - Testimonials → Dynamic from database

2. **vehicles.html**
   - Vehicle grid → GET /api/vehicle-types
   - Filters → Dynamic filtering
   - Book now → Redirect to booking flow

3. **vehicle_details.html**
   - Vehicle info → GET /api/vehicle-types/{id}
   - Booking form → POST /api/bookings

4. **services.html**
   - Service cards → GET /api/services

5. **package_deals.html**
   - Package grid → GET /api/packages
   - Filters → Dynamic filtering

6. **package_details.html**
   - Package info → GET /api/packages/{slug}
   - Booking form → POST /api/bookings

7. **login.html**
   - Login form → POST /api/login

8. **register.html**
   - Registration form → POST /api/register

9. **dashboard.html**
   - User stats → GET /api/user/profile
   - Recent bookings → GET /api/bookings
   - Upcoming trips → GET /api/bookings?status=upcoming

10. **profile.html**
    - Profile data → GET /api/user/profile
    - Update form → PUT /api/user/profile
    - Addresses → GET /api/user/addresses

11. **my-bookings.html**
    - Bookings list → GET /api/bookings
    - Filters → Query parameters

12. **booking-details.html**
    - Booking info → GET /api/bookings/{id}
    - Track → GET /api/bookings/{id}/track
    - Invoice → GET /api/bookings/{id}/invoice

13. **booking-confirmation.html**
    - Confirmation data → After POST /api/bookings

14. **rate-driver.html**
    - Submit rating → POST /api/bookings/{id}/rate

15. **ratings-reviews.html**
    - Reviews list → GET /api/ratings

16. **saved-addresses.html**
    - Addresses list → GET /api/user/addresses
    - CRUD operations → POST/PUT/DELETE /api/user/addresses

17. **account-settings.html**
    - Settings data → GET /api/user/profile
    - Update → PUT /api/user/profile
    - Change password → POST /api/user/change-password

18. **contact.html**
    - Contact form → POST to email service

---

## THIRD-PARTY INTEGRATIONS

### 1. **Payment Gateways**

#### Stripe Integration
```php
// Install: composer require stripe/stripe-php

// config/services.php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],

// Process payment
$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
$paymentIntent = $stripe->paymentIntents->create([
    'amount' => $booking->total_amount * 100,
    'currency' => 'usd',
    'payment_method' => $paymentMethodId,
    'confirm' => true,
]);
```

#### PayPal Integration
```php
// Install: composer require paypal/rest-api-sdk-php
```

### 2. **Google Maps API**

#### Map Services
```javascript
// Distance Matrix API
// Directions API
// Places API
// Geocoding API

// .env
GOOGLE_MAPS_API_KEY=your_api_key
```

```php
// app/Services/MapService.php
public function calculateDistance($origin, $destination)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
    $params = [
        'origins' => $origin,
        'destinations' => $destination,
        'key' => config('services.google_maps.api_key'),
    ];
    
    $response = Http::get($url, $params);
    return $response->json();
}
```

### 4. **Email Service**

```php
// config/mail.php - Use Mailgun or SMTP

// Send booking confirmation
Mail::to($user)->send(new BookingConfirmation($booking));
```

---

## IMPLEMENTATION ROADMAP

### **Phase 1: Foundation (Weeks 1-3)**

#### Week 1: Project Setup & Database
- [ ] Install Laravel 10+
- [ ] Configure database connection
- [ ] Create all database migrations
- [ ] Run migrations
- [ ] Create database seeders
- [ ] Set up authentication (Laravel Sanctum)
- [ ] Configure file storage (S3 or local)
- [ ] Set up environment variables

#### Week 2: Models & Relationships
- [ ] Create all Eloquent models
- [ ] Define model relationships
- [ ] Create model factories
- [ ] Set up accessors & mutators
- [ ] Implement soft deletes
- [ ] Create observers for audit logging

#### Week 3: Admin Panel Foundation
- [ ] Install Filament v3 or Laravel Backpack
- [ ] Configure admin authentication
- [ ] Create admin dashboard layout
- [ ] Set up admin menu structure
- [ ] Implement roles & permissions
- [ ] Create admin user seeder

---

### **Phase 2: Core Modules (Weeks 4-6)**

#### Week 4: Vehicle & Driver Management
- [ ] Vehicle Types CRUD
- [ ] Vehicles CRUD with image upload
- [ ] Driver registration & profile
- [ ] Driver verification system
- [ ] Driver-vehicle assignment
- [ ] Vehicle maintenance tracking

#### Week 5: Service & Package Management
- [ ] Services CRUD
- [ ] Packages CRUD
- [ ] Destinations CRUD
- [ ] Pricing configuration
- [ ] Image upload & gallery

#### Week 6: Customer Management
- [ ] Customer list with filters
- [ ] Customer profile details
- [ ] Customer address management
- [ ] Customer activity logs
- [ ] Customer tier management

---

### **Phase 3: Booking System (Weeks 7-9)**

#### Week 7: Booking Core
- [ ] Booking creation flow
- [ ] Fare calculation engine
- [ ] Driver assignment logic
- [ ] Booking status management
- [ ] Booking calendar view

#### Week 8: Booking Features
- [ ] Real-time driver location tracking
- [ ] Booking notifications (Email)
- [ ] Booking cancellation logic
- [ ] Booking rescheduling
- [ ] Manual booking from admin

#### Week 9: Booking Analytics
- [ ] Booking reports
- [ ] Revenue reports
- [ ] Driver performance reports
- [ ] Peak hours analysis
- [ ] Export functionality

---

### **Phase 4: Payment System (Weeks 10-11)**

#### Week 10: Payment Gateway Integration
- [ ] Stripe integration
- [ ] PayPal integration
- [ ] Payment method storage
- [ ] Payment processing
- [ ] Webhook handling

#### Week 11: Payment Management
- [ ] Transaction list & details
- [ ] Refund processing
- [ ] Payment reports
- [ ] Invoice generation (PDF)
- [ ] Payment receipts

---

### **Phase 5: Additional Features (Weeks 12-13)**

#### Week 12: Ratings & Content
- [ ] Rating & review system
- [ ] Review moderation
- [ ] Content management (Pages, FAQs)
- [ ] Testimonials management

#### Week 13: Marketing & Promo
- [ ] Promo code system
- [ ] Promo code validation API
- [ ] Usage tracking
- [ ] Email campaigns (optional)

---

### **Phase 6: API Development (Weeks 14-15)**

#### Week 14: Public & Auth APIs
- [ ] Authentication endpoints
- [ ] User profile APIs
- [ ] Booking APIs
- [ ] Service & package APIs
- [ ] Vehicle type APIs

#### Week 15: Payment & Additional APIs
- [ ] Payment APIs
- [ ] Promo code APIs
- [ ] Rating APIs
- [ ] Notification APIs
- [ ] API documentation (Swagger/Postman)

---

### **Phase 7: Frontend Integration (Weeks 16-17)**

#### Week 16: Connect Frontend to Backend
- [ ] Update frontend to call APIs
- [ ] Implement authentication flow
- [ ] Connect booking forms
- [ ] Integrate payment gateway

#### Week 17: Testing & Refinement
- [ ] Test all user flows
- [ ] Fix bugs
- [ ] Optimize performance
- [ ] Security audit
- [ ] Cross-browser testing

---

### **Phase 8: Testing & Deployment (Week 18)**

#### Week 18: Final Testing & Launch
- [ ] Unit tests
- [ ] Integration tests
- [ ] Load testing
- [ ] Security testing
- [ ] Deploy to production server
- [ ] Configure SSL certificate
- [ ] Set up backups
- [ ] Monitor error logs

---

## ADDITIONAL CONSIDERATIONS

### Security Checklist
- [ ] Validate all user inputs
- [ ] Implement CSRF protection
- [ ] Use prepared statements (Eloquent ORM)
- [ ] Hash passwords (bcrypt)
- [ ] Encrypt sensitive data
- [ ] Rate limiting on APIs
- [ ] SQL injection prevention
- [ ] XSS protection
- [ ] Secure file uploads
- [ ] Environment variable protection

### Performance Optimization
- [ ] Database indexing
- [ ] Query optimization
- [ ] Eager loading relationships
- [ ] Cache frequently accessed data (Redis)
- [ ] Image optimization
- [ ] CDN for static assets
- [ ] Enable gzip compression

### Scalability
- [ ] Horizontal scaling readiness
- [ ] Load balancer support
- [ ] Database replication
- [ ] Session management (Redis)
- [ ] Cache management

---

## ESTIMATED COSTS

### Development
- Developer (18 weeks): $15,000 - $30,000
- Designer (if needed): $2,000 - $5,000

### Third-party Services (Monthly)
- Server hosting (VPS/Cloud): $50 - $200
- Domain name: $10 - $15/year
- SSL certificate: Free (Let's Encrypt)
- Google Maps API: $200 - $500 (based on usage)
- Email service (Mailgun): $35 - $80
- Payment gateway fees: 2.9% + $0.30 per transaction
- Database backups: $20 - $50

### Total Estimated Monthly Cost: $300 - $850

---

## SUPPORT & MAINTENANCE

### Post-Launch Tasks
- [ ] Monitor error logs daily
- [ ] Regular database backups
- [ ] Security updates
- [ ] Feature enhancements
- [ ] Bug fixes
- [ ] Performance monitoring
- [ ] User feedback implementation

---

## CONCLUSION

This comprehensive plan covers all aspects of building a Laravel-based admin panel for City Link Chauffeurs. The system includes complete vehicle, driver, booking, payment, and customer management with extensive reporting and analytics capabilities.

**Key Features:**
✅ Complete booking management system  
✅ Real-time driver tracking  
✅ Payment gateway integration  
✅ Rating & review system  
✅ Promo code management  
✅ Comprehensive reporting  
✅ RESTful API for frontend integration  
✅ Role-based access control  
✅ Mobile-responsive design  

**Next Steps:**
1. Review and approve this plan
2. Set up development environment
3. Begin Phase 1 implementation
4. Regular progress reviews
5. Iterative testing and deployment

---

**Document Version:** 1.0  
**Last Updated:** November 12, 2025  
**Status:** Ready for Development  
**Prepared By:** GitHub Copilot

---

**Contact for clarifications or modifications to this plan.**
