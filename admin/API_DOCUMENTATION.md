# City Link Chauffeurs API Documentation

Base URL: `http://your-domain.com/api/v1`

All API requests should include:
- `Accept: application/json`
- `Content-Type: application/json`

For authenticated endpoints, include:
- `Authorization: Bearer {your-token}`

---

## Authentication

### Register
Create a new customer account.

**Endpoint:** `POST /register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+44 7700 900000",
  "password": "SecurePass123",
  "password_confirmation": "SecurePass123"
}
```

**Success Response (201):**
```json
{
  "message": "Registration successful",
  "customer": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+44 7700 900000",
    "loyalty_points": 0,
    "created_at": "2025-11-13T14:30:00.000000Z"
  },
  "token": "1|abc123xyz..."
}
```

**Error Response (422):**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

---

### Login
Authenticate a customer and receive an access token.

**Endpoint:** `POST /login`

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "SecurePass123"
}
```

**Success Response (200):**
```json
{
  "message": "Login successful",
  "customer": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+44 7700 900000",
    "loyalty_points": 150,
    "created_at": "2025-11-13T14:30:00.000000Z"
  },
  "token": "2|xyz789abc..."
}
```

**Error Response (422):**
```json
{
  "message": "The provided credentials are incorrect.",
  "errors": {
    "email": ["The provided credentials are incorrect."]
  }
}
```

---

### Logout
Revoke the current authentication token.

**Endpoint:** `POST /logout`

**Authentication:** Required

**Success Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

---

### Get Current User
Retrieve the authenticated customer's details.

**Endpoint:** `GET /me`

**Authentication:** Required

**Success Response (200):**
```json
{
  "customer": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+44 7700 900000",
    "address": "123 Main Street, London",
    "loyalty_points": 150,
    "preferred_payment_method": "card",
    "created_at": "2025-11-13T14:30:00.000000Z"
  }
}
```

---

## Customer Profile

### Get Profile
Retrieve the authenticated customer's profile.

**Endpoint:** `GET /profile`

**Authentication:** Required

**Success Response (200):**
```json
{
  "customer": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+44 7700 900000",
    "address": "123 Main Street, London",
    "date_of_birth": "1990-05-15",
    "preferred_payment_method": "card",
    "loyalty_points": 150,
    "is_active": true,
    "created_at": "2025-11-13T14:30:00.000000Z"
  }
}
```

---

### Update Profile
Update the authenticated customer's profile.

**Endpoint:** `PUT /profile`

**Authentication:** Required

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "phone": "+44 7700 900001",
  "address": "456 New Street, London",
  "date_of_birth": "1990-05-15",
  "preferred_payment_method": "card"
}
```

**To change password, include:**
```json
{
  "current_password": "OldPass123",
  "password": "NewPass123",
  "password_confirmation": "NewPass123"
}
```

**Success Response (200):**
```json
{
  "message": "Profile updated successfully",
  "customer": {
    "id": 1,
    "name": "John Doe Updated",
    "email": "john@example.com",
    "phone": "+44 7700 900001",
    "address": "456 New Street, London",
    "loyalty_points": 150
  }
}
```

---

### Delete Account
Permanently delete the customer account (soft delete).

**Endpoint:** `DELETE /account`

**Authentication:** Required

**Request Body:**
```json
{
  "password": "SecurePass123"
}
```

**Success Response (200):**
```json
{
  "message": "Account deleted successfully"
}
```

---

## Vehicles

### List Vehicles
Get all available vehicles.

**Endpoint:** `GET /vehicles`

**Query Parameters:**
- `type` (optional): Filter by vehicle type ID
- `available` (optional): Filter available vehicles (boolean)
- `per_page` (optional): Results per page (default: 15)

**Example:** `GET /vehicles?type=1&available=true&per_page=10`

**Success Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "vehicle_type": {
        "id": 1,
        "name": "Luxury Sedan",
        "base_price": 50.00,
        "price_per_mile": 2.50
      },
      "make": "Mercedes-Benz",
      "model": "S-Class",
      "year": 2024,
      "registration_number": "ABC 123",
      "color": "Black",
      "passenger_capacity": 4,
      "status": "available",
      "features": ["Leather seats", "Wi-Fi", "Climate control"]
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 25
  }
}
```

---

### Get Vehicle Details
Get detailed information about a specific vehicle.

**Endpoint:** `GET /vehicles/{id}`

**Success Response (200):**
```json
{
  "vehicle": {
    "id": 1,
    "vehicle_type": {
      "id": 1,
      "name": "Luxury Sedan",
      "base_price": 50.00,
      "price_per_mile": 2.50,
      "description": "Premium luxury sedan for executive travel"
    },
    "make": "Mercedes-Benz",
    "model": "S-Class",
    "year": 2024,
    "registration_number": "ABC 123",
    "color": "Black",
    "passenger_capacity": 4,
    "luggage_capacity": 3,
    "status": "available",
    "features": ["Leather seats", "Wi-Fi", "Climate control"],
    "images": [
      "https://example.com/images/vehicle-1.jpg"
    ]
  }
}
```

---

### Get Vehicle Types
Get all vehicle types with pricing.

**Endpoint:** `GET /vehicle-types`

**Success Response (200):**
```json
{
  "vehicle_types": [
    {
      "id": 1,
      "name": "Luxury Sedan",
      "base_price": 50.00,
      "price_per_mile": 2.50,
      "price_per_hour": 60.00,
      "passenger_capacity": 4,
      "luggage_capacity": 3,
      "description": "Premium luxury sedan for executive travel",
      "features": ["Leather seats", "Wi-Fi", "Climate control"],
      "available_count": 5
    },
    {
      "id": 2,
      "name": "SUV",
      "base_price": 70.00,
      "price_per_mile": 3.00,
      "price_per_hour": 80.00,
      "passenger_capacity": 6,
      "luggage_capacity": 5,
      "description": "Spacious SUV for family travel",
      "features": ["Spacious interior", "GPS", "Child seats available"],
      "available_count": 3
    }
  ]
}
```

---

## Bookings

### List Bookings
Get all bookings for the authenticated customer.

**Endpoint:** `GET /bookings`

**Authentication:** Required

**Query Parameters:**
- `status` (optional): Filter by status (upcoming, completed, cancelled)
- `per_page` (optional): Results per page (default: 15)

**Success Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "booking_reference": "BK-2025-0001",
      "vehicle": {
        "id": 1,
        "make": "Mercedes-Benz",
        "model": "S-Class",
        "type": "Luxury Sedan"
      },
      "pickup_location": "Heathrow Airport",
      "dropoff_location": "Central London Hotel",
      "pickup_datetime": "2025-11-20 14:30:00",
      "status": "upcoming",
      "total_amount": 125.50,
      "payment_status": "paid"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 10
  }
}
```

---

### Create Booking
Create a new booking.

**Endpoint:** `POST /bookings`

**Authentication:** Required

**Request Body:**
```json
{
  "vehicle_type_id": 1,
  "pickup_location": "Heathrow Airport",
  "dropoff_location": "Central London Hotel",
  "pickup_datetime": "2025-11-20 14:30:00",
  "passenger_count": 2,
  "luggage_count": 3,
  "notes": "Flight arrives at 14:00",
  "payment_method": "card"
}
```

**Success Response (201):**
```json
{
  "message": "Booking created successfully",
  "booking": {
    "id": 1,
    "booking_reference": "BK-2025-0001",
    "vehicle_type": {
      "id": 1,
      "name": "Luxury Sedan"
    },
    "pickup_location": "Heathrow Airport",
    "dropoff_location": "Central London Hotel",
    "pickup_datetime": "2025-11-20 14:30:00",
    "passenger_count": 2,
    "total_amount": 125.50,
    "status": "pending",
    "payment_status": "pending"
  }
}
```

---

### Get Booking Details
Get detailed information about a specific booking.

**Endpoint:** `GET /bookings/{id}`

**Authentication:** Required

**Success Response (200):**
```json
{
  "booking": {
    "id": 1,
    "booking_reference": "BK-2025-0001",
    "vehicle": {
      "id": 5,
      "make": "Mercedes-Benz",
      "model": "S-Class",
      "registration_number": "ABC 123"
    },
    "driver": {
      "id": 3,
      "name": "James Smith",
      "phone": "+44 7700 900111"
    },
    "pickup_location": "Heathrow Airport",
    "dropoff_location": "Central London Hotel",
    "pickup_datetime": "2025-11-20 14:30:00",
    "status": "confirmed",
    "total_amount": 125.50,
    "payment_status": "paid",
    "notes": "Flight arrives at 14:00"
  }
}
```

---

### Cancel Booking
Cancel a booking.

**Endpoint:** `POST /bookings/{id}/cancel`

**Authentication:** Required

**Request Body (optional):**
```json
{
  "cancellation_reason": "Plans changed"
}
```

**Success Response (200):**
```json
{
  "message": "Booking cancelled successfully",
  "booking": {
    "id": 1,
    "booking_reference": "BK-2025-0001",
    "status": "cancelled",
    "cancelled_at": "2025-11-13T15:00:00.000000Z"
  }
}
```

---

### Rate Booking
Rate a completed booking.

**Endpoint:** `POST /bookings/{id}/rate`

**Authentication:** Required

**Request Body:**
```json
{
  "rating": 5,
  "review": "Excellent service, professional driver!"
}
```

**Success Response (200):**
```json
{
  "message": "Rating submitted successfully",
  "rating": {
    "booking_id": 1,
    "rating": 5,
    "review": "Excellent service, professional driver!",
    "created_at": "2025-11-13T15:30:00.000000Z"
  }
}
```

---

## Saved Addresses

### List Saved Addresses
Get all saved addresses for the customer.

**Endpoint:** `GET /saved-addresses`

**Authentication:** Required

**Success Response (200):**
```json
{
  "addresses": [
    {
      "id": 1,
      "label": "Home",
      "address": "123 Main Street, London",
      "postcode": "SW1A 1AA",
      "is_default": true
    },
    {
      "id": 2,
      "label": "Work",
      "address": "456 Office Lane, London",
      "postcode": "EC1A 1BB",
      "is_default": false
    }
  ]
}
```

---

### Save Address
Save a new address.

**Endpoint:** `POST /saved-addresses`

**Authentication:** Required

**Request Body:**
```json
{
  "label": "Home",
  "address": "123 Main Street, London",
  "postcode": "SW1A 1AA",
  "is_default": true
}
```

**Success Response (201):**
```json
{
  "message": "Address saved successfully",
  "address": {
    "id": 1,
    "label": "Home",
    "address": "123 Main Street, London",
    "postcode": "SW1A 1AA",
    "is_default": true
  }
}
```

---

### Delete Address
Delete a saved address.

**Endpoint:** `DELETE /saved-addresses/{id}`

**Authentication:** Required

**Success Response (200):**
```json
{
  "message": "Address deleted successfully"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "This action is unauthorized."
}
```

### Not Found (404)
```json
{
  "message": "Resource not found."
}
```

### Server Error (500)
```json
{
  "message": "Server Error",
  "error": "An unexpected error occurred."
}
```

---

## Rate Limiting

API requests are rate-limited to:
- **60 requests per minute** for authenticated users
- **30 requests per minute** for guest users

When rate limit is exceeded, you'll receive a `429 Too Many Requests` response:

```json
{
  "message": "Too Many Attempts."
}
```

---

## Next.js Integration Example

```typescript
// api/client.ts
import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://your-laravel-api.com/api/v1',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add auth token to requests
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default apiClient;

// Usage example
export const authService = {
  async register(data) {
    const response = await apiClient.post('/register', data);
    localStorage.setItem('auth_token', response.data.token);
    return response.data;
  },
  
  async login(email, password) {
    const response = await apiClient.post('/login', { email, password });
    localStorage.setItem('auth_token', response.data.token);
    return response.data;
  },
  
  async logout() {
    await apiClient.post('/logout');
    localStorage.removeItem('auth_token');
  },
  
  async getProfile() {
    const response = await apiClient.get('/profile');
    return response.data.customer;
  },
};

export const bookingService = {
  async getBookings(params = {}) {
    const response = await apiClient.get('/bookings', { params });
    return response.data;
  },
  
  async createBooking(data) {
    const response = await apiClient.post('/bookings', data);
    return response.data;
  },
  
  async cancelBooking(id, reason) {
    const response = await apiClient.post(`/bookings/${id}/cancel`, { cancellation_reason: reason });
    return response.data;
  },
};
```

---

## Testing with cURL

### Register
```bash
curl -X POST http://your-domain.com/api/v1/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+44 7700 900000",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123"
  }'
```

### Login
```bash
curl -X POST http://your-domain.com/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePass123"
  }'
```

### Get Profile (with auth)
```bash
curl -X GET http://your-domain.com/api/v1/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

**Last Updated:** November 13, 2025  
**API Version:** 1.0
