<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the vehicle types.
     */
    public function index()
    {
        $vehicleTypes = VehicleType::withCount('vehicles')->paginate(15);
        return view('admin.vehicle-types.index', compact('vehicleTypes'));
    }

    /**
     * Show the form for creating a new vehicle type.
     */
    public function create()
    {
        return view('admin.vehicle-types.create');
    }

    /**
     * Store a newly created vehicle type in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'per_km_price' => 'required|numeric|min:0',
            'per_hour_price' => 'required|numeric|min:0',
            'passenger_capacity' => 'required|integer|min:1|max:20',
            'luggage_capacity' => 'required|integer|min:0|max:10',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vehicle-types', 'public');
        }

        VehicleType::create($validated);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type created successfully.');
    }

    /**
     * Display the specified vehicle type.
     */
    public function show(VehicleType $vehicleType)
    {
        $vehicleType->loadCount('vehicles');
        return view('admin.vehicle-types.show', compact('vehicleType'));
    }

    /**
     * Show the form for editing the specified vehicle type.
     */
    public function edit(VehicleType $vehicleType)
    {
        return view('admin.vehicle-types.edit', compact('vehicleType'));
    }

    /**
     * Update the specified vehicle type in storage.
     */
    public function update(Request $request, VehicleType $vehicleType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'per_km_price' => 'required|numeric|min:0',
            'per_hour_price' => 'required|numeric|min:0',
            'passenger_capacity' => 'required|integer|min:1|max:20',
            'luggage_capacity' => 'required|integer|min:0|max:10',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($vehicleType->image) {
                Storage::disk('public')->delete($vehicleType->image);
            }
            $validated['image'] = $request->file('image')->store('vehicle-types', 'public');
        }

        $vehicleType->update($validated);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type updated successfully.');
    }

    /**
     * Remove the specified vehicle type from storage.
     */
    public function destroy(VehicleType $vehicleType)
    {
        if ($vehicleType->vehicles()->count() > 0) {
            return redirect()->route('admin.vehicle-types.index')
                ->with('error', 'Cannot delete vehicle type with associated vehicles.');
        }

        if ($vehicleType->image) {
            Storage::disk('public')->delete($vehicleType->image);
        }

        $vehicleType->delete();

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type deleted successfully.');
    }

    /**
     * Toggle the active status of a vehicle type.
     */
    public function toggleStatus(VehicleType $vehicleType)
    {
        $vehicleType->update(['is_active' => !$vehicleType->is_active]);

        return redirect()->route('admin.vehicle-types.index')
            ->with('success', 'Vehicle type status updated.');
    }
}
