<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Citizen Dashboard | GovEase')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="citizen-layout-body">
        <div class="citizen-layout-shell">
            <aside class="citizen-layout-sidebar">
                <x-citizen.layout.sidebar />
            </aside>

            <div class="citizen-layout-content">
                <x-citizen.layout.navbar />

                <main class="citizen-layout-main">
                    <div class="citizen-layout-container">
                        <div class="citizen-layout-mobile-sidebar">
                            <x-citizen.layout.sidebar :mobile="true" />
                        </div>

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
