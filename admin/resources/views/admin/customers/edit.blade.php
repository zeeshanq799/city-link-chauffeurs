@extends('layouts.app')

@section('title', 'Edit Customer')

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
<li class="inline-flex items-center text-gray-700 font-medium">Edit</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.customers.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
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
                    value="{{ old('first_name', $customer->first_name) }}"
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
                    value="{{ old('last_name', $customer->last_name) }}"
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
                    value="{{ old('email', $customer->email) }}"
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
                    value="{{ old('phone', $customer->phone) }}"
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
    
    <!-- Password Card (Optional) -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Change Password</h3>
        
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Leave password fields blank if you don't want to change the password.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    New Password
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="input-field @error('password') border-red-500 @enderror"
                    placeholder="Enter new password"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
            </div>
            
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm New Password
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation"
                    class="input-field"
                    placeholder="Confirm new password"
                >
                <p class="mt-1 text-sm text-gray-500">Re-enter the new password</p>
            </div>
        </div>
    </div>
    
    <!-- Additional Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Additional Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="loyalty_points" class="block text-sm font-medium text-gray-700 mb-2">
                    Loyalty Points
                </label>
                <div class="flex items-center space-x-2">
                    <input 
                        type="number" 
                        name="loyalty_points" 
                        id="loyalty_points" 
                        value="{{ old('loyalty_points', $customer->loyalty_points) }}"
                        min="0"
                        class="input-field @error('loyalty_points') border-red-500 @enderror"
                        placeholder="0"
                    >
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                </div>
                @error('loyalty_points')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Current: {{ number_format($customer->loyalty_points) }} points</p>
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
                    <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
            Update Customer
        </x-button>
    </div>
</form>
@endsection
