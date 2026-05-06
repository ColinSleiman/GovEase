<header class="citizen-navbar">
    <div class="citizen-navbar-inner">
        <div>
            <p class="citizen-navbar-kicker">Citizen Portal</p>
            <h2 class="citizen-navbar-title">Dashboard</h2>
        </div>

        <div class="citizen-navbar-actions">
            <span class="text-sm text-slate-600">{{ auth()->user()?->full_name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-base btn-variant-white">Logout</button>
            </form>
        </div>
    </div>
</header>
