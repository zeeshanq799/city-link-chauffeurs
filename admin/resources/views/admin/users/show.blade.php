@extends('layouts.app')

@section('title', 'User Details')

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
<li class="inline-flex items-center text-gray-700 font-medium">{{ $user->name }}</li>
@endsection

@section('actions')
<div class="flex items-center space-x-3">
    @can('update', $user)
    <x-button href="{{ route('admin.users.edit', $user) }}" variant="primary" icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
        Edit User
    </x-button>
    @endcan
    <x-button href="{{ route('admin.users.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
        Back to List
    </x-button>
</div>
@endsection

@section('content')
<!-- User Profile Header -->
<div class="card p-8 mb-6">
    <div class="flex items-center">
        <div class="flex-shrink-0 w-24 h-24 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white text-3xl font-bold">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="ml-6 flex-1">
            <div class="flex items-center">
                <h2 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h2>
                @if($user->id === auth()->user()->id)
                <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    You
                </span>
                @endif
            </div>
            <p class="text-lg text-gray-600 mt-1">{{ $user->email }}</p>
            <div class="flex items-center mt-3 space-x-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Active
                </span>
                <span class="text-sm text-gray-500">
                    Member since {{ $user->created_at->format('M d, Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- User Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <x-stat-card 
        title="Assigned Roles" 
        :value="$user->roles->count()"
        icon="chart"
        color="primary"
    />
    
    <x-stat-card 
        title="Total Permissions" 
        :value="$user->roles->flatMap->permissions->unique('id')->count()"
        icon="chart"
        color="secondary"
    />
    
    <x-stat-card 
        title="Account Age" 
        :value="$user->created_at->diffInDays(now()) . ' days'"
        icon="calendar"
        color="success"
    />
</div>

<!-- Account Information -->
<div class="card p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Account Information</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">User ID</label>
            <p class="text-base text-gray-900">#{{ $user->id }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
            <p class="text-base text-gray-900">{{ $user->email }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Account Created</label>
            <p class="text-base text-gray-900">{{ $user->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
            <p class="text-base text-gray-900">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Email Verified</label>
            @if($user->email_verified_at)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Verified
            </span>
            @else
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                Not Verified
            </span>
            @endif
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Account Status</label>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Active
            </span>
        </div>
    </div>
</div>

<!-- Assigned Roles -->
<div class="card p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Roles ({{ $user->roles->count() }})</h3>
    
    @if($user->roles->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($user->roles as $role)
        <div class="border border-gray-200 rounded-lg p-4 hover:border-primary-300 transition-colors">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-semibold text-gray-900">{{ $role->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $role->permissions->count() }} {{ Str::plural('permission', $role->permissions->count()) }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.roles.show', $role) }}" class="text-primary-600 hover:text-primary-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No roles assigned</h3>
        <p class="mt-1 text-sm text-gray-500">This user doesn't have any roles assigned yet.</p>
    </div>
    @endif
</div>

<!-- All Permissions (via Roles) -->
<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">All Permissions</h3>
    
    @php
        $allPermissions = $user->roles->flatMap->permissions->unique('id');
    @endphp
    
    @if($allPermissions->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
            $groupedPermissions = $allPermissions->groupBy(function($permission) {
                return explode('.', $permission->name)[0];
            });
        @endphp
        
        @foreach($groupedPermissions as $module => $modulePermissions)
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 capitalize flex items-center">
                    <span class="w-2 h-2 bg-primary-500 rounded-full mr-2"></span>
                    {{ str_replace('-', ' ', $module) }}
                    <span class="ml-auto text-xs text-gray-500 font-normal">
                        {{ $modulePermissions->count() }} {{ Str::plural('permission', $modulePermissions->count()) }}
                    </span>
                </h4>
            </div>
            <div class="p-4 bg-white">
                <div class="flex flex-wrap gap-2">
                    @foreach($modulePermissions as $permission)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        {{ ucfirst(str_replace(['.', '-'], ' ', $permission->name)) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions</h3>
        <p class="mt-1 text-sm text-gray-500">This user doesn't have any permissions through their assigned roles.</p>
    </div>
    @endif
</div>
@endsection
