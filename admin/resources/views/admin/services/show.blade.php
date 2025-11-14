@extends('layouts.app')

@section('title', $service->name . ' - Service Details')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.services.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">{{ $service->name }}</h1>
            </div>
            <div class="flex gap-2">
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
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $categoryColors[$service->category] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $service->category_display }}
                </span>
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($service->is_featured)
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    <i class="fas fa-star mr-1"></i>Featured
                </span>
                @endif
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.services.edit', $service) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('admin.services.duplicate', $service) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-secondary">
                    <i class="fas fa-copy mr-2"></i>Duplicate
                </button>
            </form>
            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this service?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Overview -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Overview</h3>
                @if($service->icon)
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mb-4">
                    <i class="fas {{ $service->icon }} text-3xl text-white"></i>
                </div>
                @endif
                <p class="text-gray-700 mb-4">{{ $service->short_description }}</p>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="text-gray-900 font-medium">{{ $service->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="text-gray-900 font-medium">{{ $service->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($service->description)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! $service->description !!}
                </div>
            </div>
            @endif

            <!-- Features -->
            @if($service->features)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Features</h3>
                <div class="prose max-w-none text-gray-700">
                    {!! $service->features !!}
                </div>
            </div>
            @endif

            <!-- Inclusions & Exclusions -->
            @if($service->inclusions || $service->exclusions)
            <div class="card p-6">
                <div x-data="{ activeTab: 'inclusions' }">
                    <div class="flex border-b border-gray-200 mb-4">
                        <button @click="activeTab = 'inclusions'" 
                                :class="activeTab === 'inclusions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 border-b-2 font-medium text-sm">
                            Included
                        </button>
                        <button @click="activeTab = 'exclusions'" 
                                :class="activeTab === 'exclusions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="px-4 py-2 border-b-2 font-medium text-sm">
                            Excluded
                        </button>
                    </div>

                    <div x-show="activeTab === 'inclusions'" class="prose max-w-none text-gray-700">
                        {!! $service->inclusions ?? '<p class="text-gray-500">No inclusions specified</p>' !!}
                    </div>

                    <div x-show="activeTab === 'exclusions'" class="prose max-w-none text-gray-700">
                        {!! $service->exclusions ?? '<p class="text-gray-500">No exclusions specified</p>' !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Terms & Policies -->
            @if($service->terms_conditions || $service->cancellation_policy)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Terms & Policies</h3>
                <div class="space-y-4">
                    @if($service->terms_conditions)
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex justify-between items-center w-full text-left py-3 border-b border-gray-200">
                            <span class="font-medium text-gray-900">Terms & Conditions</span>
                            <i class="fas fa-chevron-down" :class="open ? 'transform rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="prose prose-sm max-w-none text-gray-700 mt-3">
                            {!! $service->terms_conditions !!}
                        </div>
                    </div>
                    @endif

                    @if($service->cancellation_policy)
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="flex justify-between items-center w-full text-left py-3 border-b border-gray-200">
                            <span class="font-medium text-gray-900">Cancellation Policy</span>
                            <i class="fas fa-chevron-down" :class="open ? 'transform rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="prose prose-sm max-w-none text-gray-700 mt-3">
                            {!! $service->cancellation_policy !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Service Areas -->
            @if($service->service_areas && count($service->service_areas) > 0)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Areas</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($service->service_areas as $area)
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $area['name'] ?? 'N/A' }}
                        @if(isset($area['type']))
                        <span class="text-blue-500 text-xs">({{ ucfirst($area['type']) }})</span>
                        @endif
                    </span>
                    @endforeach
                </div>

                @if($service->airport_service && $service->supported_airports)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-medium text-gray-900 mb-2">Supported Airports</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($service->supported_airports as $airport)
                        <span class="px-3 py-1 bg-cyan-50 text-cyan-700 rounded-full text-sm">
                            <i class="fas fa-plane mr-1"></i>
                            {{ $airport['code'] ?? 'N/A' }} - {{ $airport['name'] ?? 'N/A' }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Recent Bookings -->
            @if(isset($service->bookings) && $service->bookings->isNotEmpty())
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($service->bookings->take(10) as $booking)
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
                                    <span class="px-2 py-1 text-xs rounded-full {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($booking->status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($service->bookings->count() > 10)
                <div class="mt-4">
                    <a href="{{ route('admin.bookings.index', ['service' => $service->id]) }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                        View all {{ $service->bookings->count() }} bookings →
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Statistics -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                    @if($service->avg_rating)
                    <div>
                        <p class="text-sm text-gray-600">Average Rating</p>
                        <div class="flex items-center">
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($service->avg_rating, 1) }}</p>
                            <i class="fas fa-star text-yellow-400 ml-2"></i>
                        </div>
                        <p class="text-xs text-gray-500">{{ $service->total_reviews }} reviews</p>
                    </div>
                    @endif
                    @if($service->last_booking_at)
                    <div>
                        <p class="text-sm text-gray-600">Last Booking</p>
                        <p class="text-sm font-medium text-gray-900">{{ $service->last_booking_at->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pricing Details -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing Details</h3>
                @php
                    $pricingColors = [
                        'flat_rate' => 'bg-blue-100 text-blue-800',
                        'hourly' => 'bg-purple-100 text-purple-800',
                        'distance_based' => 'bg-cyan-100 text-cyan-800',
                        'custom' => 'bg-gray-100 text-gray-800',
                        'tiered' => 'bg-indigo-100 text-indigo-800',
                    ];
                @endphp
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $pricingColors[$service->pricing_type] ?? 'bg-gray-100 text-gray-800' }} mb-4 inline-block">
                    {{ $service->pricing_type_display }}
                </span>

                @if($service->base_price)
                <div class="mb-4">
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($service->base_price, 2) }}</p>
                    <p class="text-sm text-gray-600">Base Price</p>
                </div>
                @endif

                <div class="space-y-3 text-sm">
                    @if($service->min_price)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Minimum Price:</span>
                        <span class="font-medium text-gray-900">${{ number_format($service->min_price, 2) }}</span>
                    </div>
                    @endif

                    @if($service->hourly_rate)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hourly Rate:</span>
                        <span class="font-medium text-gray-900">${{ number_format($service->hourly_rate, 2) }}/hr</span>
                    </div>
                    @endif

                    @if($service->per_mile_rate)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Per Mile Rate:</span>
                        <span class="font-medium text-gray-900">${{ number_format($service->per_mile_rate, 2) }}/mi</span>
                    </div>
                    @endif

                    @if($service->min_hours)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Minimum Hours:</span>
                        <span class="font-medium text-gray-900">{{ $service->min_hours }} hrs</span>
                    </div>
                    @endif

                    @if($service->max_hours)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Maximum Hours:</span>
                        <span class="font-medium text-gray-900">{{ $service->max_hours }} hrs</span>
                    </div>
                    @endif

                    @if($service->additional_hour_rate)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Additional Hour:</span>
                        <span class="font-medium text-gray-900">${{ number_format($service->additional_hour_rate, 2) }}/hr</span>
                    </div>
                    @endif
                </div>

                @if($service->tiered_pricing && count($service->tiered_pricing) > 0)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-medium text-gray-900 mb-2 text-sm">Tiered Pricing</h4>
                    <div class="space-y-2">
                        @foreach($service->tiered_pricing as $tier)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $tier['hours'] ?? 'N/A' }} hours:</span>
                            <span class="font-medium text-gray-900">${{ number_format($tier['rate'] ?? 0, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Availability -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability</h3>
                
                @if($service->available_days && count($service->available_days) > 0)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Available Days</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <span class="px-2 py-1 text-xs rounded {{ in_array($day, $service->available_days) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-400' }}">
                            {{ ucfirst(substr($day, 0, 3)) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="space-y-3 text-sm">
                    @if($service->available_from && $service->available_to)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Operating Hours:</span>
                        <span class="font-medium text-gray-900">{{ date('g:i A', strtotime($service->available_from)) }} - {{ date('g:i A', strtotime($service->available_to)) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Advance Booking:</span>
                        <span class="font-medium text-gray-900">{{ $service->advance_booking_hours }} hours</span>
                    </div>

                    @if($service->max_advance_days)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max Advance:</span>
                        <span class="font-medium text-gray-900">{{ $service->max_advance_days }} days</span>
                    </div>
                    @endif

                    @if($service->max_passengers)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max Passengers:</span>
                        <span class="font-medium text-gray-900">{{ $service->max_passengers }}</span>
                    </div>
                    @endif

                    @if($service->max_luggage)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max Luggage:</span>
                        <span class="font-medium text-gray-900">{{ $service->max_luggage }} pieces</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Restrictions -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Settings</h3>
                <div class="space-y-3 text-sm">
                    @if($service->free_waiting_time)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Free Waiting:</span>
                        <span class="font-medium text-gray-900">{{ $service->free_waiting_time }} mins</span>
                    </div>
                    @endif

                    @if($service->waiting_charge_per_min)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Waiting Charge:</span>
                        <span class="font-medium text-gray-900">${{ number_format($service->waiting_charge_per_min, 2) }}/min</span>
                    </div>
                    @endif

                    @if($service->max_distance_miles)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max Distance:</span>
                        <span class="font-medium text-gray-900">{{ $service->max_distance_miles }} miles</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Airport Service:</span>
                        <span class="font-medium {{ $service->airport_service ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $service->airport_service ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            @if($service->amenities && count($service->amenities) > 0)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Amenities</h3>
                <ul class="space-y-2">
                    @foreach($service->amenities as $amenity)
                    <li class="flex items-start text-sm">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span class="text-gray-700">{{ $amenity }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Quick Facts -->
            @if($service->quick_facts && count($service->quick_facts) > 0)
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Facts</h3>
                <div class="space-y-3">
                    @foreach($service->quick_facts as $key => $value)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $key }}</p>
                        <p class="text-sm font-medium text-gray-900">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- SEO Information -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Slug</p>
                        <p class="text-sm font-mono text-gray-900">{{ $service->slug }}</p>
                    </div>

                    @if($service->meta_title)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Meta Title</p>
                        <p class="text-sm text-gray-900">{{ $service->meta_title }}</p>
                    </div>
                    @endif

                    @if($service->meta_description)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Meta Description</p>
                        <p class="text-sm text-gray-700">{{ $service->meta_description }}</p>
                    </div>
                    @endif

                    @if($service->meta_keywords && count($service->meta_keywords) > 0)
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Keywords</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($service->meta_keywords as $keyword)
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $keyword }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
