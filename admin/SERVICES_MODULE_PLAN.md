# Services Management Module - Development Plan

**Project**: City Link Chauffeurs - Admin Panel  
**Module**: Services Management  
**Framework**: Laravel 11 + Alpine.js + Tailwind CSS  
**Status**: 🔴 Not Started (0/20 tasks)  
**Created**: November 14, 2025  

---

## 📋 Table of Contents
1. [Module Overview](#module-overview)
2. [Service Categories](#service-categories)
3. [Database Schema](#database-schema)
4. [Development Tasks](#development-tasks)
5. [Features & Functionality](#features--functionality)
6. [UI/UX Specifications](#uiux-specifications)
7. [Dependencies](#dependencies)

---

## 🎯 Module Overview

The Services Management module allows administrators to manage all transportation services offered by City Link Chauffeurs. Each service represents a distinct transportation offering with detailed information, pricing structures, and availability settings.

**Key Objectives:**
- ✅ Comprehensive CRUD operations for services
- ✅ Multiple service categories (Point-to-Point, Hourly, Airport, Corporate, Events, Tours)
- ✅ Rich text descriptions with image support
- ✅ Flexible pricing structures (flat rate, hourly, distance-based, custom)
- ✅ Service-specific features and amenities
- ✅ Availability management (days, hours, advance booking requirements)
- ✅ Vehicle type associations
- ✅ Service area management
- ✅ SEO-friendly slugs and meta tags

---

## 📊 Service Categories

Based on the frontend analysis, the system supports these service types:

| Category | Slug | Description | Pricing Type |
|----------|------|-------------|--------------|
| **Point-to-Point** | `point-to-point` | Direct transfers between two locations | Distance-based or Flat |
| **Hourly Charter** | `hourly-charter` | Flexible hourly booking with driver | Hourly rate |
| **Airport Transfer** | `airport-transfer` | Airport pickup/dropoff services | Flat rate per trip |
| **Corporate Services** | `corporate` | Business travel and executive transport | Custom/Contract |
| **Special Events** | `events` | Weddings, parties, celebrations | Flat or hourly |
| **Tours & Packages** | `tours` | City tours and sightseeing | Flat or hourly |

---

## 🗄️ Database Schema

### Migration: `create_services_table`

```php
Schema::create('services', function (Blueprint $table) {
    // Primary Fields
    $table->id();
    $table->string('name', 255);                    // Service name
    $table->string('slug', 255)->unique();          // URL-friendly slug
    $table->enum('category', [
        'point-to-point',
        'hourly-charter', 
        'airport-transfer',
        'corporate',
        'events',
        'tours'
    ])->default('point-to-point');
    $table->boolean('is_active')->default(true);    // Active/Inactive
    $table->boolean('is_featured')->default(false); // Featured on homepage
    $table->integer('sort_order')->default(0);      // Display order
    
    // Description & Content
    $table->string('short_description', 500)->nullable();
    $table->longText('description')->nullable();     // Rich text - Main content
    $table->longText('features')->nullable();        // Rich text - Key features
    $table->longText('inclusions')->nullable();      // Rich text - What's included
    $table->longText('exclusions')->nullable();      // Rich text - What's not included
    $table->longText('terms_conditions')->nullable(); // Rich text - Terms
    $table->longText('cancellation_policy')->nullable(); // Rich text - Cancellation rules
    
    // Pricing Configuration
    $table->enum('pricing_type', [
        'flat_rate',        // Fixed price
        'hourly',           // Per hour
        'distance_based',   // Per mile/km
        'custom',           // Negotiable/quote-based
        'tiered'            // Different rates for time ranges
    ])->default('flat_rate');
    $table->decimal('base_price', 10, 2)->nullable();          // Starting/base price
    $table->decimal('min_price', 10, 2)->nullable();           // Minimum charge
    $table->decimal('hourly_rate', 10, 2)->nullable();         // Hourly rate (if applicable)
    $table->decimal('per_mile_rate', 10, 2)->nullable();       // Per mile rate (if applicable)
    $table->integer('min_hours')->nullable();                   // Minimum hours required
    $table->integer('max_hours')->nullable();                   // Maximum hours allowed
    $table->decimal('additional_hour_rate', 10, 2)->nullable(); // Rate after min hours
    $table->json('tiered_pricing')->nullable();                 // Time-based pricing tiers
    
    // Availability & Booking
    $table->json('available_days')->nullable();      // Days of week available
    $table->time('available_from')->nullable();      // Service start time
    $table->time('available_to')->nullable();        // Service end time
    $table->integer('advance_booking_hours')->default(24); // Min advance booking
    $table->integer('max_advance_days')->nullable(); // Max days in advance
    $table->integer('max_passengers')->nullable();   // Max passenger capacity
    $table->integer('max_luggage')->nullable();      // Max luggage pieces
    $table->integer('free_waiting_time')->nullable(); // Free waiting mins
    $table->decimal('waiting_charge_per_min', 10, 2)->nullable(); // Waiting charges
    
    // Service Areas
    $table->json('service_areas')->nullable();       // Geographic coverage
    $table->integer('max_distance_miles')->nullable(); // Max service distance
    $table->boolean('airport_service')->default(false);
    $table->json('supported_airports')->nullable();   // Airport codes/names
    
    // Vehicle Associations
    $table->json('vehicle_types')->nullable();       // Array of vehicle type IDs
    $table->json('amenities')->nullable();           // Service amenities/features
    
    // Media
    $table->string('icon', 100)->nullable();         // FontAwesome icon class
    $table->string('thumbnail')->nullable();         // Main image
    $table->json('gallery')->nullable();             // Additional images
    
    // Quick Facts
    $table->json('quick_facts')->nullable();         // Key info (JSON: {key: value})
    
    // SEO
    $table->string('meta_title', 255)->nullable();
    $table->text('meta_description')->nullable();
    $table->json('meta_keywords')->nullable();
    
    // Statistics (auto-updated)
    $table->integer('total_bookings')->default(0);
    $table->decimal('total_revenue', 12, 2)->default(0);
    $table->decimal('avg_rating', 3, 2)->nullable();
    $table->integer('total_reviews')->default(0);
    $table->timestamp('last_booking_at')->nullable();
    
    // Timestamps
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes
    $table->index('category');
    $table->index('is_active');
    $table->index('is_featured');
    $table->index('sort_order');
    $table->index(['category', 'is_active']);
});
```

### JSON Field Structures

**1. tiered_pricing** - Different rates for different time periods:
```json
[
    {"hours": "2", "rate": "150.00"},
    {"hours": "4", "rate": "280.00"},
    {"hours": "8", "rate": "500.00"},
    {"hours": "full_day", "rate": "700.00"}
]
```

**2. available_days** - Days service is offered:
```json
["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"]
```

**3. service_areas** - Geographic coverage:
```json
[
    {"name": "San Francisco", "type": "city"},
    {"name": "Oakland", "type": "city"},
    {"name": "Bay Area", "type": "region"},
    {"name": "Northern California", "type": "region"}
]
```

**4. supported_airports** - Airport coverage:
```json
[
    {"code": "SFO", "name": "San Francisco International Airport"},
    {"code": "OAK", "name": "Oakland International Airport"},
    {"code": "SJC", "name": "San Jose International Airport"}
]
```

**5. vehicle_types** - Associated vehicle categories:
```json
[1, 3, 5, 8]  // Array of vehicle_type IDs
```

**6. amenities** - Service features:
```json
[
    "Professional uniformed chauffeur",
    "Complimentary bottled water",
    "Wi-Fi enabled vehicles",
    "Phone chargers",
    "Meet & greet service",
    "Flight tracking",
    "Child safety seats available",
    "Wheelchair accessible options",
    "Luggage assistance"
]
```

**7. quick_facts** - Key information:
```json
{
    "duration": "1-2 hours",
    "availability": "24/7",
    "capacity": "1-3 passengers",
    "luggage": "2 large bags",
    "vehicle": "Executive Sedan or SUV"
}
```

**8. meta_keywords** - SEO keywords:
```json
["airport transfer", "luxury transport", "chauffeur service", "san francisco"]
```

---

## ✅ Development Tasks

### **Phase 1: Database & Model Setup** (Tasks 1-3)

#### ✅ Task 1: Create Services Migration
- **File**: `database/migrations/YYYY_MM_DD_create_services_table.php`
- **Action**: Create comprehensive services table with all fields defined above
- **Validation**: Run migration successfully without errors

#### ✅ Task 2: Create Service Model
- **File**: `app/Models/Service.php`
- **Features**:
  - Mass assignable fields (fillable array)
  - Casts for JSON fields, booleans, decimals, dates
  - Soft deletes trait
  - Accessors for formatted prices
  - Scopes: `active()`, `featured()`, `byCategory($category)`, `popular()`
  - Relationships:
    - `bookings()` - hasMany to Booking model
    - `reviews()` - hasMany to Review model
    - `vehicleTypes()` - Custom accessor to fetch VehicleType models from vehicle_types JSON
  - Spatie Media Library integration for images
  - Slug generation on save
- **Methods**:
  - `getFormattedPriceAttribute()` - Returns formatted base price
  - `getPriceDisplayAttribute()` - Returns "From $X" or "Starting at $X/hr"
  - `isAvailableOnDay($day)` - Check if service available on specific day
  - `isAvailableAt($time)` - Check if service available at specific time
  - `canBookInAdvance($date)` - Validate advance booking window

#### ✅ Task 3: Create Service Seeder
- **File**: `database/seeders/ServiceSeeder.php`
- **Data**: Create 12-15 diverse services covering all categories
- **Categories to include**:
  - 2-3 Point-to-Point services
  - 2-3 Hourly Charter services
  - 3-4 Airport Transfer services (different airports)
  - 2-3 Corporate services
  - 1-2 Special Events services
  - 1-2 Tours services
- **Variation**: Different pricing types, availability schedules, and features
- **Content**: Rich HTML descriptions, features, inclusions, terms
- **Call from**: `DatabaseSeeder.php`

---

### **Phase 2: Routes & Controller** (Tasks 4-5)

#### ✅ Task 4: Define Service Routes
- **File**: `routes/web.php`
- **Routes**:
```php
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('services', ServiceController::class);
    Route::post('services/{service}/toggle-active', [ServiceController::class, 'toggleActive'])
        ->name('services.toggle-active');
    Route::post('services/{service}/toggle-featured', [ServiceController::class, 'toggleFeatured'])
        ->name('services.toggle-featured');
    Route::post('services/{service}/duplicate', [ServiceController::class, 'duplicate'])
        ->name('services.duplicate');
    Route::post('services/reorder', [ServiceController::class, 'reorder'])
        ->name('services.reorder');
});
```

#### ✅ Task 5: Create ServiceController
- **File**: `app/Http/Controllers/Admin/ServiceController.php`
- **Methods**:
  1. `index()` - List services with filters, search, pagination
  2. `create()` - Show create form with vehicle types, categories
  3. `store()` - Validate and create service
  4. `show()` - Display service details with statistics
  5. `edit()` - Show edit form with existing data
  6. `update()` - Validate and update service
  7. `destroy()` - Soft delete service (check for active bookings first)
  8. `toggleActive()` - Toggle is_active status
  9. `toggleFeatured()` - Toggle is_featured status
  10. `duplicate()` - Create copy of service
  11. `reorder()` - Update sort_order for services
- **Validation Rules**:
  - name: required, max 255, unique (except current ID)
  - slug: required, max 255, unique (except current ID)
  - category: required, in allowed categories
  - pricing_type: required, in allowed pricing types
  - base_price: required_if pricing_type is flat_rate
  - hourly_rate: required_if pricing_type is hourly
  - Rich text fields: nullable, max 65535 chars
  - JSON fields: nullable, json format
  - Numeric fields: numeric, min 0
- **Error Handling**: Try-catch blocks for database operations, graceful degradation

---

### **Phase 3: Views - Index & Show** (Tasks 6-7)

#### ✅ Task 6: Create Services Index View
- **File**: `resources/views/admin/services/index.blade.php`
- **Layout**: Extends `layouts.app`
- **Components**:

**Header Section**:
```blade
- Page title: "Services Management"
- Stats cards row (4 cards):
  * Total Services (with icon)
  * Active Services (with percentage)
  * Featured Services
  * Total Revenue (from all service bookings)
- Buttons: "+ Add Service", "Reorder Services", "Export CSV"
```

**Filters Section**:
```blade
- Search box (name, description)
- Category filter (dropdown with all categories)
- Status filter: All, Active, Inactive
- Featured filter: All, Featured Only
- Pricing type filter: All types
- Sort by: Name, Created Date, Sort Order, Popularity, Revenue
- Reset filters button
```

**Services Table**:
| Column | Content |
|--------|---------|
| Service | Thumbnail + Name + Category badge |
| Type | Pricing type badge |
| Price | Formatted base price with pricing type indicator |
| Status | Active/Inactive toggle badge |
| Featured | Star icon toggle |
| Bookings | Total bookings count |
| Revenue | Total revenue formatted |
| Sort Order | Drag handle + order number |
| Actions | View, Edit, Duplicate, Delete dropdown |

**Features**:
- Sortable columns
- Row hover effects
- Status toggle (AJAX)
- Featured toggle (AJAX)
- Bulk actions checkbox
- Pagination (25 per page)
- Empty state with CTA
- Drag-and-drop reordering

#### ✅ Task 7: Create Service Show View
- **File**: `resources/views/admin/services/show.blade.php`
- **Layout**: Extends `layouts.app`
- **Sections**:

**Header**:
```blade
- Service name with category badge
- Status badges (Active, Featured)
- Action buttons: Edit, Duplicate, Delete
- Back to list link
```

**Main Content (2-column layout)**:

**Left Column (8 cols)**:
1. **Service Overview Card**:
   - Thumbnail image
   - Short description
   - Icon display
   - Created/Updated dates

2. **Description Card**:
   - Rich HTML content from description field
   - Collapsible if long

3. **Features Card**:
   - Rich HTML content from features field
   - Icon list format

4. **Inclusions & Exclusions Card**:
   - Two tabs: "Included" and "Excluded"
   - Rich HTML content

5. **Terms & Policies Card**:
   - Accordion sections:
     * Terms & Conditions
     * Cancellation Policy

6. **Service Areas Card**:
   - List of covered areas with type badges
   - Airport support indicator
   - Supported airports list (if applicable)

7. **Recent Bookings Card** (if bookings exist):
   - Table with last 10 bookings
   - Columns: Booking ID, Customer, Date, Amount, Status
   - Link to view all bookings for this service

**Right Column (4 cols)**:
1. **Statistics Card**:
   - Total Bookings
   - Total Revenue
   - Average Rating (with stars)
   - Total Reviews
   - Last Booking Date

2. **Pricing Details Card**:
   - Pricing Type badge
   - Base Price (large display)
   - Min/Max prices
   - Hourly rate (if applicable)
   - Per mile rate (if applicable)
   - Min/Max hours
   - Tiered pricing table (if applicable)

3. **Availability Card**:
   - Available days (pill badges)
   - Operating hours (from-to)
   - Advance booking requirements
   - Max advance days
   - Maximum passengers
   - Maximum luggage

4. **Booking Restrictions Card**:
   - Free waiting time
   - Waiting charges
   - Max distance
   - Minimum hours

5. **Vehicle Types Card**:
   - List of associated vehicle types
   - Vehicle thumbnails and names
   - Link to vehicle management

6. **Amenities Card**:
   - List of service amenities with icons
   - Checkmark icons for each

7. **Quick Facts Card**:
   - Key-value pairs display
   - Icon-based layout

8. **Gallery Card** (if images exist):
   - Thumbnail grid
   - Lightbox view on click

9. **SEO Information Card**:
   - Meta title
   - Meta description
   - Meta keywords (pill badges)
   - Slug display

---

### **Phase 4: Views - Create & Edit** (Tasks 8-9)

#### ✅ Task 8: Create Service Create View
- **File**: `resources/views/admin/services/create.blade.php`
- **Layout**: Extends `layouts.app`
- **Form Structure**: POST to `admin.services.store`

**Multi-card Form Layout**:

**Card 1: Basic Information**:
```blade
- Service Name* (text input)
- Slug* (text input, auto-generated from name with edit option)
- Category* (select dropdown with all categories)
- Short Description (textarea, max 500 chars, character counter)
- Icon (text input for FontAwesome class with icon preview)
- Sort Order (number input)
- Status toggles: Is Active (switch), Is Featured (switch)
```

**Card 2: Description & Content** (Summernote editors):
```blade
- Description* (Summernote with full features, 400px height)
- Features (Summernote with full features, 300px height)
- Inclusions (Summernote, 250px height)
- Exclusions (Summernote, 250px height)
```

**Card 3: Terms & Policies** (Summernote editors):
```blade
- Terms & Conditions (Summernote, 300px height)
- Cancellation Policy (Summernote, 300px height)
```

**Card 4: Pricing Configuration**:
```blade
- Pricing Type* (select: Flat Rate, Hourly, Distance-based, Custom, Tiered)
- Base Price (number input, shown for flat_rate)
- Minimum Price (number input)
- Hourly Rate (number input, shown for hourly/tiered)
- Per Mile Rate (number input, shown for distance_based)
- Minimum Hours (number input)
- Maximum Hours (number input)
- Additional Hour Rate (number input)
- Tiered Pricing (repeater field, shown for tiered):
  * Hour/Duration (text input)
  * Rate (number input)
  * Add/Remove tier buttons
```

**Card 5: Availability Settings**:
```blade
- Available Days* (checkbox group: Mon-Sun, Select All option)
- Available From (time input)
- Available To (time input)
- Advance Booking Hours* (number input, default 24)
- Max Advance Days (number input)
- Maximum Passengers (number input)
- Maximum Luggage (number input)
```

**Card 6: Booking Settings**:
```blade
- Free Waiting Time (number input, in minutes)
- Waiting Charge Per Minute (number input)
- Max Distance Miles (number input)
- Airport Service (switch toggle)
- Supported Airports (repeater, shown if airport service enabled):
  * Airport Code (text input)
  * Airport Name (text input)
  * Add/Remove airport buttons
```

**Card 7: Service Areas**:
```blade
- Service Areas (repeater field):
  * Area Name (text input)
  * Type (select: City, Region, State)
  * Add/Remove area buttons
```

**Card 8: Vehicle Types & Amenities**:
```blade
- Vehicle Types (multi-select checkbox list, fetch from vehicle_types table)
- Amenities (repeater field):
  * Amenity name (text input)
  * Add/Remove buttons
  * Pre-defined suggestions dropdown
```

**Card 9: Media**:
```blade
- Thumbnail Image* (file upload with preview)
- Gallery Images (multiple file upload with preview grid)
- Upload guidelines: Max 5MB per image, JPG/PNG/WebP
```

**Card 10: Quick Facts**:
```blade
- Quick Facts (repeater field with key-value pairs):
  * Key (text input)
  * Value (text input)
  * Add/Remove fact buttons
```

**Card 11: SEO Settings**:
```blade
- Meta Title (text input, max 255 chars with counter)
- Meta Description (textarea, max 320 chars with counter)
- Meta Keywords (tag input, comma-separated)
```

**Form Footer**:
```blade
- Save & Exit button (primary)
- Save & Add Another button
- Cancel button (secondary)
```

**JavaScript Features**:
- Auto-generate slug from service name
- Show/hide fields based on pricing type selection
- Show/hide airport fields based on airport service toggle
- Character counters for text fields
- Summernote initialization with image upload
- Form validation before submit
- Unsaved changes warning

#### ✅ Task 9: Create Service Edit View
- **File**: `resources/views/admin/services/edit.blade.php`
- **Layout**: Same as create view
- **Differences**:
  - Form method: PUT to `admin.services.update`
  - All fields pre-filled with `old()` or `$service->field`
  - Existing thumbnail/gallery images displayed
  - Existing tiered pricing, areas, airports, amenities loaded
  - Show creation and last update timestamps
  - Delete button in form footer

---

### **Phase 5: UI Components & Assets** (Tasks 10-12)

#### ✅ Task 10: Add Services to Admin Sidebar
- **File**: `resources/views/layouts/app.blade.php`
- **Location**: After Packages menu item
- **Structure**:
```blade
<li>
    <a href="{{ route('admin.services.index') }}" 
       class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
        <i class="fas fa-concierge-bell"></i>
        <span>Services</span>
        @if($pendingServicesCount > 0)
            <span class="badge">{{ $pendingServicesCount }}</span>
        @endif
    </a>
</li>
```
- **Badge**: Show count of inactive services (optional)

#### ✅ Task 11: Build Frontend Assets
- **Action**: Compile CSS/JS with Vite
- **Command**: `npm run build`
- **Verify**: Alpine.js components, Summernote, styles load correctly

#### ✅ Task 12: Update Database Seeder
- **File**: `database/seeders/DatabaseSeeder.php`
- **Action**: Add `ServiceSeeder::class` to `$this->call()` array
- **Order**: After VehicleSeeder, before PackageSeeder

---

### **Phase 6: Authorization & Permissions** (Tasks 13-14)

#### ✅ Task 13: Create ServicePolicy
- **File**: `app/Policies/ServicePolicy.php`
- **Command**: `php artisan make:policy ServicePolicy --model=Service`
- **Methods**:
  - `viewAny()` - Check if user can view services list
  - `view()` - Check if user can view specific service
  - `create()` - Check if user can create services
  - `update()` - Check if user can update service
  - `delete()` - Check if user can delete service (check for active bookings)
  - `toggleActive()` - Check if user can toggle active status
  - `toggleFeatured()` - Check if user can toggle featured status
- **Register**: In `AuthServiceProvider`

#### ✅ Task 14: Seed Service Permissions
- **File**: `database/seeders/PermissionSeeder.php` (create if doesn't exist)
- **Permissions**:
  - `services.view` - View services list
  - `services.create` - Create new services
  - `services.update` - Edit existing services
  - `services.delete` - Delete services
  - `services.toggle-active` - Activate/deactivate services
  - `services.toggle-featured` - Mark services as featured
  - `services.reorder` - Change sort order
- **Assign**: Assign to admin and manager roles

---

### **Phase 7: Optional Enhancements** (Tasks 15-17)

#### ✅ Task 15: Add Dashboard Widget
- **File**: `resources/views/admin/dashboard.blade.php`
- **Widget**: Services Summary Card
- **Content**:
  - Total services count
  - Active services count
  - Featured services count
  - Most popular service (by bookings)
  - Total revenue from services
  - Chart: Bookings by service category (pie/donut chart)
- **Link**: "View All Services" button

#### ✅ Task 16: Implement Export Functionality
- **Package**: `maatwebsite/laravel-excel` (already installed)
- **File**: `app/Exports/ServicesExport.php`
- **Controller Method**: `ServiceController::export()`
- **Route**: `GET admin/services/export`
- **Columns**: ID, Name, Category, Pricing Type, Base Price, Status, Bookings, Revenue, Created Date
- **Filters**: Export with applied filters
- **Format**: CSV

#### ✅ Task 17: Add Slug Package (Optional)
- **Package**: `spatie/laravel-sluggable`
- **Installation**: `composer require spatie/laravel-sluggable`
- **Implementation**: Use in Service model for auto-slug generation
- **Benefit**: Automatic URL-friendly slug creation from service name

---

### **Phase 8: Testing & Validation** (Tasks 18-20)

#### ✅ Task 18: Test Service CRUD Operations
**Manual Testing Checklist**:
- [ ] Create service with all fields
- [ ] Create service with minimal required fields
- [ ] Edit service and update all fields
- [ ] Edit service and change pricing type (verify field visibility)
- [ ] Delete service without bookings
- [ ] Attempt to delete service with bookings (should prevent)
- [ ] Toggle active status via table button
- [ ] Toggle featured status via table button
- [ ] Duplicate service (verify all fields copied)
- [ ] Upload thumbnail and gallery images
- [ ] Test Summernote editors with formatting and images
- [ ] Test tiered pricing repeater
- [ ] Test service areas repeater
- [ ] Test supported airports repeater
- [ ] Test amenities repeater
- [ ] Test quick facts repeater

#### ✅ Task 19: Test Views & Filters
**View Testing Checklist**:
- [ ] Index page loads with seeded services
- [ ] Search by service name works
- [ ] Filter by category works
- [ ] Filter by status (active/inactive) works
- [ ] Filter by featured works
- [ ] Filter by pricing type works
- [ ] Sort by different columns works
- [ ] Pagination works correctly
- [ ] Stats cards show correct counts
- [ ] Show page displays all service details
- [ ] Show page displays bookings (if exist)
- [ ] Show page displays statistics correctly
- [ ] Rich text content renders correctly (HTML)
- [ ] Gallery images display in lightbox
- [ ] Vehicle types display correctly
- [ ] Service areas display correctly

#### ✅ Task 20: Test Reordering
**Reordering Testing**:
- [ ] Drag and drop services to reorder
- [ ] Order persists after page reload
- [ ] Order affects display on frontend (if implemented)
- [ ] AJAX save works without page refresh

---

## 🎨 UI/UX Specifications

### Color Scheme
- **Primary**: Tailwind blue-600 (#2563EB)
- **Success**: Tailwind green-600 (#16A34A)
- **Warning**: Tailwind yellow-500 (#EAB308)
- **Danger**: Tailwind red-600 (#DC2626)
- **Gold Accent**: #D4AF37 (brand color)

### Category Color Coding
- Point-to-Point: Blue
- Hourly Charter: Purple
- Airport Transfer: Cyan
- Corporate: Indigo
- Events: Pink
- Tours: Green

### Pricing Type Badges
- Flat Rate: Blue badge
- Hourly: Purple badge
- Distance-based: Cyan badge
- Custom: Gray badge
- Tiered: Indigo badge

### Icons (FontAwesome)
- Point-to-Point: `fa-route`
- Hourly Charter: `fa-clock`
- Airport Transfer: `fa-plane-departure`
- Corporate: `fa-briefcase`
- Events: `fa-calendar-star`
- Tours: `fa-map-marked-alt`

### Responsive Breakpoints
- Mobile: < 768px (stack cards)
- Tablet: 768px - 1024px (2-column layout)
- Desktop: > 1024px (full layout)

---

## 🔗 Dependencies

### Backend Packages (Already Installed)
- ✅ `laravel/framework: ^11.0`
- ✅ `spatie/laravel-permission` - Role/permission management
- ✅ `spatie/laravel-medialibrary` - Image management
- ✅ `maatwebsite/laravel-excel` - Export functionality

### Optional Packages
- ⏳ `spatie/laravel-sluggable` - Auto-slug generation

### Frontend Libraries (Already Integrated)
- ✅ Alpine.js 3.x - UI components
- ✅ Tailwind CSS 3.x - Styling
- ✅ Summernote 0.8.18 - Rich text editor with image upload
- ✅ jQuery 3.6.0 - Required for Summernote
- ✅ Chart.js - Dashboard charts

### Database Relationships
- **Depends On**: 
  - `vehicle_types` table (for vehicle associations)
  - `users` table (for creator tracking)
- **Referenced By**:
  - `bookings` table (foreign key: service_id)
  - `reviews` table (foreign key: service_id)
  - `service_bookings` pivot table (if multiple services per booking)

---

## 📝 Notes & Best Practices

### Defensive Coding
- **Booking Relationships**: Wrap all bookings/reviews queries in try-catch blocks
- **Reason**: Bookings/Reviews modules may not exist yet
- **Fallback**: Return default values (0 counts, empty arrays) when table missing
- **Example**:
```php
try {
    $service->load('bookings');
    $stats = [
        'total_bookings' => $service->bookings()->count(),
        'total_revenue' => $service->bookings()->sum('total_amount')
    ];
} catch (\Exception $e) {
    $stats = ['total_bookings' => 0, 'total_revenue' => 0];
}
```

### Image Upload Strategy
- **Summernote**: Use base64 inline images for descriptions (no backend storage needed)
- **Thumbnail/Gallery**: Use Spatie Media Library for proper storage
- **Validation**: Max 5MB per image, allowed types: jpg, png, webp
- **Optimization**: Consider image compression on upload

### Slug Management
- **Auto-generate**: From service name on create
- **Editable**: Allow manual override
- **Unique**: Validate uniqueness in database
- **Format**: lowercase, hyphens, no special chars

### Pricing Complexity
- **Conditional Fields**: Show/hide based on pricing_type selection
- **Validation**: Require appropriate fields per pricing type
- **Display**: Format prices consistently across all views
- **Tiered Pricing**: Use repeater component for dynamic tiers

### Performance Considerations
- **Eager Loading**: Load relationships when querying lists
- **Pagination**: Default 25 per page, adjustable
- **Indexes**: On category, is_active, is_featured for fast filtering
- **Caching**: Consider caching service list for frontend display

### Security
- **Authorization**: Use policies and permissions consistently
- **Validation**: Server-side validation for all inputs
- **Sanitization**: Clean HTML from rich text editors (allow safe tags only)
- **CSRF**: Ensure all forms include CSRF token

---

## 🎯 Success Criteria

Module is considered complete when:
- ✅ All 20 tasks completed
- ✅ CRUD operations work without errors
- ✅ Rich text editor functional with image upload
- ✅ All filters and search working
- ✅ Reordering functional (if implemented)
- ✅ Permissions properly enforced
- ✅ Views render correctly on mobile/tablet/desktop
- ✅ No console errors or PHP warnings
- ✅ Database seeder creates 12+ diverse services
- ✅ Export functionality works (if implemented)
- ✅ Dashboard widget displays (if implemented)

---

## 📅 Estimated Timeline

| Phase | Tasks | Estimated Time |
|-------|-------|----------------|
| Phase 1: Database & Model | 1-3 | 3-4 hours |
| Phase 2: Routes & Controller | 4-5 | 4-5 hours |
| Phase 3: Index & Show Views | 6-7 | 4-5 hours |
| Phase 4: Create & Edit Views | 8-9 | 5-6 hours |
| Phase 5: UI & Assets | 10-12 | 1-2 hours |
| Phase 6: Authorization | 13-14 | 2-3 hours |
| Phase 7: Enhancements | 15-17 | 3-4 hours |
| Phase 8: Testing | 18-20 | 3-4 hours |
| **TOTAL** | **20 tasks** | **25-33 hours** |

**Recommended Approach**: Complete phases sequentially, test after each phase.

---

## 🚀 Getting Started

To begin implementation, start with **Phase 1, Task 1**:

```bash
# Navigate to admin directory
cd admin

# Create migration
php artisan make:migration create_services_table

# Open migration file and add schema from this plan
# Then run migration
php artisan migrate

# Continue with Task 2 (Create Service Model)
php artisan make:model Service
```

---

**Last Updated**: November 14, 2025  
**Module Status**: 🔴 Ready to Start  
**Next Task**: Task 1 - Create Services Migration
