<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'BMT Perhutangan')</title>

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

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="min-h-screen bg-background font-sans text-foreground antialiased">
    @include('partials.icons')
    @include('partials.sidebar')

    <div class="app-main">
        <header class="app-header">
            <button type="button" class="btn btn-outline btn-icon lg:hidden" data-sidebar-toggle aria-label="Toggle sidebar">
                <svg class="icon"><use href="#icon-menu"></use></svg>
            </button>

            @hasSection('topnav')
                <nav class="hidden items-center gap-1 md:flex">
                    @yield('topnav')
                </nav>
            @endif

            <div class="ms-auto flex items-center gap-2">
                <button type="button" data-dialog-open="command-dialog"
                    class="btn btn-outline h-8 justify-start gap-2 px-2.5 text-muted-foreground sm:w-56 sm:justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="icon icon-sm"><use href="#icon-search"></use></svg>
                        <span class="hidden sm:inline">Search</span>
                    </span>
                    <kbd class="hidden rounded border bg-muted px-1.5 py-0.5 text-[10px] font-medium tracking-wide sm:inline-flex">&#8984;K</kbd>
                </button>

                {{-- Theme switch --}}
                <div class="dropdown">
                    <button type="button" class="btn btn-ghost btn-icon" data-dropdown-trigger aria-expanded="false" aria-label="Toggle theme">
                        <svg class="icon hidden" data-theme-check="light"><use href="#icon-sun"></use></svg>
                        <svg class="icon hidden" data-theme-check="dark"><use href="#icon-moon"></use></svg>
                        <svg class="icon hidden" data-theme-check="system"><use href="#icon-monitor"></use></svg>
                    </button>
                    <div data-dropdown class="dropdown-menu hidden w-40">
                        <div class="dropdown-label">Theme</div>
                        <button type="button" class="dropdown-item" data-set-theme="light">
                            <svg class="icon"><use href="#icon-sun"></use></svg>
                            Light
                            <svg class="icon icon-sm ms-auto hidden" data-theme-check="light"><use href="#icon-check"></use></svg>
                        </button>
                        <button type="button" class="dropdown-item" data-set-theme="dark">
                            <svg class="icon"><use href="#icon-moon"></use></svg>
                            Dark
                            <svg class="icon icon-sm ms-auto hidden" data-theme-check="dark"><use href="#icon-check"></use></svg>
                        </button>
                        <button type="button" class="dropdown-item" data-set-theme="system">
                            <svg class="icon"><use href="#icon-monitor"></use></svg>
                            System
                            <svg class="icon icon-sm ms-auto hidden" data-theme-check="system"><use href="#icon-check"></use></svg>
                        </button>
                    </div>
                </div>

                {{-- Config drawer --}}
                <button type="button" class="btn btn-ghost btn-icon" data-dialog-open="config-drawer" aria-label="Open theme config">
                    <svg class="icon"><use href="#icon-palette"></use></svg>
                </button>

                <div class="mx-1 h-6 w-px bg-border"></div>

                {{-- Profile dropdown --}}
                @auth
                    @php $currentUser = auth()->user(); @endphp
                    <div class="dropdown">
                        <button type="button" class="flex items-center gap-2 rounded-full" data-dropdown-trigger aria-expanded="false" aria-label="Buka menu profil">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-green-700 text-xs font-semibold text-white">{{ $currentUser->initials() }}</span>
                            <span class="hidden flex-col text-left sm:flex">
                                <span class="text-sm font-semibold leading-tight">{{ $currentUser->name }}</span>
                                <span class="text-xs text-muted-foreground">{{ $currentUser->email }}</span>
                            </span>
                        </button>
                        <div data-dropdown class="dropdown-menu hidden w-56">
                            <div class="px-2 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-green-700 text-xs font-semibold text-white">{{ $currentUser->initials() }}</span>
                                    <div class="grid min-w-0 text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ $currentUser->name }}</span>
                                        <span class="truncate text-xs text-muted-foreground">{{ $currentUser->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-separator"></div>
                            <a href="{{ route('settings.account') }}" class="dropdown-item">
                                <svg class="icon"><use href="#icon-badge-check"></use></svg> Akun
                            </a>
                            <div class="dropdown-separator"></div>
                            <button type="button" class="dropdown-item text-destructive" data-dialog-open="signout-dialog">
                                <svg class="icon"><use href="#icon-log-out"></use></svg> Keluar
                            </button>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.signin') }}" class="btn btn-primary h-8">Masuk</a>
                @endauth
            </div>
        </header>

        <main class="app-content @yield('main-class')">
            @yield('content')
        </main>

        @unless (View::hasSection('hide-footer'))
            <footer class="app-footer">
                &copy; {{ date('Y') }} BMT Perhutangan &middot; Baitul Maal wat Tamwil
            </footer>
        @endunless
    </div>

    {{-- Command menu --}}
    <div class="dialog" id="command-dialog" role="dialog" aria-modal="true" aria-labelledby="command-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel mt-[18vh] max-w-lg p-0">
            <h2 id="command-title" class="sr-only">Command menu</h2>
            <div class="flex items-center gap-2 border-b px-3">
                <svg class="icon icon-sm text-muted-foreground"><use href="#icon-search"></use></svg>
                <input type="text" data-command-input autofocus
                    class="h-11 w-full bg-transparent text-sm outline-none placeholder:text-muted-foreground"
                    placeholder="Type a command or search...">
            </div>
            <div class="command-list">
                <div class="px-3 py-1.5 text-xs font-medium text-muted-foreground">Pages</div>
                <a href="{{ route('dashboard') }}" class="command-item" data-command-item data-href="{{ route('dashboard') }}" data-keywords="home overview">
                    <svg class="icon text-muted-foreground"><use href="#icon-layout-dashboard"></use></svg> Dashboard
                </a>
                <a href="{{ route('piutang.index') }}" class="command-item" data-command-item data-href="{{ route('piutang.index') }}" data-keywords="receivables tagihan hutang">
                    <svg class="icon text-muted-foreground"><use href="#icon-wallet"></use></svg> Piutang
                </a>
                <a href="{{ route('master.borrowers') }}" class="command-item" data-command-item data-href="{{ route('master.borrowers') }}" data-keywords="peminjam borrower master nama alamat no hp">
                    <svg class="icon text-muted-foreground"><use href="#icon-users"></use></svg> Data Peminjam
                </a>
                <a href="{{ route('settings.account') }}" class="command-item" data-command-item data-href="{{ route('settings.account') }}" data-keywords="preferences">
                    <svg class="icon text-muted-foreground"><use href="#icon-settings"></use></svg> Settings
                </a>
                <a href="{{ route('help.index') }}" class="command-item" data-command-item data-href="{{ route('help.index') }}" data-keywords="support">
                    <svg class="icon text-muted-foreground"><use href="#icon-circle-help"></use></svg> Help Center
                </a>
            </div>
        </div>
    </div>

    {{-- Config drawer --}}
    <div class="dialog drawer" id="config-drawer" role="dialog" aria-modal="true" aria-labelledby="config-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel">
            <button type="button" class="dialog-close" data-dialog-close aria-label="Close">
                <svg class="icon"><use href="#icon-x"></use></svg>
            </button>
            <div class="dialog-header">
                <h2 id="config-title" class="dialog-title">Theme Preferences</h2>
                <p class="dialog-description">Tweak the appearance of the dashboard.</p>
            </div>

            <div class="space-y-6">
                <div class="space-y-2">
                    <span class="label">Theme</span>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer rounded-lg border-2 border-muted p-1 has-[:checked]:border-primary">
                            <input type="radio" name="drawer-theme" value="light" class="sr-only" data-theme-option>
                            <span class="block space-y-1 rounded-md bg-[#ecedef] p-2">
                                <span class="block h-2 w-10 rounded bg-white"></span>
                                <span class="block h-2 w-8 rounded bg-white"></span>
                                <span class="block h-6 rounded bg-white"></span>
                            </span>
                            <span class="block py-1.5 text-center text-xs font-medium">Light</span>
                        </label>
                        <label class="cursor-pointer rounded-lg border-2 border-muted p-1 has-[:checked]:border-primary">
                            <input type="radio" name="drawer-theme" value="dark" class="sr-only" data-theme-option>
                            <span class="block space-y-1 rounded-md bg-slate-950 p-2">
                                <span class="block h-2 w-10 rounded bg-slate-700"></span>
                                <span class="block h-2 w-8 rounded bg-slate-700"></span>
                                <span class="block h-6 rounded bg-slate-800"></span>
                            </span>
                            <span class="block py-1.5 text-center text-xs font-medium">Dark</span>
                        </label>
                        <label class="cursor-pointer rounded-lg border-2 border-muted p-1 has-[:checked]:border-primary">
                            <input type="radio" name="drawer-theme" value="system" class="sr-only" data-theme-option>
                            <span class="block space-y-1 rounded-md bg-gradient-to-br from-[#ecedef] to-slate-800 p-2">
                                <span class="block h-2 w-10 rounded bg-white/70"></span>
                                <span class="block h-2 w-8 rounded bg-white/70"></span>
                                <span class="block h-6 rounded bg-white/40"></span>
                            </span>
                            <span class="block py-1.5 text-center text-xs font-medium">System</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="label" for="drawer-font">Font</label>
                    <select id="drawer-font" class="select" onchange="window.App && App.setFont(this.value)">
                        <option value="inter">Inter</option>
                        <option value="manrope">Manrope</option>
                        <option value="system">System</option>
                    </select>
                    <p class="form-description">Set the font you want to use in the dashboard.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sign out dialog --}}
    <div class="dialog" id="signout-dialog" role="dialog" aria-modal="true" aria-labelledby="signout-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <div class="dialog-header">
                <h2 id="signout-title" class="dialog-title">Keluar</h2>
                <p class="dialog-description">Yakin ingin keluar? Anda perlu masuk kembali untuk mengakses dashboard.</p>
            </div>
            <form method="POST" action="{{ route('auth.signout') }}">
                @csrf
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Batal</button>
                    <button type="submit" class="btn btn-destructive">Keluar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast-container" aria-live="polite" aria-atomic="true"></div>
    <div id="flash-message" data-message="{{ session('status') }}"></div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
