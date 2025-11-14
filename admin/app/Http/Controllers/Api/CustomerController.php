<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @group Customer Profile
 * 
 * APIs for managing customer profile
 */
class CustomerController extends Controller
{
    /**
     * Get profile
     * 
     * Retrieve the authenticated customer's profile details
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
     *     "date_of_birth": "1990-05-15",
     *     "preferred_payment_method": "card",
     *     "loyalty_points": 150,
     *     "is_active": true,
     *     "created_at": "2025-11-13T14:30:00.000000Z"
     *   }
     * }
     */
    public function show(Request $request)
    {
        return response()->json([
            'customer' => $request->user(),
        ]);
    }

    /**
     * Update profile
     * 
     * Update the authenticated customer's profile information
     * 
     * @authenticated
     * 
     * @bodyParam name string The customer's full name. Example: John Doe
     * @bodyParam phone string The customer's phone number. Example: +44 7700 900000
     * @bodyParam address string The customer's address. Example: 123 Main Street, London
     * @bodyParam date_of_birth date The customer's date of birth. Example: 1990-05-15
     * @bodyParam preferred_payment_method string Payment method preference. Example: card
     * @bodyParam current_password string Required if changing password. Example: OldPass123
     * @bodyParam password string New password (min 8 characters). Example: NewPass123
     * @bodyParam password_confirmation string New password confirmation. Example: NewPass123
     * 
     * @response 200 {
     *   "message": "Profile updated successfully",
     *   "customer": {
     *     "id": 1,
     *     "name": "John Doe Updated",
     *     "email": "john@example.com",
     *     "phone": "+44 7700 900001",
     *     "address": "456 New Street, London",
     *     "loyalty_points": 150
     *   }
     * }
     * 
     * @response 422 {
     *   "message": "The current password is incorrect.",
     *   "errors": {
     *     "current_password": ["The current password is incorrect."]
     *   }
     * }
     */
    public function update(Request $request)
    {
        $customer = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date|before:today',
            'preferred_payment_method' => 'nullable|in:cash,card,wallet',
            'current_password' => 'required_with:password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // If changing password, verify current password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return response()->json([
                    'message' => 'The current password is incorrect.',
                    'errors' => [
                        'current_password' => ['The current password is incorrect.']
                    ]
                ], 422);
            }
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password'], $validated['current_password']);
        }

        $customer->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'customer' => $customer->fresh(),
        ]);
    }

    /**
     * Delete account
     * 
     * Soft delete the authenticated customer's account
     * 
     * @authenticated
     * 
     * @bodyParam password string required Current password for confirmation. Example: SecurePass123
     * 
     * @response 200 {
     *   "message": "Account deleted successfully"
     * }
     * 
     * @response 422 {
     *   "message": "The password is incorrect.",
     *   "errors": {
     *     "password": ["The password is incorrect."]
     *   }
     * }
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $customer = $request->user();

        if (!Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'The password is incorrect.',
                'errors' => [
                    'password' => ['The password is incorrect.']
                ]
            ], 422);
        }

        // Soft delete the customer
        $customer->delete();

        // Revoke all tokens
        $customer->tokens()->delete();

        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }

    /**
     * Get saved addresses
     * 
     * Retrieve all saved addresses for the authenticated customer
     * 
     * @authenticated
     * 
     * @response 200 {
     *   "addresses": [
     *     {
     *       "id": 1,
     *       "label": "Home",
     *       "address": "123 Main Street, London",
     *       "postcode": "SW1A 1AA",
     *       "is_default": true
     *     },
     *     {
     *       "id": 2,
     *       "label": "Work",
     *       "address": "456 Office Lane, London",
     *       "postcode": "EC1A 1BB",
     *       "is_default": false
     *     }
     *   ]
     * }
     */
    public function addresses(Request $request)
    {
        // This will be implemented when SavedAddress model is created
        return response()->json([
            'addresses' => [],
            'message' => 'Saved addresses feature coming soon'
        ]);
    }

    /**
     * Save a new address
     * 
     * @authenticated
     * 
     * @bodyParam label string required Address label. Example: Home
     * @bodyParam address string required Full address. Example: 123 Main Street, London
     * @bodyParam postcode string required Postcode. Example: SW1A 1AA
     * @bodyParam is_default boolean Set as default address. Example: true
     * 
     * @response 201 {
     *   "message": "Address saved successfully",
     *   "address": {
     *     "id": 1,
     *     "label": "Home",
     *     "address": "123 Main Street, London",
     *     "postcode": "SW1A 1AA",
     *     "is_default": true
     *   }
     * }
     */
    public function storeAddress(Request $request)
    {
        // Will be implemented with SavedAddress model
        return response()->json([
            'message' => 'Feature coming soon'
        ], 501);
    }

    /**
     * Delete a saved address
     * 
     * @authenticated
     * 
     * @urlParam address integer required The address ID. Example: 1
     * 
     * @response 200 {
     *   "message": "Address deleted successfully"
     * }
     */
    public function destroyAddress(Request $request, $address)
    {
        // Will be implemented with SavedAddress model
        return response()->json([
            'message' => 'Feature coming soon'
        ], 501);
    }
}
