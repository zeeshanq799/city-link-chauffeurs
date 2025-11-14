<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $customers = $query->latest()
                          ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date|before:today',
            'preferred_payment_method' => 'nullable|in:cash,card,wallet',
            'loyalty_points' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $customer = Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load(['bookings' => function($query) {
            $query->latest()->limit(10);
        }]);

        $stats = [
            'total_bookings' => $customer->bookings()->count(),
            'completed_bookings' => $customer->bookings()->where('status', 'completed')->count(),
            'cancelled_bookings' => $customer->bookings()->where('status', 'cancelled')->count(),
            'total_spent' => $customer->bookings()->where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date|before:today',
            'preferred_payment_method' => 'nullable|in:cash,card,wallet',
            'loyalty_points' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has bookings
        if ($customer->bookings()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Cannot delete customer with existing bookings. Consider deactivating instead.');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Toggle customer active status.
     */
    public function toggleStatus(Customer $customer)
    {
        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.customers.index')
            ->with('success', "Customer {$status} successfully.");
    }

    /**
     * Add loyalty points to customer.
     */
    public function addPoints(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $customer->loyalty_points += $validated['points'];
        $customer->save();

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', "Added {$validated['points']} loyalty points to customer.");
    }
}
