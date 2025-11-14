@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
@endsection

@section('content')
<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Customers -->
    <x-stat-card 
        title="Total Customers" 
        :value="\App\Models\Customer::count()"
        icon="users"
        color="primary"
        trend="+12%"
        trendDirection="up"
    />
    
    <!-- Active Bookings -->
    <x-stat-card 
        title="Active Bookings" 
        value="0"
        icon="calendar"
        color="success"
        trend="+0%"
        trendDirection="up"
    />
    
    <!-- Total Revenue -->
    <x-stat-card 
        title="Total Revenue" 
        value="$0"
        icon="money"
        color="warning"
        trend="+0%"
        trendDirection="up"
    />
    
    <!-- Pending Reviews -->
    <x-stat-card 
        title="Pending Reviews" 
        value="0"
        icon="star"
        color="secondary"
    />
</div>

<!-- Charts & Activity Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Bookings Chart -->
    <div class="lg:col-span-2">
        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Bookings Overview</h3>
                    <p class="text-sm text-gray-600">Last 7 days booking trends</p>
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                        Week
                    </button>
                    <button class="px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Month
                    </button>
                    <button class="px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        Year
                    </button>
                </div>
            </div>
            <canvas id="bookingsChart" height="100"></canvas>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="lg:col-span-1">
        <div class="card p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @php
                    $recentCustomers = \App\Models\Customer::latest()->take(5)->get();
                @endphp
                
                @forelse($recentCustomers as $customer)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center shadow-md">
                            <span class="text-sm font-semibold text-white">
                                {{ substr($customer->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                        <p class="text-xs text-gray-500">Registered as new customer</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $customer->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">No activity yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Latest Customers -->
    <div class="card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Latest Customers</h3>
            <a href="{{ route('admin.customers.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                View all →
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left border-b border-gray-200">
                    <tr>
                        <th class="pb-3 font-semibold text-gray-700">Customer</th>
                        <th class="pb-3 font-semibold text-gray-700">Email</th>
                        <th class="pb-3 font-semibold text-gray-700">Status</th>
                        <th class="pb-3 font-semibold text-gray-700">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $latestCustomers = \App\Models\Customer::latest()->take(5)->get();
                    @endphp
                    
                    @forelse($latestCustomers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                                    <span class="text-xs font-semibold text-white">
                                        {{ substr($customer->name, 0, 1) }}
                                    </span>
                                </div>
                                <span class="font-medium text-gray-900">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-gray-600">{{ $customer->email }}</td>
                        <td class="py-3">
                            @if($customer->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="py-3 text-gray-600">{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            No customers yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            @can('customers.create')
            <a href="{{ route('admin.customers.create') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Add Customer</span>
            </a>
            @endcan
            
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg hover:shadow-md transition-all group opacity-50 cursor-not-allowed">
                <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">New Booking</span>
                <span class="text-xs text-gray-500 mt-1">(Coming Soon)</span>
            </a>
            
            @can('users.create')
            <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Add User</span>
            </a>
            @endcan
            
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg hover:shadow-md transition-all group opacity-50 cursor-not-allowed">
                <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center mb-3 shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">Add Vehicle</span>
                <span class="text-xs text-gray-500 mt-1">(Coming Soon)</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('bookingsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Bookings',
                    data: [0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
