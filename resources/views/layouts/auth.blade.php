<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Masuk') - BMT Cinde Barokah</title>

    <script>
        (function () {
            try {
                var theme = localStorage.getItem('theme') || 'system';
                var dark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
                var font = localStorage.getItem('font') || 'inter';
                if (font === 'manrope' || font === 'system') document.documentElement.classList.add('font-' + font);
            } catch (e) {}
        })();
    </script>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v={{ filemtime(public_path('images/favicon.png')) }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased">
    @include('partials.icons')

    <div class="auth-shell">
        <div class="w-full max-w-md py-8">
            <div class="mb-6 flex flex-col items-center gap-2 text-center">
                <img src="{{ asset('images/logo-bmt.png') }}?v={{ filemtime(public_path('images/logo-bmt.png')) }}" alt="Logo BMT Sumur Cinde Barokah"
                    class="size-10 rounded-xl bg-white object-contain">
                <div>
                    <a href="{{ route('dashboard') }}" class="text-lg font-semibold">BMT Cinde Barokah</a>
                    <p class="text-xs text-muted-foreground">Baitul Maal wat Tamwil</p>
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <div class="fixed right-4 top-4">
        <div class="dropdown">
            <button type="button" class="btn btn-ghost btn-icon" data-dropdown-trigger aria-expanded="false" aria-label="Toggle theme">
                <svg class="icon hidden" data-theme-check="light"><use href="#icon-sun"></use></svg>
                <svg class="icon hidden" data-theme-check="dark"><use href="#icon-moon"></use></svg>
                <svg class="icon hidden" data-theme-check="system"><use href="#icon-monitor"></use></svg>
            </button>
            <div data-dropdown class="dropdown-menu hidden w-40">
                <div class="dropdown-label">Theme</div>
                <button type="button" class="dropdown-item" data-set-theme="light">
                    <svg class="icon"><use href="#icon-sun"></use></svg> Light
                    <svg class="icon icon-sm ms-auto hidden" data-theme-check="light"><use href="#icon-check"></use></svg>
                </button>
                <button type="button" class="dropdown-item" data-set-theme="dark">
                    <svg class="icon"><use href="#icon-moon"></use></svg> Dark
                    <svg class="icon icon-sm ms-auto hidden" data-theme-check="dark"><use href="#icon-check"></use></svg>
                </button>
                <button type="button" class="dropdown-item" data-set-theme="system">
                    <svg class="icon"><use href="#icon-monitor"></use></svg> System
                    <svg class="icon icon-sm ms-auto hidden" data-theme-check="system"><use href="#icon-check"></use></svg>
                </button>
            </div>
        </div>
    </div>

    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>
    <div id="flash-message" data-message="{{ session('status') }}"></div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
