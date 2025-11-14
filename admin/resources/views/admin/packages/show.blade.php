@extends('layouts.app')

@section('title', $package->name)

@section('header', $package->name)

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
    <a href="{{ route('admin.packages.index') }}" class="text-gray-600 hover:text-primary-600">Packages</a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center text-gray-700 font-medium">{{ Str::limit($package->name, 30) }}</li>
@endsection

@section('actions')
<div class="flex items-center gap-2">
    <x-button href="{{ route('admin.packages.edit', $package) }}" variant="primary" icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
        Edit Package
    </x-button>
    <x-button href="{{ route('admin.packages.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
        Back to List
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg Booking Value</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['avg_booking_value'] ?? 0, 2) }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Details -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Package Details</h3>
                
                @if($package->featured_image_url)
                <div class="mb-6">
                    <img src="{{ $package->featured_image_url }}" alt="{{ $package->name }}" class="w-full h-64 object-cover rounded-lg">
                </div>
                @endif

                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            {!! $package->category_badge !!}
                        </div>
                        @if($package->is_featured)
                        <div class="flex-shrink-0">
                            {!! $package->featured_badge !!}
                        </div>
                        @endif
                    </div>

                    @if($package->short_description)
                    <p class="text-gray-600 text-sm">{{ $package->short_description }}</p>
                    @endif

                    <div class="prose max-w-none text-gray-700">
                        {!! $package->description !!}
                    </div>
                </div>
            </div>

            <!-- Inclusions & Exclusions -->
            @if($package->inclusions || $package->exclusions)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">What's Included</h3>
                
                @if($package->inclusions)
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">✓ Inclusions</h4>
                    <ul class="space-y-2">
                        @foreach($package->inclusions as $inclusion)
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $inclusion }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($package->exclusions)
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">✗ Exclusions</h4>
                    <ul class="space-y-2">
                        @foreach($package->exclusions as $exclusion)
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ $exclusion }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif

            <!-- Terms & Policies -->
            @if($package->terms_conditions || $package->cancellation_policy)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Terms & Policies</h3>
                
                @if($package->terms_conditions)
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Terms & Conditions</h4>
                    <div class="text-sm text-gray-600 prose prose-sm max-w-none">{!! $package->terms_conditions !!}</div>
                </div>
                @endif

                @if($package->cancellation_policy)
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Cancellation Policy</h4>
                    <div class="text-sm text-gray-600 prose prose-sm max-w-none">{!! $package->cancellation_policy !!}</div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pricing -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Pricing Type</p>
                        <p class="text-base font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $package->pricing_type) }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Base Price</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($package->base_price, 2) }}</p>
                    </div>

                    @if($package->discount_price || $package->discount_percentage)
                    <div>
                        <p class="text-sm text-gray-600">Discount</p>
                        @if($package->discount_price)
                        <p class="text-lg font-semibold text-green-600">${{ number_format($package->discount_price, 2) }}</p>
                        @endif
                        @if($package->discount_percentage)
                        <p class="text-sm text-green-600">{{ $package->discount_percentage }}% off</p>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Final Price</p>
                        <p class="text-2xl font-bold text-primary-600">${{ number_format($package->final_price, 2) }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Capacity & Duration -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Capacity & Duration</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="text-base font-medium text-gray-900">{{ $package->duration_hours }} {{ Str::plural('hour', $package->duration_hours) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Passengers</p>
                            <p class="text-base font-medium text-gray-900">{{ $package->passenger_capacity }} {{ Str::plural('person', $package->passenger_capacity) }}</p>
                        </div>
                    </div>

                    @if($package->luggage_capacity)
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Luggage</p>
                            <p class="text-base font-medium text-gray-900">{{ $package->luggage_capacity }} {{ Str::plural('bag', $package->luggage_capacity) }}</p>
                        </div>
                    </div>
                    @endif

                    @if($package->min_booking_hours)
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Min. Booking</p>
                            <p class="text-base font-medium text-gray-900">{{ $package->min_booking_hours }} {{ Str::plural('hour', $package->min_booking_hours) }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Facts -->
            @if($package->quick_facts)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Facts</h3>
                
                <div class="space-y-3">
                    @foreach($package->quick_facts as $key => $value)
                    <div>
                        <p class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                        <p class="text-base font-medium text-gray-900">{{ $value }}</p>
                    </div>
                    @endforeach

                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Availability</p>
                        <p class="text-base font-medium text-gray-900">
                            @if($package->availability_24_7)
                                <span class="inline-flex items-center gap-1 text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    24/7 Available
                                </span>
                            @else
                                <span class="text-gray-600">By Appointment</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $package->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Featured</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $package->is_featured ? 'Yes' : 'No' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Sort Order</p>
                        <p class="text-base font-medium text-gray-900">{{ $package->sort_order }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Created</p>
                        <p class="text-base font-medium text-gray-900">{{ $package->created_at->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="text-base font-medium text-gray-900">{{ $package->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            @if($package->meta_title || $package->meta_description)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO</h3>
                
                <div class="space-y-3">
                    @if($package->meta_title)
                    <div>
                        <p class="text-sm text-gray-600">Meta Title</p>
                        <p class="text-sm text-gray-900">{{ $package->meta_title }}</p>
                    </div>
                    @endif

                    @if($package->meta_description)
                    <div>
                        <p class="text-sm text-gray-600">Meta Description</p>
                        <p class="text-sm text-gray-900">{{ $package->meta_description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Bookings -->
    @if(isset($package->bookings) && $package->bookings->isNotEmpty())
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <th class="px-4 py-3">Booking ID</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($package->bookings->take(10) as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-800 font-medium">
                                #{{ $booking->id }}
                            </a>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $booking->customer->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">${{ number_format($booking->total_amount ?? 0, 2) }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($booking->status === 'completed') bg-green-100 text-green-800
                                @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($package->bookings->count() > 10)
        <div class="mt-4">
            <a href="{{ route('admin.bookings.index', ['package' => $package->id]) }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                View all {{ $package->bookings->count() }} bookings →
            </a>
        </div>
        @endif
    </div>
    @else
    <div class="card p-6 text-center">
        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-gray-500">No bookings yet for this package</p>
    </div>
    @endif
</div>
@endsection
