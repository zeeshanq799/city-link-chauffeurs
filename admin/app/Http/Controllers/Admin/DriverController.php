<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Driver::query()->withCount('vehicles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Verification status filter
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Availability filter
        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        // Sort by rating or trips
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $drivers = $query->paginate(15)->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'bio' => 'nullable|string|max:1000',
            
            // License Information
            'license_number' => 'required|string|unique:drivers,license_number',
            'license_expiry' => 'nullable|date|after:today',
            'experience_years' => 'required|integer|min:0|max:50',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            
            // Status
            'status' => 'required|in:active,inactive,suspended',
            'verification_status' => 'required|in:pending,verified,rejected',
            'is_available' => 'boolean',
            'background_check_status' => 'required|in:pending,passed,failed',
            
            // Media
            'profile_photo' => 'nullable|image|max:2048',
            'license_photo' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $driver = Driver::create($validated);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $driver->addMediaFromRequest('profile_photo')
                      ->toMediaCollection('profile_photos');
            }

            // Handle license photo upload
            if ($request->hasFile('license_photo')) {
                $driver->addMediaFromRequest('license_photo')
                      ->toMediaCollection('license_photos');
            }

            DB::commit();

            return redirect()->route('admin.drivers.index')
                           ->with('success', 'Driver created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                       ->with('error', 'Failed to create driver: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $driver->load(['vehicles', 'approvedBy']);
        
        // Get statistics
        $stats = [
            'total_trips' => $driver->total_trips,
            'total_earnings' => $driver->total_earnings,
            'average_rating' => $driver->rating_avg,
            'acceptance_rate' => $driver->acceptance_rate,
            'cancellation_rate' => $driver->cancellation_rate,
        ];

        return view('admin.drivers.show', compact('driver', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'bio' => 'nullable|string|max:1000',
            
            // License Information
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'license_expiry' => 'nullable|date|after:today',
            'experience_years' => 'required|integer|min:0|max:50',
            'languages' => 'nullable|array',
            'languages.*' => 'string|max:50',
            
            // Status
            'status' => 'required|in:active,inactive,suspended',
            'verification_status' => 'required|in:pending,verified,rejected',
            'is_available' => 'boolean',
            'background_check_status' => 'required|in:pending,passed,failed',
            
            // Media
            'profile_photo' => 'nullable|image|max:2048',
            'license_photo' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $driver->update($validated);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $driver->clearMediaCollection('profile_photos');
                $driver->addMediaFromRequest('profile_photo')
                      ->toMediaCollection('profile_photos');
            }

            // Handle license photo upload
            if ($request->hasFile('license_photo')) {
                $driver->clearMediaCollection('license_photos');
                $driver->addMediaFromRequest('license_photo')
                      ->toMediaCollection('license_photos');
            }

            DB::commit();

            return redirect()->route('admin.drivers.show', $driver)
                           ->with('success', 'Driver updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                       ->with('error', 'Failed to update driver: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        try {
            // Check if driver has assigned vehicles
            if ($driver->vehicles()->count() > 0) {
                return back()->with('error', 'Cannot delete driver with assigned vehicles.');
            }

            $driver->delete();

            return redirect()->route('admin.drivers.index')
                           ->with('success', 'Driver deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete driver: ' . $e->getMessage());
        }
    }

    /**
     * Verify or reject a driver.
     */
    public function verify(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
            'rejection_reason' => 'required_if:verification_status,rejected|nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $driver->update([
                'verification_status' => $validated['verification_status'],
                'approved_at' => $validated['verification_status'] === 'verified' ? now() : null,
                'approved_by' => $validated['verification_status'] === 'verified' ? Auth::id() : null,
            ]);

            // TODO: Send notification to driver

            DB::commit();

            $message = $validated['verification_status'] === 'verified' 
                ? 'Driver verified successfully.' 
                : 'Driver rejected.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update verification status: ' . $e->getMessage());
        }
    }

    /**
     * Toggle driver availability (online/offline).
     */
    public function toggleAvailability(Driver $driver)
    {
        try {
            $driver->update([
                'is_available' => !$driver->is_available
            ]);

            $status = $driver->is_available ? 'online' : 'offline';
            return back()->with('success', "Driver is now {$status}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to toggle availability: ' . $e->getMessage());
        }
    }

    /**
     * Assign vehicle to driver.
     */
    public function assignVehicle(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        DB::beginTransaction();
        try {
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

            // Check if vehicle is already assigned
            if ($vehicle->driver_id && $vehicle->driver_id !== $driver->id) {
                return back()->with('error', 'Vehicle is already assigned to another driver.');
            }

            // Assign vehicle to driver
            $vehicle->update(['driver_id' => $driver->id]);

            DB::commit();

            return back()->with('success', 'Vehicle assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign vehicle: ' . $e->getMessage());
        }
    }

    /**
     * Get available vehicles for assignment.
     */
    public function getAvailableVehicles()
    {
        $vehicles = Vehicle::whereNull('driver_id')
                          ->orWhere('driver_id', 0)
                          ->with('vehicleType')
                          ->get();

        return response()->json($vehicles);
    }
}
