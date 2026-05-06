<header class="admin-navbar">
    <div class="admin-navbar-inner">
        <div>
            <p class="admin-navbar-kicker">Office Staff</p>
            <h2 class="admin-navbar-title">Dashboard</h2>
        </div>

        <div class="admin-navbar-actions">
            <span class="text-sm text-slate-600">{{ auth()->user()?->full_name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-base btn-variant-white">Logout</button>
            </form>
            <div class="admin-avatar"></div>
        </div>
    </div>
</header>
