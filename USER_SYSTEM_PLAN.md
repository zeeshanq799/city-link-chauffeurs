# City Link Chauffeurs - User Profile & Booking Management System

## Project Overview
A comprehensive user management and booking system for City Link Chauffeurs with 12+ integrated pages covering profile management, booking history, payments, ratings, and support.

---

## PHASE 1: CORE USER MANAGEMENT PAGES

### 1. **DASHBOARD.HTML** - User Hub & Overview
**Location:** `/dashboard.html`
**Purpose:** Central hub for all user activities

**Key Sections:**
- **Header**
  - Welcome greeting with user's name
  - Quick profile access
  - Notification bell with count
  - Settings menu

- **Stats Cards** (4 cards in row)
  - Total Rides Count
  - Total Amount Spent
  - Preferred Driver
  - Member Since Date

- **Recent Bookings Section**
  - Display last 3-5 bookings
  - Each card shows: Date, Service Type, Price, Status
  - Link to "View All Bookings"

- **Upcoming Trips Widget**
  - Next scheduled ride countdown
  - Driver info if assigned
  - Pickup location & time
  - Quick "View Trip" button

- **Quick Actions** (Button Grid)
  - Book Now → Direct to booking page
  - My Bookings → my-bookings.html
  - Account Settings → account-settings.html
  - Saved Addresses → saved-addresses.html

---

### 2. **PROFILE.HTML** - User Account & Personal Information
**Location:** `/profile.html`
**Purpose:** Manage personal profile information

**Key Sections:**
- **Profile Header**
  - Avatar/Profile photo with upload button
  - Name display
  - Member status badge (Silver/Gold/Platinum)
  - Edit profile button

- **Personal Information Tab**
  - First Name & Last Name (with edit)
  - Email Address (verified badge)
  - Phone Number (verified badge)
  - Date of Birth
  - Gender (optional)
  - Edit button with save/cancel

- **Address Section**
  - Current address
  - Add/Edit address button
  - Link to saved-addresses.html

- **Preferences Tab**
  - Preferred vehicle type (dropdown)
  - Preferred payment method
  - Ride preferences (AC/Non-AC, Music, Temperature)
  - Language preference
  - Save preferences button

- **Account Status**
  - Account creation date
  - Total trips
  - Average rating
  - Member tier

---

### 3. **MY-BOOKINGS.HTML** - Booking History & Management
**Location:** `/my-bookings.html`
**Purpose:** View and manage all user bookings

**Key Features:**
- **Navigation Tabs**
  - Upcoming (Next 7 days)
  - Active (In Progress)
  - Past (Completed)
  - Cancelled

- **Booking Cards** (Each card contains)
  - Booking ID
  - Date & Time
  - Service Type (Airport, Corporate, Hourly, Point-to-Point)
  - Pickup & Drop Location
  - Vehicle & Driver (if assigned)
  - Price
  - Status Badge (Confirmed, In Progress, Completed, Cancelled)
  
- **Card Actions**
  - View Details → booking-details.html
  - Cancel Booking (if applicable)
  - Reschedule (if upcoming)
  - Rate Driver (if completed)
  - Download Invoice (if completed)
  - Report Issue

- **Filters & Search**
  - Date range picker
  - Service type filter
  - Status filter
  - Search by booking ID

---

### 4. **BOOKING-DETAILS.HTML** - Trip Information & Status
**Location:** `/booking-details.html`
**Purpose:** Complete booking details and trip information

**Key Sections:**
- **Trip Overview**
  - Booking ID
  - Status with timeline
  - Booking date & time
  - Estimated duration & distance

- **Pickup & Dropoff Details**
  - Full addresses
  - Time windows
  - Special instructions (if any)

- **Driver Information**
  - Driver photo
  - Name & rating
  - Vehicle details
  - License/Verification badge
  - Contact button (if ride in progress)

- **Vehicle Details**
  - Vehicle type & model
  - License plate
  - Color
  - Features (AC, WiFi, Charging, etc.)

- **Route Map**
  - Interactive map showing route
  - Pickup & dropoff markers
  - Estimated time & distance
  - Live tracking (if in progress)

- **Pricing Breakdown**
  - Base fare
  - Distance charges
  - Time charges
  - Tolls/Parking (if applicable)
  - Discounts/Promo applied
  - Total fare
  - Payment method used

- **Status Timeline**
  - Booking confirmed
  - Driver assigned
  - Ride started
  - Ride completed
  - Payment processed

- **Actions**
  - Cancel booking (if applicable)
  - Contact driver
  - Rate driver (if completed)
  - Download receipt
  - Report issue
  - Call support

---

## PHASE 2: ADDRESSES & PREFERENCES

### 5. **SAVED-ADDRESSES.HTML** - Favorite Locations
**Location:** `/saved-addresses.html`
**Purpose:** Store and manage frequently used addresses

**Key Sections:**
- **Saved Addresses List**
  - Home address card
  - Work address card
  - Other locations cards
  - Each card shows:
    - Location name/label
    - Full address
    - Edit & Delete buttons
    - Set as default checkbox

- **Address Card Details**
  - Address label (Home, Work, Gym, Restaurant, etc.)
  - Street address
  - Apartment/Building number
  - City, State, ZIP
  - Special instructions (Gate code, building name, etc.)
  - Set as pickup default
  - Set as dropoff default

- **Add New Address**
  - Form with fields:
    - Address label
    - Street address with autocomplete
    - Apartment/Suite/Building number
    - City, State, ZIP
    - Special instructions
    - Save button
  - Map preview of location
  - Confirm location button

- **Edit Address**
  - Pre-filled form with current details
  - Update button
  - Delete option
  - Cancel button

---

## PHASE 4: RATINGS & SUPPORT

### 6. **RATINGS-REVIEWS.HTML** - User Feedback History
**Location:** `/ratings-reviews.html`
**Purpose:** View and manage all user ratings and reviews

**Key Sections:**
- **Rating Summary**
  - Average rating (out of 5 stars)
  - Total reviews count
  - Rating distribution (5★, 4★, 3★, 2★, 1★ with percentages)

- **Filters**
  - Sort by: Recent, Highest Rating, Lowest Rating
  - Filter by rating
  - Date range filter

- **Reviews List**
  - Each review card shows:
    - Driver/Vehicle photo
    - Driver name
    - Trip date
    - Star rating
    - Review text (truncated)
    - View Full button

- **Review Details Modal**
  - Full review text
  - Photo gallery (if included)
  - Edit button (if within edit window)
  - Delete button
  - Report button (for inappropriate content)

- **Driver Ratings Section**
  - Display ratings received from drivers for this user
  - Date, Rating, Driver name
  - Comments from drivers

---

### 7. **RATE-DRIVER.HTML** - Post-Ride Feedback Form
**Location:** `/rate-driver.html`
**Purpose:** Rate and review driver after completed ride

**Key Sections:**
- **Ride Summary**
  - Driver photo & name
  - Vehicle info
  - Trip date & time
  - Route summary

- **Star Rating**
  - 5-star interactive rating system
  - Hover to preview rating
  - Click to select rating
  - Display selected rating

- **Rating Categories** (Optional)
  - Driving Skills (5 stars)
  - Vehicle Cleanliness (5 stars)
  - Professionalism (5 stars)
  - Communication (5 stars)

- **Review Section**
  - Text area for written review
  - Character count (max 500)
  - Predefined tags/quick comments
  - Sample comments for reference

- **Photo Upload** (Optional)
  - Upload photo of vehicle/driver
  - Photo preview
  - Remove button

- **Tip Option**
  - Tip amount quick selection (10%, 15%, 20%, Custom)
  - Payment method selector
  - Add tip button

- **Report Issue** (If needed)
  - Checkbox: "Report an issue"
  - Issue type dropdown (Driver behavior, Vehicle condition, etc.)
  - Report details text area

- **Action Buttons**
  - Submit Rating button
  - Cancel button
  - Skip for now button

---

## PHASE 4: ACCOUNT & SETTINGS

### 8. **ACCOUNT-SETTINGS.HTML** - User Preferences & Security
**Location:** `/account-settings.html`
**Purpose:** Manage account settings and preferences

**Key Sections:**
- **Profile Settings Tab**
  - Name edit (First & Last name)
  - Email change
  - Phone number change
  - Email verification status
  - Phone verification status
  - Verify buttons

- **Security Tab**
  - Current password verification
  - Change password form:
    - Current password
    - New password
    - Confirm new password
    - Password strength indicator
    - Update button
  - Two-factor authentication (enable/disable)
  - Active sessions list
  - Logout all other sessions button

- **Notifications Tab**
  - Email notifications (checkbox options)
    - Booking confirmations
    - Ride reminders
    - Promotions
    - News & updates
  - SMS notifications
  - Push notifications
  - In-app notifications
  - Save preferences

- **Privacy Tab**
  - Profile visibility (Public/Private)
  - Share ride data with partners (checkbox)
  - Personalized ads (checkbox)
  - Data collection preferences
  - Download my data button (GDPR)
  - Delete account button with confirmation

- **Preferences Tab**
  - Language selection
  - Theme (Light/Dark mode)
  - Currency selection
  - Distance unit (Miles/Kilometers)
  - Timezone
  - Default ride type
  - Save preferences button

- **Connected Devices Tab**
  - List of devices logged in
  - Device info (type, browser, OS)
  - Last active
  - Logout button for each device
  - Logout all devices

---

### 9. **BOOKING-CONFIRMATION.HTML** - Order Confirmation Page
**Location:** `/booking-confirmation.html`
**Purpose:** Display booking confirmation after booking is placed

**Key Sections:**
- **Confirmation Header**
  - Success checkmark/badge
  - "Booking Confirmed" message
  - Booking reference number (copy button)
  - Booking time

- **Trip Summary**
  - Service type
  - Pickup location & time
  - Dropoff location
  - Estimated fare
  - Estimated duration

- **Driver Assignment** (Dynamic)
  - If driver assigned:
    - Driver photo
    - Driver name & rating
    - Vehicle info & plate
    - Estimated arrival time
    - Vehicle location on map
  - If not assigned:
    - "Searching for driver..." message
    - Real-time search status

- **Booking Details**
  - Passenger name
  - Contact number
  - Special requests (if any)
  - Payment method

- **Map**
  - Route preview (if available)
  - Pickup and dropoff markers
  - Estimated route

- **Action Buttons**
  - View Booking Details → booking-details.html
  - Download Booking Confirmation (PDF)
  - Share Booking (SMS/Email/Whatsapp)
  - Add to Calendar
  - Continue Shopping / Book Another Ride

- **Helpful Links**
  - FAQs
  - Customer support
  - Cancellation policy

---

## ADDITIONAL COMPONENTS

### 10. **User Menu for Navbar**
**Pages Affected:** All pages with navbar
**Features:**
- Dropdown menu with user profile photo
- Profile
- My Bookings
- Dashboard
- Account Settings
- Logout button

---

## DATABASE SCHEMA (Reference)

### Users Table
```
- user_id (Primary Key)
- first_name, last_name
- email, phone
- date_of_birth
- gender
- profile_photo_url
- home_address, work_address
- preferences (JSON)
- rating_avg
- total_trips
- member_since
- status (active, suspended, deleted)
```

### Bookings Table
```
- booking_id
- user_id (Foreign Key)
- service_type
- pickup_location, dropoff_location
- pickup_time, estimated_arrival_time
- actual_arrival_time, completion_time
- driver_id (nullable)
- vehicle_id (nullable)
- distance, duration
- base_fare, service_charge, toll, discount
- total_fare
- payment_method_id
- status (confirmed, cancelled, completed, no-show)
- special_requests
- created_at, updated_at
```

### Payments Table
```
- payment_id
- booking_id
- user_id
- payment_method_id
- amount
- status (pending, completed, failed, refunded)
- transaction_id (from payment gateway)
- timestamp
```

### Ratings Table
```
- rating_id
- booking_id
- from_user_id (reviewer)
- to_driver_id / to_user_id
- rating_value (1-5)
- category_ratings (JSON - if multiple categories)
- review_text
- photo_urls (array)
- helpful_count
- created_at, updated_at
```

### Addresses Table
```
- address_id
- user_id
- label (Home, Work, Other)
- street_address
- city, state, zip
- latitude, longitude
- special_instructions
- is_default_pickup
- is_default_dropoff
- created_at, updated_at
```

---

## NAVIGATION STRUCTURE

```
User Dashboard (After Login)
├── Dashboard (Hub)
├── Profile
├── My Bookings
│   ├── Booking Details
│   ├── Rate Driver
│   └── Booking Confirmation
├── Addresses (Saved Addresses)
├── Ratings & Reviews
└── Account Settings
    ├── Profile Settings
    ├── Security
    ├── Notifications
    ├── Privacy
    └── Preferences
```

---

## RECOMMENDED IMPLEMENTATION ORDER

**Phase 1 (Foundation):**
1. Dashboard.html - Start here
2. Profile.html
3. My-bookings.html
4. Booking-details.html

**Phase 2 (Addresses):**
5. Saved-addresses.html

**Phase 3 (Feedback):**
6. Ratings-reviews.html
7. Rate-driver.html

**Phase 4 (Settings & Confirmation):**
8. Account-settings.html
9. Booking-confirmation.html
10. Navbar Integration - Add user menu to all pages

---

## TECHNICAL REQUIREMENTS

### Frontend Technologies:
- HTML5, CSS3, Bootstrap 5
- JavaScript (vanilla or jQuery)
- AOS animations for scroll effects
- Font Awesome icons
- Chart.js or similar for statistics

### Backend Integration Needed:
- User authentication (JWT or sessions)
- API endpoints for all CRUD operations
- Real-time updates (WebSocket for live tracking)
- Payment gateway integration
- Email/SMS notifications
- Map API integration (Google Maps or similar)

### Third-party Services:
- Payment gateway (Stripe, PayPal, etc.)
- Email service (SendGrid, Mailgun)
- SMS service (Twilio, AWS SNS)
- Map service (Google Maps, Mapbox)
- Chat service (Firebase, Zendesk)

---

## Security Considerations

1. **Authentication:** Implement proper login/logout and session management
2. **Authorization:** Role-based access control (User, Driver, Admin, Support)
3. **Data Protection:** Encrypt sensitive data (credit cards, SSN)
4. **Password Security:** Hash passwords, implement password recovery
5. **HTTPS:** Use SSL/TLS for all communications
6. **CSRF Protection:** Implement CSRF tokens in forms
7. **Input Validation:** Validate and sanitize all user inputs
8. **Rate Limiting:** Prevent brute force attacks
9. **Audit Logging:** Log all sensitive operations

---

## Performance Optimization

1. Lazy load images
2. Implement pagination for lists
3. Cache frequently accessed data
4. Minimize CSS/JS files
5. Use CDN for static assets
6. Implement service workers for offline support
7. Optimize database queries with indexes

---

## Responsive Design

- Mobile-first approach
- Bootstrap 5 breakpoints
- Touch-friendly buttons (48px minimum)
- Responsive tables/cards
- Mobile-optimized navigation
- Viewport optimization

---

## Accessibility

- WCAG 2.1 AA compliance
- Proper heading hierarchy
- Alt text for images
- Color contrast ratios
- Keyboard navigation support
- Screen reader compatibility

---

**Last Updated:** November 11, 2025
**Status:** Planning Phase - Ready for Development
