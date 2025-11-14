@extends('layouts.app')

@section('title', 'Services Management')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Services Management</h1>
            <p class="text-gray-600 mt-1">Manage transportation services and pricing</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.services.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Add Service
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Services -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Services</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-concierge-bell text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Active Services -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Services</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_percentage'] }}% of total</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Featured Services -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Featured Services</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['featured'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">${{ number_format($stats['total_revenue'], 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">From all bookings</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <form method="GET" action="{{ route('admin.services.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search services..." 
                       class="input-field">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="input-field">
                    <option value="">All Categories</option>
                    <option value="point-to-point" {{ request('category') === 'point-to-point' ? 'selected' : '' }}>Point-to-Point</option>
                    <option value="hourly-charter" {{ request('category') === 'hourly-charter' ? 'selected' : '' }}>Hourly Charter</option>
                    <option value="airport-transfer" {{ request('category') === 'airport-transfer' ? 'selected' : '' }}>Airport Transfer</option>
                    <option value="corporate" {{ request('category') === 'corporate' ? 'selected' : '' }}>Corporate</option>
                    <option value="events" {{ request('category') === 'events' ? 'selected' : '' }}>Events</option>
                    <option value="tours" {{ request('category') === 'tours' ? 'selected' : '' }}>Tours</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Pricing Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pricing Type</label>
                <select name="pricing_type" class="input-field">
                    <option value="">All Types</option>
                    <option value="flat_rate" {{ request('pricing_type') === 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                    <option value="hourly" {{ request('pricing_type') === 'hourly' ? 'selected' : '' }}>Hourly</option>
                    <option value="distance_based" {{ request('pricing_type') === 'distance_based' ? 'selected' : '' }}>Distance-based</option>
                    <option value="custom" {{ request('pricing_type') === 'custom' ? 'selected' : '' }}>Custom</option>
                    <option value="tiered" {{ request('pricing_type') === 'tiered' ? 'selected' : '' }}>Tiered</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-5 flex gap-3">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn-secondary">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Services Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Service
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pricing
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Bookings
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Revenue
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($services as $service)
                    <tr class="hover:bg-gray-50">
                        <!-- Service Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <i class="fas {{ $service->icon ?? 'fa-concierge-bell' }} text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($service->short_description, 50) }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $categoryColors = [
                                    'point-to-point' => 'bg-blue-100 text-blue-800',
                                    'hourly-charter' => 'bg-purple-100 text-purple-800',
                                    'airport-transfer' => 'bg-cyan-100 text-cyan-800',
                                    'corporate' => 'bg-indigo-100 text-indigo-800',
                                    'events' => 'bg-pink-100 text-pink-800',
                                    'tours' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $categoryColors[$service->category] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $service->category_display }}
                            </span>
                        </td>

                        <!-- Pricing -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $service->price_display }}</div>
                            @php
                                $pricingColors = [
                                    'flat_rate' => 'bg-blue-100 text-blue-800',
                                    'hourly' => 'bg-purple-100 text-purple-800',
                                    'distance_based' => 'bg-cyan-100 text-cyan-800',
                                    'custom' => 'bg-gray-100 text-gray-800',
                                    'tiered' => 'bg-indigo-100 text-indigo-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pricingColors[$service->pricing_type] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $service->pricing_type_display }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-2">
                                <!-- Active Status -->
                                <form action="{{ route('admin.services.toggle-active', $service) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $service->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>

                                <!-- Featured Status -->
                                <form action="{{ route('admin.services.toggle-featured', $service) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="fas fa-star mr-1"></i>
                                        {{ $service->is_featured ? 'Featured' : 'Standard' }}
                                    </button>
                                </form>
                            </div>
                        </td>

                        <!-- Bookings -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ $service->total_bookings }}</div>
                            @if($service->avg_rating)
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-star text-yellow-400 mr-1"></i>
                                {{ number_format($service->avg_rating, 1) }}
                            </div>
                            @endif
                        </td>

                        <!-- Revenue -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ number_format($service->total_revenue, 2) }}</div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div x-data="dropdown()" class="relative inline-block text-left">
                                <button @click="toggle()" type="button" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>

                                <div x-show="isOpen" 
                                     @click.away="close()"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('admin.services.show', $service) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-eye mr-2"></i>View Details
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </a>
                                        <form action="{{ route('admin.services.duplicate', $service) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-copy mr-2"></i>Duplicate
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this service?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <i class="fas fa-trash mr-2"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-concierge-bell text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No services found</h3>
                                <p class="text-gray-500 mb-4">Get started by creating your first service</p>
                                <a href="{{ route('admin.services.create') }}" class="btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Add Service
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($services->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $services->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
