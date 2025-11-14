<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by featured
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        // Filter by pricing type
        if ($request->filled('pricing_type') && $request->pricing_type !== 'all') {
            $query->where('pricing_type', $request->pricing_type);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $services = $query->paginate(25)->withQueryString();

        // Calculate stats
        $total = Service::count();
        $active = Service::where('is_active', true)->count();
        
        $stats = [
            'total' => $total,
            'active' => $active,
            'active_percentage' => $total > 0 ? round(($active / $total) * 100, 1) : 0,
            'featured' => Service::where('is_featured', true)->count(),
            'total_revenue' => Service::sum('total_revenue'),
        ];

        // Categories for filter
        $categories = [
            'point-to-point' => 'Point to Point',
            'hourly-charter' => 'Hourly Charter',
            'airport-transfer' => 'Airport Transfer',
            'corporate' => 'Corporate',
            'events' => 'Events',
            'tours' => 'Tours',
        ];

        // Pricing types for filter
        $pricingTypes = [
            'flat_rate' => 'Flat Rate',
            'hourly' => 'Hourly',
            'distance_based' => 'Distance Based',
            'custom' => 'Custom',
            'tiered' => 'Tiered',
        ];

        return view('admin.services.index', compact('services', 'stats', 'categories', 'pricingTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'point-to-point' => 'Point to Point',
            'hourly-charter' => 'Hourly Charter',
            'airport-transfer' => 'Airport Transfer',
            'corporate' => 'Corporate',
            'events' => 'Events',
            'tours' => 'Tours',
        ];

        $pricingTypes = [
            'flat_rate' => 'Flat Rate',
            'hourly' => 'Hourly',
            'distance_based' => 'Distance Based',
            'custom' => 'Custom',
            'tiered' => 'Tiered',
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.services.create', compact('categories', 'pricingTypes', 'daysOfWeek'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'slug' => 'nullable|string|max:255|unique:services,slug',
            'category' => 'required|in:point-to-point,hourly-charter,airport-transfer,corporate,events,tours',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'pricing_type' => 'required|in:flat_rate,hourly,distance_based,custom,tiered',
            'base_price' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'per_mile_rate' => 'nullable|numeric|min:0',
            'min_hours' => 'nullable|integer|min:0',
            'max_hours' => 'nullable|integer|min:0',
            'advance_booking_hours' => 'nullable|integer|min:0',
            'max_passengers' => 'nullable|integer|min:1',
            'max_luggage' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'airport_service' => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle JSON fields
        if ($request->has('available_days')) {
            $validated['available_days'] = $request->available_days;
        }

        if ($request->has('service_areas')) {
            $validated['service_areas'] = $request->service_areas;
        }

        if ($request->has('supported_airports')) {
            $validated['supported_airports'] = $request->supported_airports;
        }

        if ($request->has('vehicle_types')) {
            $validated['vehicle_types'] = $request->vehicle_types;
        }

        if ($request->has('amenities')) {
            $validated['amenities'] = $request->amenities;
        }

        if ($request->has('quick_facts')) {
            $validated['quick_facts'] = $request->quick_facts;
        }

        if ($request->has('meta_keywords')) {
            $validated['meta_keywords'] = $request->meta_keywords;
        }

        try {
            $service = Service::create($validated);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating service: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        // Calculate statistics with defensive error handling
        try {
            $service->load('bookings');
            $stats = [
                'total_bookings' => $service->bookings()->count(),
                'total_revenue' => $service->bookings()->where('status', 'completed')->sum('total_amount'),
                'avg_rating' => $service->avg_rating ?? 0,
                'total_reviews' => $service->total_reviews ?? 0,
            ];
        } catch (\Exception $e) {
            // Bookings table doesn't exist yet
            $stats = [
                'total_bookings' => 0,
                'total_revenue' => 0,
                'avg_rating' => 0,
                'total_reviews' => 0,
            ];
        }

        return view('admin.services.show', compact('service', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $categories = [
            'point-to-point' => 'Point to Point',
            'hourly-charter' => 'Hourly Charter',
            'airport-transfer' => 'Airport Transfer',
            'corporate' => 'Corporate',
            'events' => 'Events',
            'tours' => 'Tours',
        ];

        $pricingTypes = [
            'flat_rate' => 'Flat Rate',
            'hourly' => 'Hourly',
            'distance_based' => 'Distance Based',
            'custom' => 'Custom',
            'tiered' => 'Tiered',
        ];

        $daysOfWeek = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return view('admin.services.edit', compact('service', 'categories', 'pricingTypes', 'daysOfWeek'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'slug' => 'nullable|string|max:255|unique:services,slug,' . $service->id,
            'category' => 'required|in:point-to-point,hourly-charter,airport-transfer,corporate,events,tours',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'pricing_type' => 'required|in:flat_rate,hourly,distance_based,custom,tiered',
            'base_price' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'per_mile_rate' => 'nullable|numeric|min:0',
            'min_hours' => 'nullable|integer|min:0',
            'max_hours' => 'nullable|integer|min:0',
            'advance_booking_hours' => 'nullable|integer|min:0',
            'max_passengers' => 'nullable|integer|min:1',
            'max_luggage' => 'nullable|integer|min:0',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'airport_service' => 'nullable|boolean',
        ]);

        // Handle JSON fields
        if ($request->has('available_days')) {
            $validated['available_days'] = $request->available_days;
        }

        if ($request->has('service_areas')) {
            $validated['service_areas'] = $request->service_areas;
        }

        if ($request->has('supported_airports')) {
            $validated['supported_airports'] = $request->supported_airports;
        }

        if ($request->has('vehicle_types')) {
            $validated['vehicle_types'] = $request->vehicle_types;
        }

        if ($request->has('amenities')) {
            $validated['amenities'] = $request->amenities;
        }

        if ($request->has('quick_facts')) {
            $validated['quick_facts'] = $request->quick_facts;
        }

        if ($request->has('meta_keywords')) {
            $validated['meta_keywords'] = $request->meta_keywords;
        }

        try {
            $service->update($validated);

            return redirect()->route('admin.services.index')
                ->with('success', 'Service updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating service: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            // Check for active bookings
            try {
                $activeBookings = $service->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
                if ($activeBookings > 0) {
                    return back()->with('error', 'Cannot delete service with active bookings. Please complete or cancel all bookings first.');
                }
            } catch (\Exception $e) {
                // Bookings table doesn't exist, proceed with deletion
            }

            $service->delete();

            return redirect()->route('admin.services.index')
                ->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Service $service)
    {
        try {
            $service->is_active = !$service->is_active;
            $service->save();

            $status = $service->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Service {$status} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating service status: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Service $service)
    {
        try {
            $service->is_featured = !$service->is_featured;
            $service->save();

            $status = $service->is_featured ? 'marked as featured' : 'removed from featured';
            return back()->with('success', "Service {$status} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating featured status: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate service
     */
    public function duplicate(Service $service)
    {
        try {
            $newService = $service->replicate();
            $newService->name = $service->name . ' (Copy)';
            $newService->slug = Str::slug($newService->name);
            $newService->is_featured = false;
            $newService->total_bookings = 0;
            $newService->total_revenue = 0;
            $newService->save();

            return redirect()->route('admin.services.edit', $newService)
                ->with('success', 'Service duplicated successfully! Please update the details.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error duplicating service: ' . $e->getMessage());
        }
    }
}
