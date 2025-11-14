@extends('layouts.app')

@section('title', 'Edit Service')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.services.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Service: {{ $service->name }}</h1>
            </div>
            <p class="text-gray-600">Update service information and settings</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (Form Fields) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    
                    <div class="space-y-4">
                        <!-- Service Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Service Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $service->name) }}" 
                                   class="input-field @error('name') border-red-500 @enderror" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                URL Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $service->slug) }}" 
                                   class="input-field @error('slug') border-red-500 @enderror" 
                                   placeholder="auto-generated-from-name">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category" class="input-field @error('category') border-red-500 @enderror" required>
                                <option value="">Select Category</option>
                                <option value="point-to-point" {{ old('category', $service->category) === 'point-to-point' ? 'selected' : '' }}>Point-to-Point</option>
                                <option value="hourly-charter" {{ old('category', $service->category) === 'hourly-charter' ? 'selected' : '' }}>Hourly Charter</option>
                                <option value="airport-transfer" {{ old('category', $service->category) === 'airport-transfer' ? 'selected' : '' }}>Airport Transfer</option>
                                <option value="corporate" {{ old('category', $service->category) === 'corporate' ? 'selected' : '' }}>Corporate</option>
                                <option value="events" {{ old('category', $service->category) === 'events' ? 'selected' : '' }}>Events</option>
                                <option value="tours" {{ old('category', $service->category) === 'tours' ? 'selected' : '' }}>Tours</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Short Description
                            </label>
                            <textarea name="short_description" rows="3" 
                                      class="input-field @error('short_description') border-red-500 @enderror" 
                                      maxlength="500">{{ old('short_description', $service->short_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
                            @error('short_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Icon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Icon (FontAwesome class)
                            </label>
                            <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" 
                                   class="input-field @error('icon') border-red-500 @enderror" 
                                   placeholder="fa-concierge-bell">
                            <p class="mt-1 text-xs text-gray-500">Example: fa-route, fa-plane, fa-clock</p>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Sort Order
                            </label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}" 
                                   class="input-field @error('sort_order') border-red-500 @enderror">
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description & Content -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description & Content</h3>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Description
                        </label>
                        <textarea id="description" name="description" class="summernote">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Features -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Key Features
                        </label>
                        <textarea id="features" name="features" class="summernote">{{ old('features', $service->features) }}</textarea>
                        @error('features')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Inclusions -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            What's Included
                        </label>
                        <textarea id="inclusions" name="inclusions" class="summernote-simple">{{ old('inclusions', $service->inclusions) }}</textarea>
                        @error('inclusions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exclusions -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            What's Not Included
                        </label>
                        <textarea id="exclusions" name="exclusions" class="summernote-simple">{{ old('exclusions', $service->exclusions) }}</textarea>
                        @error('exclusions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Terms & Policies -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Terms & Policies</h3>
                    
                    <!-- Terms & Conditions -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Terms & Conditions
                        </label>
                        <textarea id="terms_conditions" name="terms_conditions" class="summernote-simple">{{ old('terms_conditions', $service->terms_conditions) }}</textarea>
                        @error('terms_conditions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cancellation Policy -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cancellation Policy
                        </label>
                        <textarea id="cancellation_policy" name="cancellation_policy" class="summernote-simple">{{ old('cancellation_policy', $service->cancellation_policy) }}</textarea>
                        @error('cancellation_policy')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing Configuration -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing Configuration</h3>
                    
                    <div class="space-y-4">
                        <!-- Pricing Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pricing Type <span class="text-red-500">*</span>
                            </label>
                            <select name="pricing_type" id="pricing_type" class="input-field" required>
                                <option value="flat_rate" {{ old('pricing_type', $service->pricing_type) === 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                                <option value="hourly" {{ old('pricing_type', $service->pricing_type) === 'hourly' ? 'selected' : '' }}>Hourly</option>
                                <option value="distance_based" {{ old('pricing_type', $service->pricing_type) === 'distance_based' ? 'selected' : '' }}>Distance-based</option>
                                <option value="custom" {{ old('pricing_type', $service->pricing_type) === 'custom' ? 'selected' : '' }}>Custom</option>
                                <option value="tiered" {{ old('pricing_type', $service->pricing_type) === 'tiered' ? 'selected' : '' }}>Tiered</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Base Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Base Price</label>
                                <input type="number" step="0.01" name="base_price" value="{{ old('base_price', $service->base_price) }}" 
                                       class="input-field" placeholder="0.00">
                            </div>

                            <!-- Minimum Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Price</label>
                                <input type="number" step="0.01" name="min_price" value="{{ old('min_price', $service->min_price) }}" 
                                       class="input-field" placeholder="0.00">
                            </div>

                            <!-- Hourly Rate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate</label>
                                <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate', $service->hourly_rate) }}" 
                                       class="input-field" placeholder="0.00">
                            </div>

                            <!-- Per Mile Rate -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Per Mile Rate</label>
                                <input type="number" step="0.01" name="per_mile_rate" value="{{ old('per_mile_rate', $service->per_mile_rate) }}" 
                                       class="input-field" placeholder="0.00">
                            </div>

                            <!-- Minimum Hours -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Hours</label>
                                <input type="number" name="min_hours" value="{{ old('min_hours', $service->min_hours) }}" 
                                       class="input-field">
                            </div>

                            <!-- Maximum Hours -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Hours</label>
                                <input type="number" name="max_hours" value="{{ old('max_hours', $service->max_hours) }}" 
                                       class="input-field">
                            </div>

                            <!-- Additional Hour Rate -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Hour Rate</label>
                                <input type="number" step="0.01" name="additional_hour_rate" value="{{ old('additional_hour_rate', $service->additional_hour_rate) }}" 
                                       class="input-field" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Availability Settings -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability Settings</h3>
                    
                    <div class="space-y-4">
                        <!-- Available Days -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Available Days</label>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <label class="flex items-center">
                                    <input type="checkbox" name="available_days[]" value="{{ $day }}" 
                                           {{ (is_array(old('available_days', $service->available_days)) && in_array($day, old('available_days', $service->available_days))) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 mr-2">
                                    <span class="text-sm">{{ ucfirst($day) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Available From -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available From</label>
                                <input type="time" name="available_from" value="{{ old('available_from', $service->available_from) }}" 
                                       class="input-field">
                            </div>

                            <!-- Available To -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available To</label>
                                <input type="time" name="available_to" value="{{ old('available_to', $service->available_to) }}" 
                                       class="input-field">
                            </div>

                            <!-- Advance Booking Hours -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Advance Booking (Hours)</label>
                                <input type="number" name="advance_booking_hours" value="{{ old('advance_booking_hours', $service->advance_booking_hours) }}" 
                                       class="input-field">
                            </div>

                            <!-- Max Advance Days -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Advance (Days)</label>
                                <input type="number" name="max_advance_days" value="{{ old('max_advance_days', $service->max_advance_days) }}" 
                                       class="input-field">
                            </div>

                            <!-- Max Passengers -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Passengers</label>
                                <input type="number" name="max_passengers" value="{{ old('max_passengers', $service->max_passengers) }}" 
                                       class="input-field">
                            </div>

                            <!-- Max Luggage -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Luggage</label>
                                <input type="number" name="max_luggage" value="{{ old('max_luggage', $service->max_luggage) }}" 
                                       class="input-field">
                            </div>

                            <!-- Free Waiting Time -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Free Waiting (Minutes)</label>
                                <input type="number" name="free_waiting_time" value="{{ old('free_waiting_time', $service->free_waiting_time) }}" 
                                       class="input-field">
                            </div>

                            <!-- Waiting Charge -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Waiting Charge ($/min)</label>
                                <input type="number" step="0.01" name="waiting_charge_per_min" value="{{ old('waiting_charge_per_min', $service->waiting_charge_per_min) }}" 
                                       class="input-field">
                            </div>

                            <!-- Max Distance -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Distance (Miles)</label>
                                <input type="number" name="max_distance_miles" value="{{ old('max_distance_miles', $service->max_distance_miles) }}" 
                                       class="input-field">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h3>
                    
                    <div class="space-y-4">
                        <!-- Meta Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $service->meta_title) }}" 
                                   class="input-field" maxlength="255">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 50-60 characters</p>
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="input-field" maxlength="320">{{ old('meta_description', $service->meta_description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Recommended: 150-160 characters</p>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', is_array($service->meta_keywords) ? implode(', ', $service->meta_keywords) : $service->meta_keywords) }}" 
                                   class="input-field" placeholder="keyword1, keyword2, keyword3">
                            <p class="mt-1 text-xs text-gray-500">Comma-separated</p>
                        </div>
                    </div>
                </div>

                <!-- Service Info -->
                <div class="card p-6 bg-gray-50">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Service Information</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <span class="text-gray-900">{{ $service->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="text-gray-900">{{ $service->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Bookings:</span>
                            <span class="text-gray-900">{{ number_format($service->total_bookings) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Revenue:</span>
                            <span class="text-gray-900">${{ number_format($service->total_revenue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (Status & Sidebar) -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                    
                    <div class="space-y-3">
                        <!-- Active Status -->
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" 
                                   {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 mr-2">
                            <span class="text-sm font-medium text-gray-700">Active Service</span>
                        </label>

                        <!-- Featured Status -->
                        <label class="flex items-center">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" 
                                   {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 mr-2">
                            <span class="text-sm font-medium text-gray-700">Featured Service</span>
                        </label>

                        <!-- Airport Service -->
                        <label class="flex items-center">
                            <input type="hidden" name="airport_service" value="0">
                            <input type="checkbox" name="airport_service" value="1" id="airport_service"
                                   {{ old('airport_service', $service->airport_service) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 mr-2">
                            <span class="text-sm font-medium text-gray-700">Airport Service</span>
                        </label>
                    </div>
                </div>

                <!-- Quick Facts -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Facts</h3>
                    <div id="quick-facts-container" class="space-y-3">
                        @if($service->quick_facts)
                            @foreach($service->quick_facts as $key => $value)
                            <div class="flex gap-2">
                                <input type="text" name="quick_facts_keys[]" value="{{ $key }}" placeholder="Key" class="input-field flex-1 text-sm">
                                <input type="text" name="quick_facts_values[]" value="{{ $value }}" placeholder="Value" class="input-field flex-1 text-sm">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addQuickFact()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-1"></i>Add Quick Fact
                    </button>
                </div>

                <!-- Amenities -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Amenities</h3>
                    <div id="amenities-container" class="space-y-2">
                        @if($service->amenities)
                            @foreach($service->amenities as $amenity)
                            <div class="flex gap-2">
                                <input type="text" name="amenities[]" value="{{ $amenity }}" placeholder="Amenity name" class="input-field flex-1 text-sm">
                                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addAmenity()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-1"></i>Add Amenity
                    </button>
                </div>

                <!-- Service Areas -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Areas</h3>
                    <div id="service-areas-container" class="space-y-3">
                        @if($service->service_areas)
                            @foreach($service->service_areas as $area)
                            <div class="space-y-2 p-3 bg-gray-50 rounded">
                                <input type="text" name="service_area_names[]" value="{{ $area['name'] ?? '' }}" placeholder="Area name" class="input-field text-sm">
                                <div class="flex gap-2">
                                    <select name="service_area_types[]" class="input-field flex-1 text-sm">
                                        <option value="city" {{ ($area['type'] ?? '') === 'city' ? 'selected' : '' }}>City</option>
                                        <option value="region" {{ ($area['type'] ?? '') === 'region' ? 'selected' : '' }}>Region</option>
                                        <option value="state" {{ ($area['type'] ?? '') === 'state' ? 'selected' : '' }}>State</option>
                                        <option value="district" {{ ($area['type'] ?? '') === 'district' ? 'selected' : '' }}>District</option>
                                    </select>
                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" onclick="addServiceArea()" class="mt-3 text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-1"></i>Add Service Area
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="card p-6">
                    <div class="space-y-3">
                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-save mr-2"></i>Update Service
                        </button>
                        <a href="{{ route('admin.services.show', $service) }}" class="btn-secondary w-full text-center block">
                            <i class="fas fa-eye mr-2"></i>View Service
                        </a>
                        <button type="button" onclick="confirmDelete()" class="btn-danger w-full">
                            <i class="fas fa-trash mr-2"></i>Delete Service
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form -->
    <form id="delete-form" action="{{ route('admin.services.destroy', $service) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Summernote for full editors
    $('.summernote').summernote({
        height: 400,
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
        ]
    });

    // Initialize Summernote for simple editors
    $('.summernote-simple').summernote({
        height: 250,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    // Auto-generate slug from name
    $('input[name="name"]').on('keyup', function() {
        let name = $(this).val();
        let slug = name.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        $('input[name="slug"]').val(slug);
    });
});

// Quick Facts Management
function addQuickFact() {
    const container = document.getElementById('quick-facts-container');
    const html = `
        <div class="flex gap-2">
            <input type="text" name="quick_facts_keys[]" placeholder="Key" class="input-field flex-1 text-sm">
            <input type="text" name="quick_facts_values[]" placeholder="Value" class="input-field flex-1 text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

// Amenities Management
function addAmenity() {
    const container = document.getElementById('amenities-container');
    const html = `
        <div class="flex gap-2">
            <input type="text" name="amenities[]" placeholder="Amenity name" class="input-field flex-1 text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

// Service Areas Management
function addServiceArea() {
    const container = document.getElementById('service-areas-container');
    const html = `
        <div class="space-y-2 p-3 bg-gray-50 rounded">
            <input type="text" name="service_area_names[]" placeholder="Area name" class="input-field text-sm">
            <div class="flex gap-2">
                <select name="service_area_types[]" class="input-field flex-1 text-sm">
                    <option value="city">City</option>
                    <option value="region">Region</option>
                    <option value="state">State</option>
                    <option value="district">District</option>
                </select>
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

// Confirm Delete
function confirmDelete() {
    if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}

// Initialize empty fields if none exist
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('quick-facts-container').children.length === 0) {
        addQuickFact();
    }
    if (document.getElementById('amenities-container').children.length === 0) {
        addAmenity();
    }
    if (document.getElementById('service-areas-container').children.length === 0) {
        addServiceArea();
    }
});
</script>
@endpush
