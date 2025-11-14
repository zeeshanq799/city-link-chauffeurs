@extends('layouts.app')

@section('title', 'Add New Package')

@section('header', 'Add New Package')

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
<li class="inline-flex items-center text-gray-700 font-medium">Create</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.packages.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{
    name: '{{ old('name') }}',
    basePrice: {{ old('base_price', 0) }},
    discountPrice: {{ old('discount_price', 0) }},
    get slug() {
        return this.name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    },
    get discountPercentage() {
        if (this.basePrice > 0 && this.discountPrice > 0 && this.discountPrice < this.basePrice) {
            return ((this.basePrice - this.discountPrice) / this.basePrice * 100).toFixed(2);
        }
        return 0;
    }
}">
    @csrf
    
    <!-- Card 1: Basic Information -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Basic Information
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Package Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    x-model="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                    class="input-field @error('name') border-red-500 @enderror"
                    placeholder="e.g., Airport Transfer - Standard"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                    Slug (URL) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="slug" 
                    id="slug" 
                    :value="slug"
                    required
                    maxlength="255"
                    class="input-field bg-gray-50 @error('slug') border-red-500 @enderror"
                    placeholder="Auto-generated from name"
                >
                <p class="mt-1 text-xs text-gray-500">Auto-generated from package name. Edit if needed.</p>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select 
                    name="category" 
                    id="category" 
                    required
                    class="input-field @error('category') border-red-500 @enderror"
                >
                    <option value="">Select Category</option>
                    <option value="airport" {{ old('category') === 'airport' ? 'selected' : '' }}>✈️ Airport Transfer</option>
                    <option value="wedding" {{ old('category') === 'wedding' ? 'selected' : '' }}>💒 Wedding</option>
                    <option value="corporate" {{ old('category') === 'corporate' ? 'selected' : '' }}>💼 Corporate</option>
                    <option value="city_tour" {{ old('category') === 'city_tour' ? 'selected' : '' }}>🏙️ City Tour</option>
                    <option value="group" {{ old('category') === 'group' ? 'selected' : '' }}>👥 Group</option>
                    <option value="custom" {{ old('category') === 'custom' ? 'selected' : '' }}>⭐ Custom</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Short Description
                </label>
                <input 
                    type="text" 
                    name="short_description" 
                    id="short_description" 
                    value="{{ old('short_description') }}"
                    maxlength="200"
                    class="input-field @error('short_description') border-red-500 @enderror"
                    placeholder="Brief one-line description (max 200 characters)"
                >
                @error('short_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" required class="@error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Card 2: Media -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Featured Image
        </h3>
        
        <div>
            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                Package Image
            </label>
            <input 
                type="file" 
                name="featured_image" 
                id="featured_image"
                accept="image/jpeg,image/jpg,image/png,image/webp"
                class="input-field @error('featured_image') border-red-500 @enderror"
            >
            <p class="mt-1 text-xs text-gray-500">Recommended size: 800x600px. Max: 2MB. Formats: JPEG, PNG, WebP</p>
            @error('featured_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <!-- Card 3: Capacity & Duration -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Capacity & Duration
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-2">
                    Duration (Hours) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="duration_hours" 
                    id="duration_hours" 
                    value="{{ old('duration_hours') }}"
                    required
                    min="0"
                    step="0.5"
                    class="input-field @error('duration_hours') border-red-500 @enderror"
                    placeholder="e.g., 2"
                >
                @error('duration_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="passenger_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                    Passenger Capacity <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="passenger_capacity" 
                    id="passenger_capacity" 
                    value="{{ old('passenger_capacity') }}"
                    required
                    min="1"
                    class="input-field @error('passenger_capacity') border-red-500 @enderror"
                    placeholder="e.g., 4"
                >
                @error('passenger_capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="luggage_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                    Luggage Capacity
                </label>
                <input 
                    type="number" 
                    name="luggage_capacity" 
                    id="luggage_capacity" 
                    value="{{ old('luggage_capacity') }}"
                    min="0"
                    class="input-field @error('luggage_capacity') border-red-500 @enderror"
                    placeholder="e.g., 2"
                >
                @error('luggage_capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="min_booking_hours" class="block text-sm font-medium text-gray-700 mb-2">
                    Minimum Booking Hours
                </label>
                <input 
                    type="number" 
                    name="min_booking_hours" 
                    id="min_booking_hours" 
                    value="{{ old('min_booking_hours') }}"
                    min="1"
                    class="input-field @error('min_booking_hours') border-red-500 @enderror"
                    placeholder="e.g., 2"
                >
                @error('min_booking_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="availability_24_7" 
                        id="availability_24_7"
                        value="1"
                        {{ old('availability_24_7', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Available 24/7</span>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Card 4: Pricing -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Pricing
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pricing Type <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="pricing_type" 
                            value="fixed"
                            {{ old('pricing_type', 'fixed') === 'fixed' ? 'checked' : '' }}
                            class="text-primary-600 focus:ring-primary-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Fixed</span>
                    </label>
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="pricing_type" 
                            value="hourly"
                            {{ old('pricing_type') === 'hourly' ? 'checked' : '' }}
                            class="text-primary-600 focus:ring-primary-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Hourly</span>
                    </label>
                    <label class="flex items-center">
                        <input 
                            type="radio" 
                            name="pricing_type" 
                            value="daily"
                            {{ old('pricing_type') === 'daily' ? 'checked' : '' }}
                            class="text-primary-600 focus:ring-primary-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">Daily</span>
                    </label>
                </div>
                @error('pricing_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Base Price ($) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="base_price" 
                    id="base_price" 
                    x-model="basePrice"
                    value="{{ old('base_price') }}"
                    required
                    min="0"
                    step="0.01"
                    class="input-field @error('base_price') border-red-500 @enderror"
                    placeholder="e.g., 99.00"
                >
                @error('base_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Discount Price ($)
                </label>
                <input 
                    type="number" 
                    name="discount_price" 
                    id="discount_price" 
                    x-model="discountPrice"
                    value="{{ old('discount_price') }}"
                    min="0"
                    step="0.01"
                    class="input-field @error('discount_price') border-red-500 @enderror"
                    placeholder="e.g., 79.00"
                >
                @error('discount_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong>Discount Percentage:</strong> 
                        <span x-text="discountPercentage + '%'" class="text-green-600 font-semibold"></span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Calculated automatically based on base and discount prices</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card 5: Details & Inclusions -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Inclusions & Details
        </h3>
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    What's Included
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(['Professional uniformed chauffeur', 'Luxury vehicle with WiFi', 'Meet & greet service', 'Flight tracking & monitoring', 'Bottled water & refreshments', '24/7 customer support'] as $inclusion)
                    <label class="flex items-start">
                        <input 
                            type="checkbox" 
                            name="inclusions[]" 
                            value="{{ $inclusion }}"
                            {{ in_array($inclusion, old('inclusions', [])) ? 'checked' : '' }}
                            class="mt-0.5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">{{ $inclusion }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div>
                <label for="exclusions_text" class="block text-sm font-medium text-gray-700 mb-2">
                    Exclusions (one per line)
                </label>
                <textarea 
                    name="exclusions_text" 
                    id="exclusions_text" 
                    rows="3"
                    class="input-field @error('exclusions_text') border-red-500 @enderror"
                    placeholder="Gratuity (suggested 15-20%)&#10;Parking fees at destination&#10;Additional stops"
                >{{ old('exclusions_text') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Enter each exclusion on a new line</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Vehicle Type
                    </label>
                    <input 
                        type="text" 
                        name="vehicle_type" 
                        id="vehicle_type" 
                        value="{{ old('vehicle_type') }}"
                        class="input-field"
                        placeholder="e.g., Luxury Sedan"
                    >
                </div>
                
                <div>
                    <label for="availability_text" class="block text-sm font-medium text-gray-700 mb-2">
                        Availability Text
                    </label>
                    <input 
                        type="text" 
                        name="availability_text" 
                        id="availability_text" 
                        value="{{ old('availability_text', '24/7') }}"
                        class="input-field"
                        placeholder="e.g., 24/7 or By Appointment"
                    >
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card 6: Terms & SEO -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Terms & SEO
        </h3>
        
        <div class="space-y-6">
            <div>
                <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                    Terms & Conditions
                </label>
                <textarea name="terms_conditions" id="terms_conditions" class="@error('terms_conditions') border-red-500 @enderror">{{ old('terms_conditions') }}</textarea>
                @error('terms_conditions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="cancellation_policy" class="block text-sm font-medium text-gray-700 mb-2">
                    Cancellation Policy
                </label>
                <textarea name="cancellation_policy" id="cancellation_policy" class="@error('cancellation_policy') border-red-500 @enderror">{{ old('cancellation_policy') }}</textarea>
                @error('cancellation_policy')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                    SEO Title
                </label>
                <input 
                    type="text" 
                    name="meta_title" 
                    id="meta_title" 
                    value="{{ old('meta_title') }}"
                    maxlength="255"
                    class="input-field @error('meta_title') border-red-500 @enderror"
                    placeholder="Package Name - City Link Chauffeurs"
                >
                @error('meta_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                    SEO Description
                </label>
                <textarea 
                    name="meta_description" 
                    id="meta_description" 
                    rows="2"
                    maxlength="255"
                    class="input-field @error('meta_description') border-red-500 @enderror"
                    placeholder="Brief description for search engines (max 255 characters)"
                >{{ old('meta_description') }}</textarea>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Card 7: Status & Settings -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Status & Settings
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Sort Order
                </label>
                <input 
                    type="number" 
                    name="sort_order" 
                    id="sort_order" 
                    value="{{ old('sort_order', 0) }}"
                    min="0"
                    class="input-field @error('sort_order') border-red-500 @enderror"
                    placeholder="0"
                >
                <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-3">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active"
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Active (visible to customers)</span>
                </label>
                
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_featured" 
                        id="is_featured"
                        value="1"
                        {{ old('is_featured') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Featured Package</span>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
            Cancel
        </a>
        <button type="submit" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Create Package
        </button>
    </div>
</form>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize main description editor with full features
        $('#description').summernote({
            height: 300,
            placeholder: 'Write detailed package description with formatting, images, and links...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    // Handle image upload - convert to base64 for now
                    for (let i = 0; i < files.length; i++) {
                        let reader = new FileReader();
                        reader.onloadend = function() {
                            let img = $('<img>').attr('src', reader.result);
                            $('#description').summernote('insertNode', img[0]);
                        }
                        reader.readAsDataURL(files[i]);
                    }
                }
            }
        });

        // Initialize terms & conditions editor (simpler)
        $('#terms_conditions').summernote({
            height: 200,
            placeholder: 'Enter terms and conditions...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        // Initialize cancellation policy editor (simpler)
        $('#cancellation_policy').summernote({
            height: 200,
            placeholder: 'Enter cancellation policy...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });
</script>
@endpush
@endsection
