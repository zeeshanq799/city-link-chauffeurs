@extends('layouts.app')

@section('title', 'Customer Details')

@section('header')
@endsection

@section('breadcrumb')
<li class="inline-flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-primary-600">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
        </svg>
    </a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center">
    <a href="{{ route('admin.customers.index') }}" class="text-gray-600 hover:text-primary-600">Customers</a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center text-gray-700 font-medium">{{ $customer->first_name }} {{ $customer->last_name }}</li>
@endsection

@section('actions')
<div class="flex items-center space-x-3">
    @can('update', $customer)
    <x-button href="{{ route('admin.customers.edit', $customer) }}" variant="primary" icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
        Edit Customer
    </x-button>
    @endcan
    <x-button href="{{ route('admin.customers.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
        Back to List
    </x-button>
</div>
@endsection

@section('content')
<!-- Customer Profile Header -->
<div class="card p-8 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-24 h-24 bg-gradient-to-br from-secondary-400 to-primary-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                {{ strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)) }}
            </div>
            <div class="ml-6">
                <h2 class="text-3xl font-bold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                <p class="text-lg text-gray-600 mt-1">{{ $customer->email }}</p>
                <div class="flex items-center mt-3 space-x-4">
                    @if($customer->status === 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Active
                    </span>
                    @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Inactive
                    </span>
                    @endif
                    <span class="text-sm text-gray-500">
                        Customer since {{ $customer->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="text-right">
            <div class="flex items-center justify-end">
                <svg class="w-8 h-8 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ number_format($customer->loyalty_points) }}</div>
                    <div class="text-sm text-gray-500">Loyalty Points</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <x-stat-card 
        title="Total Bookings" 
        :value="$customer->bookings->count()"
        icon="calendar"
        color="primary"
    />
    
    <x-stat-card 
        title="Completed Trips" 
        :value="$customer->bookings->where('status', 'completed')->count()"
        icon="chart"
        color="success"
    />
    
    <x-stat-card 
        title="Total Spent" 
        :value="'$' . number_format($customer->bookings->where('status', 'completed')->sum('final_price'), 2)"
        icon="money"
        color="secondary"
    />
    
    <x-stat-card 
        title="Average Rating" 
        :value="$customer->bookings->avg('rating') ? number_format($customer->bookings->avg('rating'), 1) : 'N/A'"
        icon="star"
        color="warning"
    />
</div>

<!-- Contact & Account Information -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Contact Information -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Email Address</p>
                    <p class="text-base text-gray-900">{{ $customer->email }}</p>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Phone Number</p>
                    <p class="text-base text-gray-900">{{ $customer->phone }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Account Information -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
        
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Customer ID</label>
                <p class="text-base text-gray-900">#{{ $customer->id }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Account Created</label>
                <p class="text-base text-gray-900">{{ $customer->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                <p class="text-base text-gray-900">{{ $customer->updated_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
        @if($customer->bookings->count() > 0)
        <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700">
            View All
        </a>
        @endif
    </div>
    
    @if($customer->bookings->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pickup</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dropoff</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($customer->bookings->take(5) as $booking)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">#{{ $booking->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $booking->pickup_location }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $booking->dropoff_location }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $booking->pickup_datetime->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->pickup_datetime->format('g:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">${{ number_format($booking->final_price, 2) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
        <p class="mt-1 text-sm text-gray-500">This customer hasn't made any bookings yet.</p>
    </div>
    @endif
</div>
@endsection
