<!-- Sidebar -->
<aside 
    class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 bg-white border-r border-gray-200 shadow-lg"
    :class="open ? 'w-64' : 'w-20'"
    x-cloak>
    
    <!-- Logo Section -->
    <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-primary-700 rounded-lg flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span x-show="open" x-transition class="text-xl font-bold text-gray-900 whitespace-nowrap">
                City Link
            </span>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 overflow-y-auto">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Dashboard</span>
                </a>
            </li>
            
            <!-- Divider -->
            <li x-show="open" x-transition class="pt-4 pb-2">
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</span>
            </li>
            
            <!-- Customer Management -->
            @can('customers.view')
            <li>
                <a href="{{ route('admin.customers.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group {{ request()->routeIs('admin.customers.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.customers.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Customers</span>
                    <span x-show="open" x-transition class="ml-auto px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-700">
                        {{ \App\Models\Customer::count() }}
                    </span>
                </a>
            </li>
            @endcan
            
            <!-- Bookings (Coming Soon) -->
            <li>
                <a href="#" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group text-gray-400 cursor-not-allowed">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Bookings</span>
                    <span x-show="open" x-transition class="ml-auto text-xs text-gray-400">(Soon)</span>
                </a>
            </li>
            
            <!-- Vehicles -->
            <li>
                <a href="{{ route('admin.vehicles.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group {{ request()->routeIs('admin.vehicles.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.vehicles.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Vehicles</span>
                </a>
            </li>
            
            <!-- Drivers -->
            <li>
                <a href="{{ route('admin.drivers.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group {{ request()->routeIs('admin.drivers.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.drivers.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Drivers</span>
                    <span x-show="open" x-transition class="ml-auto px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-700">
                        {{ \App\Models\Driver::count() }}
                    </span>
                </a>
            </li>
            
            <!-- Divider -->
            <li x-show="open" x-transition class="pt-4 pb-2">
                <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">System</span>
            </li>
            
            <!-- User Management -->
            @can('users.view')
            <li>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Users</span>
                </a>
            </li>
            @endcan
            
            <!-- Role Management -->
            @can('roles.view')
            <li>
                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all group {{ request()->routeIs('admin.roles.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.roles.*') ? 'text-primary-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span x-show="open" x-transition class="ml-3 whitespace-nowrap">Roles & Permissions</span>
                </a>
            </li>
            @endcan
        </ul>
    </nav>
    
    <!-- Bottom Section -->
    <div class="p-4 border-t border-gray-200">
        <button @click="open = !open" 
                class="w-full flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span x-show="open" x-transition class="ml-2">Collapse</span>
        </button>
    </div>
</aside>
