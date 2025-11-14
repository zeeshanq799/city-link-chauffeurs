<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerController;

Route::get('/', function () {
    return redirect('/admin');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes - Protected by auth and admin middleware
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Vehicle Types
    Route::resource('vehicle-types', VehicleTypeController::class);
    Route::post('vehicle-types/{vehicleType}/toggle-status', [VehicleTypeController::class, 'toggleStatus'])
        ->name('vehicle-types.toggle-status');
    
    // Vehicles
    Route::resource('vehicles', VehicleController::class);
    Route::post('vehicles/{vehicle}/toggle-status', [VehicleController::class, 'toggleStatus'])
        ->name('vehicles.toggle-status');
    Route::post('vehicles/{vehicle}/update-mileage', [VehicleController::class, 'updateMileage'])
        ->name('vehicles.update-mileage');
    
    // Drivers
    Route::resource('drivers', DriverController::class);
    Route::post('drivers/{driver}/verify', [DriverController::class, 'verify'])
        ->name('drivers.verify');
    Route::post('drivers/{driver}/toggle-availability', [DriverController::class, 'toggleAvailability'])
        ->name('drivers.toggle-availability');
    Route::post('drivers/{driver}/assign-vehicle', [DriverController::class, 'assignVehicle'])
        ->name('drivers.assign-vehicle');
    Route::get('drivers/available-vehicles', [DriverController::class, 'getAvailableVehicles'])
        ->name('drivers.available-vehicles');
    
    // Role & Permission Management
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    
    // Customer Management
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])
        ->name('customers.toggle-status');
    Route::post('customers/{customer}/add-points', [CustomerController::class, 'addPoints'])
        ->name('customers.add-points');
    
});
