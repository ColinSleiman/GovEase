<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Admin Dashboard | GovEase')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="admin-layout-body">
        <div class="admin-layout-shell">
            <aside class="admin-layout-sidebar">
                <x-admin.layout.sidebar />
            </aside>

            <div class="admin-layout-content">
                <x-admin.layout.navbar />

                <main class="admin-layout-main">
                    <div class="admin-layout-container">
                        <div class="admin-layout-mobile-sidebar">
                            <x-admin.layout.sidebar :mobile="true" />
                        </div>

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
