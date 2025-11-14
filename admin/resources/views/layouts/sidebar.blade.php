<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <i class="fas fa-car brand-image"></i>
        <span class="brand-text font-weight-light">City Link Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x text-white"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                <small class="text-muted">
                    @foreach(auth()->user()->roles as $role)
                        {{ $role->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Fleet Management -->
                <li class="nav-header">FLEET MANAGEMENT</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.vehicle-types.index') }}" class="nav-link {{ request()->routeIs('admin.vehicle-types.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Vehicle Types</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.vehicles.index') }}" class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Vehicles</p>
                    </a>
                </li>

                <!-- User Management -->
                <li class="nav-header">USER MANAGEMENT</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Roles & Permissions</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>

                <!-- Customer Management -->
                <li class="nav-header">CUSTOMER MANAGEMENT</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Customers</p>
                    </a>
                </li>

                <!-- More sections to be added -->
                <li class="nav-header">COMING SOON</li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-id-badge"></i>
                        <p>Drivers</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Bookings</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
