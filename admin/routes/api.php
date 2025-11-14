<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Public vehicle listings
    Route::get('/vehicles', [VehicleController::class, 'index']);
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
    Route::get('/vehicle-types', [VehicleController::class, 'types']);
});

// Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Customer profile
    Route::get('/profile', [CustomerController::class, 'show']);
    Route::put('/profile', [CustomerController::class, 'update']);
    Route::delete('/account', [CustomerController::class, 'destroy']);
    
    // Bookings
    Route::apiResource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/rate', [BookingController::class, 'rate']);
    
    // Saved addresses
    Route::get('/saved-addresses', [CustomerController::class, 'addresses']);
    Route::post('/saved-addresses', [CustomerController::class, 'storeAddress']);
    Route::delete('/saved-addresses/{address}', [CustomerController::class, 'destroyAddress']);
});
