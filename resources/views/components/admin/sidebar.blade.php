@props([
    'mobile' => false,
])

<div class="{{ $mobile ? 'space-y-6' : 'flex h-full flex-col' }}">
    <div class="{{ $mobile ? '' : 'border-b border-slate-800 px-6 py-5' }}">
        <h1 class="text-lg font-semibold tracking-wide text-white">GovEase Admin</h1>
        <p class="mt-1 text-xs text-slate-400">Operations and Monitoring</p>
    </div>

    <nav class="{{ $mobile ? '' : 'flex-1 overflow-y-auto px-4 py-6' }} space-y-5">
        <section class="space-y-2">
            <h2 class="px-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Municipalities Management</h2>
            <a href="{{ route('admin.municipalities.create') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Create Municipality
            </a>
            <a href="{{ route('admin.municipalities.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                View All Municipalities
            </a>
        </section>
        
        <section class="space-y-2">
            <h2 class="px-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Offices Management</h2>
            <a href="{{ route('admin.offices.create') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Create Office
            </a>
            <a href="{{ route('admin.offices.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                View All Offices
            </a>
        </section>

        <section class="space-y-2">
            <h2 class="px-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Users Management</h2>
            <a href="{{ route('admin.users.create') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Create User
            </a>
            <a href="{{ route('admin.users.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Manage Users
            </a>
        </section>

        <section class="space-y-2">
            <h2 class="px-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Services Monitoring</h2>
            <a href="{{ route('admin.requests.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                View Incoming Requests
            </a>
            <a href="{{ route('admin.services.monitor') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Monitor Services
            </a>
        </section>

        <section class="space-y-2">
            <h2 class="px-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Reports &amp; Analytics</h2>
            <a href="{{ route('admin.reports.office-requests') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Requests per Office
            </a>
            <a href="{{ route('admin.reports.revenue') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white">
                Revenue Reports
            </a>
        </section>
    </nav>
</div>
