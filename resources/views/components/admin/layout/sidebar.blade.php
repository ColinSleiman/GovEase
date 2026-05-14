@props([
    'mobile' => false,
])

<div class="{{ $mobile ? 'admin-sidebar-nav-mobile' : 'admin-sidebar-shell' }}">
    <div class="{{ $mobile ? '' : 'admin-sidebar-header' }}">
        <h1 class="admin-sidebar-title">GovEase Admin</h1>
        <p class="admin-sidebar-subtitle">Operations and Monitoring</p>
    </div>

    <nav class="{{ $mobile ? 'admin-sidebar-nav-mobile' : 'admin-sidebar-nav' }}">
        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Dashboard</h2>
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Overview
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Municipalities Management</h2>
            <a href="{{ route('admin.municipalities.create') }}" class="admin-sidebar-link">
                Create Municipality
            </a>
            <a href="{{ route('admin.municipalities.index') }}" class="admin-sidebar-link">
                View All Municipalities
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Offices Management</h2>
            <a href="{{ route('admin.offices.create') }}" class="admin-sidebar-link">
                Create Office
            </a>
            <a href="{{ route('admin.offices.index') }}" class="admin-sidebar-link">
                View All Offices
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Users Management</h2>
            <a href="{{ route('admin.users.create') }}" class="admin-sidebar-link">
                Create User
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar-link">
                Manage Users
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Services Monitoring</h2>
            <a href="{{ route('admin.requests.index') }}" class="admin-sidebar-link">
                View Incoming Requests
            </a>
            <a href="{{ route('admin.services.monitor') }}" class="admin-sidebar-link">
                Monitor Services
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Reports &amp; Analytics</h2>
            <a href="{{ route('admin.reports.office-requests') }}" class="admin-sidebar-link">
                Requests per Office
            </a>
            <a href="{{ route('admin.reports.revenue') }}" class="admin-sidebar-link">
                Revenue Reports
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Stripe Testing</h2>
            <a href="{{ route('admin.stripe.test') }}" class="admin-sidebar-link {{ request()->routeIs('admin.stripe.test') ? 'active' : '' }}">
                Payment Test Page
            </a>
            <a href="{{ route('admin.stripe.success') }}" class="admin-sidebar-link {{ request()->routeIs('admin.stripe.success') ? 'active' : '' }}">
                Success Page
            </a>
        </section>
    </nav>
</div>
