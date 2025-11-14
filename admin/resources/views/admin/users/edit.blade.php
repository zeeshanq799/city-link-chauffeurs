@extends('layouts.app')

@section('title', 'Edit User')

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
    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-primary-600">Users</a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center text-gray-700 font-medium">Edit</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.users.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    <!-- Basic Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $user->name) }}"
                    required
                    class="input-field @error('name') border-red-500 @enderror"
                    placeholder="Enter full name"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email', $user->email) }}"
                    required
                    class="input-field @error('email') border-red-500 @enderror"
                    placeholder="Enter email address"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Password Card -->
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
    
    <!-- Role Assignment Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Role Assignment</h3>
        
        <div class="max-w-xl">
            <label for="roles" class="block text-sm font-medium text-gray-700 mb-2">
                Assign Roles <span class="text-red-500">*</span>
            </label>
            <select 
                name="roles[]" 
                id="roles" 
                multiple
                required
                class="input-field @error('roles') border-red-500 @enderror"
                size="5"
            >
                @foreach($roles as $role)
                <option value="{{ $role->id }}" 
                    {{ (collect(old('roles', $user->roles->pluck('id')))->contains($role->id)) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
            @error('roles')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Hold Ctrl/Cmd to select multiple roles</p>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-3">
        <x-button type="button" variant="secondary" onclick="window.location='{{ route('admin.users.index') }}'">
            Cancel
        </x-button>
        <x-button type="submit" variant="primary" icon="M5 13l4 4L19 7">
            Update User
        </x-button>
    </div>
</form>
@endsection
