@php
    $items = [
        ['route' => 'settings.account', 'title' => 'Akun', 'icon' => 'wrench'],
        ['route' => 'settings.appearance', 'title' => 'Appearance', 'icon' => 'palette'],
    ];
@endphp

<div class="flex flex-col gap-6">
    <div class="space-y-0.5">
        <h1 class="text-2xl font-bold tracking-tight md:text-3xl">Pengaturan</h1>
        <p class="text-muted-foreground">Kelola pengaturan akun dan preferensi aplikasi Anda.</p>
    </div>

    <div class="border-t"></div>

    <div class="flex flex-col gap-6 lg:flex-row lg:gap-12">
        <aside class="lg:w-1/5">
            <nav class="flex gap-1 overflow-x-auto pb-2 lg:flex-col lg:space-y-1 lg:overflow-visible lg:pb-0">
                @foreach ($items as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex h-9 shrink-0 items-center gap-2 rounded-md px-3 text-sm font-medium transition-colors {{ request()->routeIs($item['route']) ? 'bg-muted text-foreground' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
                        <svg class="icon"><use href="#icon-{{ $item['icon'] }}"></use></svg>
                        {{ $item['title'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="min-w-0 flex-1">
            {{ $slot }}
        </div>
    </div>
</div>
