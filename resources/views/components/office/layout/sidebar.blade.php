@props([
    'mobile' => false,
])

<div class="{{ $mobile ? 'admin-sidebar-nav-mobile' : 'admin-sidebar-shell' }}">
    <div class="{{ $mobile ? '' : 'admin-sidebar-header' }}">
        <h1 class="admin-sidebar-title">GovEase Office</h1>
        <p class="admin-sidebar-subtitle">Office Service Operations</p>
    </div>

    <nav class="{{ $mobile ? 'admin-sidebar-nav-mobile' : 'admin-sidebar-nav' }}">
        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Overview</h2>
            <a href="{{ route('office.dashboard') }}" class="admin-sidebar-link">
                Dashboard
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Requests Management</h2>
            <a href="{{ route('office.requests.index') }}" class="admin-sidebar-link">
                Incoming Requests
            </a>
        </section>

        <section class="admin-sidebar-group">
            <h2 class="admin-sidebar-group-title">Services Management</h2>
            <a href="{{ route('office.service-categories.index') }}" class="admin-sidebar-link">
                Service Categories
            </a>
            <a href="{{ route('office.services.index') }}" class="admin-sidebar-link">
                Services
            </a>
            <a href="{{ route('office.reviews.index') }}" class="admin-sidebar-link">
                Citizen Reviews
            </a>
        </section>
    </nav>
</div>
