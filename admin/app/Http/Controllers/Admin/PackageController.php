<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Package::query();

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

        // Sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $packages = $query->paginate(15);

        // Stats
        $stats = [
            'total' => Package::count(),
            'active' => Package::where('is_active', true)->count(),
            'featured' => Package::where('is_featured', true)->count(),
            'avg_price' => Package::where('is_active', true)->avg('base_price'),
        ];

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:packages,slug|max:255',
            'category' => 'required|in:airport,wedding,corporate,city_tour,group,custom',
            'short_description' => 'nullable|string|max:200',
            'description' => 'required|string',
            'duration_hours' => 'required|integer|min:0',
            'passenger_capacity' => 'required|integer|min:1',
            'luggage_capacity' => 'nullable|integer|min:0',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'pricing_type' => 'required|in:fixed,hourly,daily',
            'min_booking_hours' => 'nullable|integer|min:1',
            'availability_24_7' => 'boolean',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'inclusions' => 'nullable|array',
            'exclusions_text' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'availability_text' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Process exclusions text into array
            if (!empty($validated['exclusions_text'])) {
                $validated['exclusions'] = array_filter(
                    array_map('trim', explode("\n", $validated['exclusions_text']))
                );
            }
            unset($validated['exclusions_text']);

            // Build quick_facts from separate fields
            $validated['quick_facts'] = [
                'vehicle' => $request->input('vehicle_type', 'Luxury Vehicle'),
                'availability' => $request->input('availability_text', '24/7'),
            ];
            unset($validated['vehicle_type'], $validated['availability_text']);

            // Handle checkboxes that may not be present in request
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['availability_24_7'] = $request->has('availability_24_7');

            $package = Package::create($validated);

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $package->addMediaFromRequest('featured_image')
                    ->toMediaCollection('featured_images');
            }

            DB::commit();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating package: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        // Try to load bookings relationship if table exists
        try {
            $package->load('bookings');
            
            // Calculate stats
            $stats = [
                'total_bookings' => $package->bookings()->count(),
                'total_revenue' => $package->bookings()->where('status', 'completed')->sum('total_amount'),
                'avg_booking_value' => $package->bookings()->where('status', 'completed')->avg('total_amount'),
            ];
        } catch (\Exception $e) {
            // If bookings table doesn't exist, set default stats
            $stats = [
                'total_bookings' => 0,
                'total_revenue' => 0,
                'avg_booking_value' => 0,
            ];
        }

        return view('admin.packages.show', compact('package', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug,' . $package->id,
            'category' => 'required|in:airport,wedding,corporate,city_tour,group,custom',
            'short_description' => 'nullable|string|max:200',
            'description' => 'required|string',
            'duration_hours' => 'required|integer|min:0',
            'passenger_capacity' => 'required|integer|min:1',
            'luggage_capacity' => 'nullable|integer|min:0',
            'base_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:base_price',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'pricing_type' => 'required|in:fixed,hourly,daily',
            'min_booking_hours' => 'nullable|integer|min:1',
            'availability_24_7' => 'boolean',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'inclusions' => 'nullable|array',
            'exclusions_text' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'availability_text' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'featured_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Process exclusions text into array
            if (!empty($validated['exclusions_text'])) {
                $validated['exclusions'] = array_filter(
                    array_map('trim', explode("\n", $validated['exclusions_text']))
                );
            } else {
                $validated['exclusions'] = [];
            }
            unset($validated['exclusions_text']);

            // Build quick_facts from separate fields
            $validated['quick_facts'] = [
                'vehicle' => $request->input('vehicle_type', 'Luxury Vehicle'),
                'availability' => $request->input('availability_text', '24/7'),
            ];
            unset($validated['vehicle_type'], $validated['availability_text']);

            // Handle checkboxes that may not be present in request
            $validated['is_active'] = $request->has('is_active');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['availability_24_7'] = $request->has('availability_24_7');

            $package->update($validated);

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $package->clearMediaCollection('featured_images');
                $package->addMediaFromRequest('featured_image')
                    ->toMediaCollection('featured_images');
            }

            DB::commit();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating package: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        try {
            // Check if package has active bookings (if bookings table exists)
            try {
                $activeBookings = $package->bookings()->whereIn('status', ['pending', 'confirmed'])->count();
                
                if ($activeBookings > 0) {
                    return back()->with('error', 'Cannot delete package with active bookings. Please cancel or complete all bookings first.');
                }
            } catch (\Exception $e) {
                // Bookings table doesn't exist yet, proceed with deletion
            }

            $package->delete();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting package: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Package $package)
    {
        try {
            $package->update(['is_featured' => !$package->is_featured]);

            return back()->with('success', 'Featured status updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating featured status: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate package
     */
    public function duplicate(Package $package)
    {
        try {
            DB::beginTransaction();

            $newPackage = $package->replicate();
            $newPackage->name = $package->name . ' - Copy';
            $newPackage->slug = Str::slug($newPackage->name) . '-' . time();
            $newPackage->is_featured = false;
            $newPackage->save();

            // Copy media
            if ($package->hasMedia('featured_images')) {
                $media = $package->getFirstMedia('featured_images');
                $newPackage->addMedia($media->getPath())
                    ->preservingOriginal()
                    ->toMediaCollection('featured_images');
            }

            DB::commit();

            return redirect()->route('admin.packages.edit', $newPackage)
                ->with('success', 'Package duplicated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error duplicating package: ' . $e->getMessage());
        }
    }
}
