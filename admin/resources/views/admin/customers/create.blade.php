@extends('layouts.app')

@section('title', 'Add New Customer')

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
<li class="inline-flex items-center text-gray-700 font-medium">Create</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.customers.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
    @csrf
    
    <!-- Personal Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Personal Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                    First Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="first_name" 
                    id="first_name" 
                    value="{{ old('first_name') }}"
                    required
                    class="input-field @error('first_name') border-red-500 @enderror"
                    placeholder="Enter first name"
                >
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Last Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="last_name" 
                    id="last_name" 
                    value="{{ old('last_name') }}"
                    required
                    class="input-field @error('last_name') border-red-500 @enderror"
                    placeholder="Enter last name"
                >
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Contact Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    required
                    class="input-field @error('email') border-red-500 @enderror"
                    placeholder="customer@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input 
                    type="tel" 
                    name="phone" 
                    id="phone" 
                    value="{{ old('phone') }}"
                    required
                    class="input-field @error('phone') border-red-500 @enderror"
                    placeholder="+1 (555) 000-0000"
                >
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Password Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Account Password</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    required
                    class="input-field @error('password') border-red-500 @enderror"
                    placeholder="Enter password"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    required
                    class="input-field"
                    placeholder="Confirm password"
                >
                <p class="mt-1 text-sm text-gray-500">Re-enter the password</p>
            </div>
        </div>
    </div>
    
    <!-- Additional Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Additional Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="loyalty_points" class="block text-sm font-medium text-gray-700 mb-2">
                    Initial Loyalty Points
                </label>
                <input 
                    type="number" 
                    name="loyalty_points" 
                    id="loyalty_points" 
                    value="{{ old('loyalty_points', 0) }}"
                    min="0"
                    class="input-field @error('loyalty_points') border-red-500 @enderror"
                    placeholder="0"
                >
                @error('loyalty_points')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Default is 0 points</p>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Account Status <span class="text-red-500">*</span>
                </label>
                <select 
                    name="status" 
                    id="status"
                    required
                    class="input-field @error('status') border-red-500 @enderror"
                >
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-3">
        <x-button type="button" variant="secondary" onclick="window.location='{{ route('admin.customers.index') }}'">
            Cancel
        </x-button>
        <x-button type="submit" variant="primary" icon="M5 13l4 4L19 7">
            Create Customer
        </x-button>
    </div>
</form>
@endsection
