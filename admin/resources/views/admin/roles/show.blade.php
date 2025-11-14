@extends('layouts.app')

@section('title', 'Role Details')

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
    <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:text-primary-600">Roles</a>
    <svg class="w-5 h-5 text-gray-400 mx-2" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
    </svg>
</li>
<li class="inline-flex items-center text-gray-700 font-medium">{{ $role->name }}</li>
@endsection

@section('actions')
<div class="flex items-center space-x-3">
    @can('update', $role)
    <x-button href="{{ route('admin.roles.edit', $role) }}" variant="primary" icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
        Edit Role
    </x-button>
    @endcan
    <x-button href="{{ route('admin.roles.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
        Back to List
    </x-button>
</div>
@endsection

@section('content')
<!-- Role Information & Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="card p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-gray-900">{{ $role->name }}</h3>
                <p class="text-sm text-gray-500">Role Name</p>
            </div>
        </div>
    </div>
    
    <x-stat-card 
        title="Total Users" 
        :value="$role->users->count()"
        icon="users"
        color="secondary"
    />
    
    <x-stat-card 
        title="Total Permissions" 
        :value="$role->permissions->count()"
        icon="chart"
        color="success"
    />
</div>

<!-- Role Details -->
<div class="card p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Role Information</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Role ID</label>
            <p class="text-base text-gray-900">#{{ $role->id }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Created Date</label>
            <p class="text-base text-gray-900">{{ $role->created_at->format('M d, Y') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
            <p class="text-base text-gray-900">{{ $role->updated_at->format('M d, Y') }}</p>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Active
            </span>
        </div>
    </div>
</div>

<!-- Assigned Users -->
<div class="card p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Users ({{ $role->users->count() }})</h3>
    
    @if($role->users->count() > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($role->users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No users assigned</h3>
        <p class="mt-1 text-sm text-gray-500">This role hasn't been assigned to any users yet.</p>
    </div>
    @endif
</div>

<!-- Permissions -->
<div class="card p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Permissions ({{ $role->permissions->count() }})</h3>
    
    @if($role->permissions->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
            $groupedPermissions = $role->permissions->groupBy(function($permission) {
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
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No permissions assigned</h3>
        <p class="mt-1 text-sm text-gray-500">This role doesn't have any permissions yet.</p>
    </div>
    @endif
</div>
@endsection
