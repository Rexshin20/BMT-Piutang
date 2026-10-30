<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'Error') - BMT Cinde Barokah</title>

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

    <div class="error-shell">
        @yield('content')
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

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
