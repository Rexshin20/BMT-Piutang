{{-- Sidebar --}}
<aside class="app-sidebar" id="sidebar" aria-label="Sidebar">
    <div class="flex h-16 shrink-0 items-center border-b border-sidebar-border px-3">
        <div class="flex w-full items-center gap-2 p-1.5">
            <div class="flex aspect-square size-8 shrink-0 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                <svg class="icon"><use href="#icon-command"></use></svg>
            </div>
            <div class="grid min-w-0 flex-1 text-sm leading-tight">
                <span class="truncate font-semibold">BMT Perhutangan</span>
                <span class="truncate text-xs text-muted-foreground">Baitul Maal wat Tamwil</span>
            </div>
        </div>
    </div>

    <div class="sidebar-scroll">
        <div class="sidebar-group-label pb-1 pt-1">General</div>
        <nav class="space-y-1">
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="icon"><use href="#icon-layout-dashboard"></use></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('piutang.index') }}" class="sidebar-item {{ request()->routeIs('piutang.*') ? 'active' : '' }}">
                <svg class="icon"><use href="#icon-wallet"></use></svg>
                <span>Piutang</span>
            </a>
        </nav>

        <div class="sidebar-group-label">Master Data</div>
        <nav class="space-y-1">
            <a href="{{ route('master.borrowers') }}" class="sidebar-item {{ request()->routeIs('master.borrowers') ? 'active' : '' }}">
                <svg class="icon"><use href="#icon-users"></use></svg>
                <span>Data Peminjam</span>
            </a>
        </nav>

        <div class="sidebar-group-label">Pages</div>
        <nav class="space-y-1">
            <div class="collapsible {{ request()->routeIs('errors.*') ? 'open' : '' }}">
                <button type="button" data-collapse-trigger aria-expanded="{{ request()->routeIs('errors.*') ? 'true' : 'false' }}"
                    class="sidebar-item w-full {{ request()->routeIs('errors.*') ? 'active' : '' }}">
                    <svg class="icon"><use href="#icon-bug"></use></svg>
                    <span class="flex-1 text-left">Errors</span>
                    <svg class="icon chevron"><use href="#icon-chevron-right"></use></svg>
                </button>
                <div class="collapse-content">
                    <div class="sidebar-sub">
                        @php $errorCode = request()->route('code'); @endphp
                        <a href="{{ route('errors.show', 401) }}" class="sidebar-subitem {{ $errorCode == '401' ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-lock"></use></svg> Unauthorized
                        </a>
                        <a href="{{ route('errors.show', 403) }}" class="sidebar-subitem {{ $errorCode == '403' ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-user-x"></use></svg> Forbidden
                        </a>
                        <a href="{{ route('errors.show', 404) }}" class="sidebar-subitem {{ $errorCode == '404' ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-file-x"></use></svg> Not Found
                        </a>
                        <a href="{{ route('errors.show', 500) }}" class="sidebar-subitem {{ $errorCode == '500' ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-server-off"></use></svg> Internal Server Error
                        </a>
                        <a href="{{ route('errors.show', 503) }}" class="sidebar-subitem {{ $errorCode == '503' ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-construction"></use></svg> Maintenance
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="sidebar-group-label">Other</div>
        <nav class="space-y-1">
            <div class="collapsible {{ request()->routeIs('settings.*') ? 'open' : '' }}">
                <button type="button" data-collapse-trigger aria-expanded="{{ request()->routeIs('settings.*') ? 'true' : 'false' }}"
                    class="sidebar-item w-full {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg class="icon"><use href="#icon-settings"></use></svg>
                    <span class="flex-1 text-left">Settings</span>
                    <svg class="icon chevron"><use href="#icon-chevron-right"></use></svg>
                </button>
                <div class="collapse-content">
                    <div class="sidebar-sub">
                        <a href="{{ route('settings.account') }}" class="sidebar-subitem {{ request()->routeIs('settings.account') ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-wrench"></use></svg> Akun
                        </a>
                        <a href="{{ route('settings.appearance') }}" class="sidebar-subitem {{ request()->routeIs('settings.appearance') ? 'active' : '' }}">
                            <svg class="icon"><use href="#icon-palette"></use></svg> Appearance
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ route('help.index') }}" class="sidebar-item {{ request()->routeIs('help.*') ? 'active' : '' }}">
                <svg class="icon"><use href="#icon-circle-help"></use></svg>
                <span>Help Center</span>
            </a>
        </nav>
    </div>

    <div class="border-t border-sidebar-border p-2">
        @auth
            @php $sidebarUser = auth()->user(); @endphp
            <div class="dropdown w-full">
                <button type="button" data-dropdown-trigger aria-expanded="false"
                    class="flex w-full items-center gap-2 rounded-md p-2 text-left transition-colors hover:bg-sidebar-accent hover:text-sidebar-accent-foreground">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border bg-muted text-xs font-semibold">{{ $sidebarUser->initials() }}</span>
                    <span class="grid min-w-0 flex-1 text-sm leading-tight">
                        <span class="truncate font-semibold">{{ $sidebarUser->name }}</span>
                        <span class="truncate text-xs text-muted-foreground">{{ $sidebarUser->email }}</span>
                    </span>
                    <svg class="icon size-4 shrink-0 text-muted-foreground"><use href="#icon-chevrons-up-down"></use></svg>
                </button>
                <div data-dropdown class="dropdown-menu left top-auto bottom-full mt-0 mb-1 hidden w-full min-w-56">
                    <div class="px-2 py-2">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg border bg-muted text-xs font-semibold">{{ $sidebarUser->initials() }}</span>
                            <div class="grid min-w-0 text-sm leading-tight">
                                <span class="truncate font-semibold">{{ $sidebarUser->name }}</span>
                                <span class="truncate text-xs text-muted-foreground">{{ $sidebarUser->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-separator"></div>
                    <a href="{{ route('settings.account') }}" class="dropdown-item">
                        <svg class="icon"><use href="#icon-badge-check"></use></svg> Akun
                    </a>
                    <div class="dropdown-separator"></div>
                    <button type="button" data-dialog-open="signout-dialog" class="dropdown-item text-destructive">
                        <svg class="icon"><use href="#icon-log-out"></use></svg> Keluar
                    </button>
                </div>
            </div>
        @else
            <a href="{{ route('auth.signin') }}" class="btn btn-primary w-full">
                <svg class="icon"><use href="#icon-log-in"></use></svg> Masuk
            </a>
        @endauth
    </div>
</aside>
<div class="sidebar-overlay" data-sidebar-close></div>
