@extends('layouts.app')

@section('title', 'Edit Role')

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
<li class="inline-flex items-center text-gray-700 font-medium">Edit</li>
@endsection

@section('actions')
<x-button href="{{ route('admin.roles.index') }}" variant="secondary" icon="M10 19l-7-7m0 0l7-7m-7 7h18">
    Back to List
</x-button>
@endsection

@section('content')
<form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')
    
    <!-- Basic Information Card -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
        
        <div class="max-w-xl">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Role Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                value="{{ old('name', $role->name) }}"
                required
                class="input-field @error('name') border-red-500 @enderror"
                placeholder="e.g., Manager, Editor, Viewer"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Choose a descriptive name for this role</p>
        </div>
    </div>
    
    <!-- Permissions Card -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Permissions</h3>
                <p class="text-sm text-gray-600 mt-1">Select the permissions for this role</p>
            </div>
            <button type="button" id="selectAll" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                Select All
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($permissions as $module => $modulePermissions)
            <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-primary-300 transition-colors">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-gray-900 capitalize">
                            {{ str_replace('-', ' ', $module) }}
                        </h4>
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                class="select-module w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                                data-module="{{ $module }}"
                            >
                            <span class="ml-2 text-xs text-gray-600">Select All</span>
                        </label>
                    </div>
                </div>
                <div class="p-4 space-y-3">
                    @foreach($modulePermissions as $permission)
                    <label class="flex items-center group cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                        <input 
                            type="checkbox" 
                            name="permissions[]" 
                            value="{{ $permission->id }}"
                            class="permission-checkbox module-{{ $module }} w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2"
                            {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                        >
                        <span class="ml-3 text-sm text-gray-700 group-hover:text-gray-900">
                            {{ ucfirst(str_replace('.', ' ', $permission->name)) }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        
        @error('permissions')
            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-3">
        <x-button type="button" variant="secondary" onclick="window.location='{{ route('admin.roles.index') }}'">
            Cancel
        </x-button>
        <x-button type="submit" variant="primary" icon="M5 13l4 4L19 7">
            Update Role
        </x-button>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        this.textContent = allChecked ? 'Select All' : 'Deselect All';
    });
    
    // Select module functionality
    document.querySelectorAll('.select-module').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = this.dataset.module;
            const moduleCheckboxes = document.querySelectorAll(`.module-${module}`);
            moduleCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    });
    
    // Update module checkbox state when individual checkboxes change
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const module = Array.from(this.classList).find(c => c.startsWith('module-')).replace('module-', '');
            const moduleCheckboxes = document.querySelectorAll(`.module-${module}`);
            const moduleSelectAll = document.querySelector(`.select-module[data-module="${module}"]`);
            
            if (moduleSelectAll) {
                moduleSelectAll.checked = Array.from(moduleCheckboxes).every(cb => cb.checked);
            }
        });
    });
    
    // Initialize module checkboxes state on page load
    document.querySelectorAll('.select-module').forEach(checkbox => {
        const module = checkbox.dataset.module;
        const moduleCheckboxes = document.querySelectorAll(`.module-${module}`);
        checkbox.checked = Array.from(moduleCheckboxes).every(cb => cb.checked);
    });
});
</script>
@endpush
