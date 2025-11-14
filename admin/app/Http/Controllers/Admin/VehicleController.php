<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index(Request $request)
    {
        $query = Vehicle::with(['vehicleType', 'driver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vehicle type
        if ($request->filled('vehicle_type_id')) {
            $query->where('vehicle_type_id', $request->vehicle_type_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('vin', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->paginate(15)->withQueryString();
        $vehicleTypes = VehicleType::active()->get();

        return view('admin.vehicles.index', compact('vehicles', 'vehicleTypes'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        $vehicleTypes = VehicleType::active()->get();
        $drivers = Driver::all();
        
        return view('admin.vehicles.create', compact('vehicleTypes', 'drivers'));
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'license_plate' => 'required|string|unique:vehicles,license_plate|max:255',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (now()->year + 1),
            'color' => 'nullable|string|max:255',
            'vin' => 'nullable|string|unique:vehicles,vin|max:255',
            'registration_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'insurance_policy_number' => 'nullable|string|max:255',
            'mileage' => 'required|integer|min:0',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_mileage' => 'nullable|integer|min:0',
            'maintenance_notes' => 'nullable|string',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'status' => 'required|in:available,in_service,maintenance,out_of_service,retired',
            'is_active' => 'boolean',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vehicle = Vehicle::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $vehicle->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['vehicleType', 'driver']);
        
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle)
    {
        $vehicleTypes = VehicleType::active()->get();
        $drivers = Driver::all();
        
        return view('admin.vehicles.edit', compact('vehicle', 'vehicleTypes', 'drivers'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'license_plate' => 'required|string|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1990|max:' . (now()->year + 1),
            'color' => 'nullable|string|max:255',
            'vin' => 'nullable|string|max:255|unique:vehicles,vin,' . $vehicle->id,
            'registration_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'insurance_policy_number' => 'nullable|string|max:255',
            'mileage' => 'required|integer|min:0',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_mileage' => 'nullable|integer|min:0',
            'maintenance_notes' => 'nullable|string',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'status' => 'required|in:available,in_service,maintenance,out_of_service,retired',
            'is_active' => 'boolean',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $vehicle->update($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $vehicle->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->clearMediaCollection('images');
        $vehicle->clearMediaCollection('documents');
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Update vehicle mileage.
     */
    public function updateMileage(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'mileage' => 'required|integer|min:' . $vehicle->mileage,
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.show', $vehicle)
            ->with('success', 'Mileage updated successfully.');
    }

    /**
     * Toggle the active status of a vehicle.
     */
    public function toggleStatus(Vehicle $vehicle)
    {
        $vehicle->update(['is_active' => !$vehicle->is_active]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle status updated.');
    }
}
