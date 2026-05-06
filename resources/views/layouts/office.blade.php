<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Office Dashboard | GovEase')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="admin-layout-body">
        <div class="admin-layout-shell">
            <aside class="admin-layout-sidebar">
                <x-office.layout.sidebar />
            </aside>

            <div class="admin-layout-content">
                <x-office.layout.navbar />

                <main class="admin-layout-main">
                    <div class="admin-layout-container">
                        <div class="admin-layout-mobile-sidebar">
                            <x-office.layout.sidebar :mobile="true" />
                        </div>

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
