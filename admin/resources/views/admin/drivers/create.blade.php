@extends('layouts.app')

@section('title', 'Add New Driver')

@section('header', 'Add New Driver')

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
    <a href="{{ route('admin.drivers.index') }}" class="text-gray-600 hover:text-primary-600">Drivers</a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center text-gray-700 font-medium">Create</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.drivers.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <!-- Personal Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                    Profile Photo
                </label>
                <input 
                    type="file" 
                    name="profile_photo" 
                    id="profile_photo"
                    accept="image/*"
                    class="input-field @error('profile_photo') border-red-500 @enderror"
                >
                @error('profile_photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    required
                    class="input-field @error('name') border-red-500 @enderror"
                    placeholder="John Doe"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    required
                    class="input-field @error('email') border-red-500 @enderror"
                    placeholder="john.doe@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number
                </label>
                <input 
                    type="tel" 
                    name="phone" 
                    id="phone" 
                    value="{{ old('phone') }}"
                    class="input-field @error('phone') border-red-500 @enderror"
                    placeholder="+1 (555) 123-4567"
                >
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                    Date of Birth
                </label>
                <input 
                    type="date" 
                    name="date_of_birth" 
                    id="date_of_birth" 
                    value="{{ old('date_of_birth') }}"
                    max="{{ date('Y-m-d') }}"
                    class="input-field @error('date_of_birth') border-red-500 @enderror"
                >
                @error('date_of_birth')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- License Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">License Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">
                    License Number <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="license_number" 
                    id="license_number" 
                    value="{{ old('license_number') }}"
                    required
                    class="input-field @error('license_number') border-red-500 @enderror"
                    placeholder="DL1234567890"
                >
                @error('license_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="license_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                    License Expiry Date
                </label>
                <input 
                    type="date" 
                    name="license_expiry" 
                    id="license_expiry" 
                    value="{{ old('license_expiry') }}"
                    min="{{ date('Y-m-d') }}"
                    class="input-field @error('license_expiry') border-red-500 @enderror"
                >
                @error('license_expiry')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="experience_years" class="block text-sm font-medium text-gray-700 mb-2">
                    Years of Experience <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="experience_years" 
                    id="experience_years" 
                    value="{{ old('experience_years', 0) }}"
                    required
                    min="0"
                    max="50"
                    class="input-field @error('experience_years') border-red-500 @enderror"
                >
                @error('experience_years')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="license_photo" class="block text-sm font-medium text-gray-700 mb-2">
                    License Photo
                </label>
                <input 
                    type="file" 
                    name="license_photo" 
                    id="license_photo"
                    accept="image/*"
                    class="input-field @error('license_photo') border-red-500 @enderror"
                >
                @error('license_photo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Professional Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Professional Information</h3>
        
        <div class="space-y-6">
            <div>
                <label for="languages" class="block text-sm font-medium text-gray-700 mb-2">
                    Languages Spoken
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach(['English', 'Spanish', 'French', 'German', 'Chinese', 'Arabic', 'Hindi', 'Portuguese'] as $language)
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="languages[]" 
                            value="{{ $language }}"
                            {{ in_array($language, old('languages', [])) ? 'checked' : '' }}
                            class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">{{ $language }}</span>
                    </label>
                    @endforeach
                </div>
                @error('languages')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                    Bio / Description
                </label>
                <textarea 
                    name="bio" 
                    id="bio"
                    rows="4"
                    class="input-field @error('bio') border-red-500 @enderror"
                    placeholder="Tell us about the driver's experience and qualifications..."
                >{{ old('bio') }}</textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Status & Verification Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Status & Verification</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="verification_status" class="block text-sm font-medium text-gray-700 mb-2">
                    Verification Status <span class="text-red-500">*</span>
                </label>
                <select 
                    name="verification_status" 
                    id="verification_status"
                    required
                    class="input-field @error('verification_status') border-red-500 @enderror"
                >
                    <option value="pending" {{ old('verification_status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="verified" {{ old('verification_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="rejected" {{ old('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('verification_status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="background_check_status" class="block text-sm font-medium text-gray-700 mb-2">
                    Background Check <span class="text-red-500">*</span>
                </label>
                <select 
                    name="background_check_status" 
                    id="background_check_status"
                    required
                    class="input-field @error('background_check_status') border-red-500 @enderror"
                >
                    <option value="pending" {{ old('background_check_status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="passed" {{ old('background_check_status') === 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ old('background_check_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('background_check_status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_available" 
                        value="1"
                        {{ old('is_available', false) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Available for assignments</span>
                </label>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-3">
        <x-button type="button" variant="secondary" onclick="window.location='{{ route('admin.drivers.index') }}'">
            Cancel
        </x-button>
        <x-button type="submit" variant="primary" icon="M5 13l4 4L19 7">
            Create Driver
        </x-button>
    </div>
</form>
@endsection
