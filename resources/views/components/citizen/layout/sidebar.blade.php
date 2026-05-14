@props([
    'mobile' => false,
])

<div class="{{ $mobile ? 'citizen-sidebar-nav-mobile' : 'citizen-sidebar-shell' }}">
    <div class="{{ $mobile ? '' : 'citizen-sidebar-header' }}">
        <h1 class="citizen-sidebar-title">GovEase Citizen</h1>
        <p class="citizen-sidebar-subtitle">Request and Track Services</p>
    </div>

    <nav class="{{ $mobile ? 'citizen-sidebar-nav-mobile' : 'citizen-sidebar-nav' }}">
        <section class="citizen-sidebar-group">
            <h2 class="citizen-sidebar-group-title">Dashboard</h2>
            <a href="{{ route('citizen.dashboard') }}" class="citizen-sidebar-link">Overview</a>
        </section>

        <section class="citizen-sidebar-group">
            <h2 class="citizen-sidebar-group-title">Requests</h2>
            <a href="{{ route('citizen.requests.create') }}" class="citizen-sidebar-link">Create Request</a>
            <a href="{{ route('citizen.requests.index') }}" class="citizen-sidebar-link">My Requests</a>
        </section>

        <section class="citizen-sidebar-group">
            <h2 class="citizen-sidebar-group-title">Feedback</h2>
            <a href="{{ route('citizen.reviews.index') }}" class="citizen-sidebar-link">Rate Services</a>
        </section>
    </nav>
</div>
