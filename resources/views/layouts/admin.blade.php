<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Admin Dashboard | GovEase')</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    
    <body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
        <div class="flex min-h-screen">
            <aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-slate-900 text-slate-100 lg:block">
                <x-admin.sidebar />
            </aside>

            <div class="flex min-w-0 flex-1 flex-col">
                <x-admin.navbar />

                <main class="flex-1 p-4 sm:p-6 lg:p-8">
                    <div class="mx-auto max-w-7xl">
                        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-3 shadow-sm lg:hidden">
                            <x-admin.sidebar :mobile="true" />
                        </div>

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
