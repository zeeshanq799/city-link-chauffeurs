<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 * 
 * APIs for customer authentication
 */
class AuthController extends Controller
{
    /**
     * Register a new customer
     * 
     * Create a new customer account and return authentication token
     * 
     * @bodyParam name string required The customer's full name. Example: John Doe
     * @bodyParam email string required The customer's email address. Example: john@example.com
     * @bodyParam phone string The customer's phone number. Example: +44 7700 900000
     * @bodyParam password string required The password (min 8 characters). Example: SecurePass123
     * @bodyParam password_confirmation string required Password confirmation. Example: SecurePass123
     * 
     * @response 201 {
     *   "message": "Registration successful",
     *   "customer": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "phone": "+44 7700 900000",
     *     "loyalty_points": 0,
     *     "created_at": "2025-11-13T14:30:00.000000Z"
     *   },
     *   "token": "1|abc123xyz..."
     * }
     * 
     * @response 422 {
     *   "message": "The email has already been taken.",
     *   "errors": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $customer->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'loyalty_points' => $customer->loyalty_points,
                'created_at' => $customer->created_at,
            ],
            'token' => $token,
        ], 201);
    }

    /**
     * Login
     * 
     * Authenticate a customer and return token
     * 
     * @bodyParam email string required The customer's email. Example: john@example.com
     * @bodyParam password string required The customer's password. Example: SecurePass123
     * 
     * @response 200 {
     *   "message": "Login successful",
     *   "customer": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "phone": "+44 7700 900000",
     *     "loyalty_points": 150,
     *     "created_at": "2025-11-13T14:30:00.000000Z"
     *   },
     *   "token": "2|xyz789abc..."
     * }
     * 
     * @response 422 {
     *   "message": "The provided credentials are incorrect."
     * }
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$customer->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated. Please contact support.'],
            ]);
        }

        // Revoke all previous tokens
        $customer->tokens()->delete();

        $token = $customer->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'loyalty_points' => $customer->loyalty_points,
                'created_at' => $customer->created_at,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Logout
     * 
     * Revoke the customer's current authentication token
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "message": "Logged out successfully"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated customer
     * 
     * Retrieve the currently authenticated customer's details
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "customer": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "phone": "+44 7700 900000",
     *     "address": "123 Main Street, London",
     *     "loyalty_points": 150,
     *     "preferred_payment_method": "card",
     *     "created_at": "2025-11-13T14:30:00.000000Z"
     *   }
     * }
     */
    public function me(Request $request)
    {
        return response()->json([
            'customer' => $request->user(),
        ]);
    }
}
