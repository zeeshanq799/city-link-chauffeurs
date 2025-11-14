@extends('layouts.app')

@section('title', 'Add New Vehicle')

@section('header', 'Add New Vehicle')

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
    <a href="{{ route('admin.vehicles.index') }}" class="text-gray-600 hover:text-primary-600">Vehicles</a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center text-gray-700 font-medium">Create</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.vehicles.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <!-- Basic Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="vehicle_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Vehicle Type <span class="text-red-500">*</span>
                </label>
                <select 
                    name="vehicle_type_id" 
                    id="vehicle_type_id"
                    required
                    class="input-field @error('vehicle_type_id') border-red-500 @enderror"
                >
                    <option value="">Select vehicle type</option>
                    @foreach($vehicleTypes as $type)
                    <option value="{{ $type->id }}" {{ old('vehicle_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
                @error('vehicle_type_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Assign Driver
                </label>
                <select 
                    name="driver_id" 
                    id="driver_id"
                    class="input-field @error('driver_id') border-red-500 @enderror"
                >
                    <option value="">No driver assigned</option>
                    @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                    @endforeach
                </select>
                @error('driver_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">
                    License Plate <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="license_plate" 
                    id="license_plate" 
                    value="{{ old('license_plate') }}"
                    required
                    class="input-field @error('license_plate') border-red-500 @enderror"
                    placeholder="ABC-1234"
                >
                @error('license_plate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="vin" class="block text-sm font-medium text-gray-700 mb-2">
                    VIN Number
                </label>
                <input 
                    type="text" 
                    name="vin" 
                    id="vin" 
                    value="{{ old('vin') }}"
                    class="input-field @error('vin') border-red-500 @enderror"
                    placeholder="1HGBH41JXMN109186"
                >
                @error('vin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="make" class="block text-sm font-medium text-gray-700 mb-2">
                    Make <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="make" 
                    id="make" 
                    value="{{ old('make') }}"
                    required
                    class="input-field @error('make') border-red-500 @enderror"
                    placeholder="Toyota"
                >
                @error('make')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                    Model <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="model" 
                    id="model" 
                    value="{{ old('model') }}"
                    required
                    class="input-field @error('model') border-red-500 @enderror"
                    placeholder="Camry"
                >
                @error('model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                    Year <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="year" 
                    id="year" 
                    value="{{ old('year', date('Y')) }}"
                    required
                    min="1990"
                    max="{{ date('Y') + 1 }}"
                    class="input-field @error('year') border-red-500 @enderror"
                >
                @error('year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                    Color
                </label>
                <input 
                    type="text" 
                    name="color" 
                    id="color" 
                    value="{{ old('color') }}"
                    class="input-field @error('color') border-red-500 @enderror"
                    placeholder="Black"
                >
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Registration & Insurance Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Registration & Insurance</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="registration_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                    Registration Expiry Date
                </label>
                <input 
                    type="date" 
                    name="registration_expiry" 
                    id="registration_expiry" 
                    value="{{ old('registration_expiry') }}"
                    class="input-field @error('registration_expiry') border-red-500 @enderror"
                >
                @error('registration_expiry')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="insurance_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                    Insurance Expiry Date
                </label>
                <input 
                    type="date" 
                    name="insurance_expiry" 
                    id="insurance_expiry" 
                    value="{{ old('insurance_expiry') }}"
                    class="input-field @error('insurance_expiry') border-red-500 @enderror"
                >
                @error('insurance_expiry')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Insurance Policy Number
                </label>
                <input 
                    type="text" 
                    name="insurance_policy_number" 
                    id="insurance_policy_number" 
                    value="{{ old('insurance_policy_number') }}"
                    class="input-field @error('insurance_policy_number') border-red-500 @enderror"
                    placeholder="POL-123456789"
                >
                @error('insurance_policy_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Mileage & Maintenance Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Mileage & Maintenance</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="mileage" class="block text-sm font-medium text-gray-700 mb-2">
                    Current Mileage (km) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="mileage" 
                    id="mileage" 
                    value="{{ old('mileage', 0) }}"
                    required
                    min="0"
                    class="input-field @error('mileage') border-red-500 @enderror"
                >
                @error('mileage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="next_maintenance_mileage" class="block text-sm font-medium text-gray-700 mb-2">
                    Next Maintenance Mileage (km)
                </label>
                <input 
                    type="number" 
                    name="next_maintenance_mileage" 
                    id="next_maintenance_mileage" 
                    value="{{ old('next_maintenance_mileage') }}"
                    min="0"
                    class="input-field @error('next_maintenance_mileage') border-red-500 @enderror"
                >
                @error('next_maintenance_mileage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="last_maintenance_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Last Maintenance Date
                </label>
                <input 
                    type="date" 
                    name="last_maintenance_date" 
                    id="last_maintenance_date" 
                    value="{{ old('last_maintenance_date') }}"
                    class="input-field @error('last_maintenance_date') border-red-500 @enderror"
                >
                @error('last_maintenance_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status"
                    required
                    class="input-field @error('status') border-red-500 @enderror"
                >
                    <option value="available" {{ old('status', 'available') === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="in_service" {{ old('status') === 'in_service' ? 'selected' : '' }}>In Service</option>
                    <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="out_of_service" {{ old('status') === 'out_of_service' ? 'selected' : '' }}>Out of Service</option>
                    <option value="retired" {{ old('status') === 'retired' ? 'selected' : '' }}>Retired</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="maintenance_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Maintenance Notes
                </label>
                <textarea 
                    name="maintenance_notes" 
                    id="maintenance_notes"
                    rows="3"
                    class="input-field @error('maintenance_notes') border-red-500 @enderror"
                    placeholder="Any maintenance notes or history..."
                >{{ old('maintenance_notes') }}</textarea>
                @error('maintenance_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Purchase Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Purchase Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Purchase Date
                </label>
                <input 
                    type="date" 
                    name="purchase_date" 
                    id="purchase_date" 
                    value="{{ old('purchase_date') }}"
                    class="input-field @error('purchase_date') border-red-500 @enderror"
                >
                @error('purchase_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Purchase Price ($)
                </label>
                <input 
                    type="number" 
                    name="purchase_price" 
                    id="purchase_price" 
                    value="{{ old('purchase_price') }}"
                    min="0"
                    step="0.01"
                    class="input-field @error('purchase_price') border-red-500 @enderror"
                    placeholder="0.00"
                >
                @error('purchase_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Additional Details Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Additional Details</h3>
        
        <div class="space-y-6">
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea 
                    name="description" 
                    id="description"
                    rows="3"
                    class="input-field @error('description') border-red-500 @enderror"
                    placeholder="Additional information about this vehicle..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                    >
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-3">
        <x-button type="button" variant="secondary" onclick="window.location='{{ route('admin.vehicles.index') }}'">
            Cancel
        </x-button>
        <x-button type="submit" variant="primary" icon="M5 13l4 4L19 7">
            Create Vehicle
        </x-button>
    </div>
</form>
@endsection
