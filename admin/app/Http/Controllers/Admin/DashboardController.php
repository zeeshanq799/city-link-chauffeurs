<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Driver;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'available_vehicles' => Vehicle::where('status', 'available')->count(),
            'in_service_vehicles' => Vehicle::where('status', 'in_service')->count(),
            'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
            'total_vehicle_types' => VehicleType::count(),
            'active_vehicle_types' => VehicleType::where('is_active', true)->count(),
            'total_drivers' => Driver::count(),
        ];

        // Recent vehicles
        $recentVehicles = Vehicle::with('vehicleType')
            ->latest()
            ->take(5)
            ->get();

        // Vehicles needing maintenance
        $maintenanceNeeded = Vehicle::whereNotNull('next_maintenance_mileage')
            ->whereRaw('mileage >= next_maintenance_mileage')
            ->with('vehicleType')
            ->take(5)
            ->get();

        // Vehicles with expiring insurance (within 30 days)
        $expiringInsurance = Vehicle::whereNotNull('insurance_expiry')
            ->whereDate('insurance_expiry', '<=', now()->addDays(30))
            ->whereDate('insurance_expiry', '>=', now())
            ->with('vehicleType')
            ->take(5)
            ->get();

        // Vehicles with expiring registration (within 30 days)
        $expiringRegistration = Vehicle::whereNotNull('registration_expiry')
            ->whereDate('registration_expiry', '<=', now()->addDays(30))
            ->whereDate('registration_expiry', '>=', now())
            ->with('vehicleType')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentVehicles',
            'maintenanceNeeded',
            'expiringInsurance',
            'expiringRegistration'
        ));
    }
}
