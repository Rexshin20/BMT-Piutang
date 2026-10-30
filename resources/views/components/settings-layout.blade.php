@php
    $items = [
        ['route' => 'settings.account', 'title' => 'Akun', 'icon' => 'user-cog'],
        ['route' => 'settings.appearance', 'title' => 'Tampilan', 'icon' => 'palette'],
    ];
@endphp

<div class="flex flex-col gap-6">
    <div class="space-y-1">
        <h1 class="text-2xl font-bold tracking-tight md:text-3xl">Pengaturan</h1>
        <p class="text-sm text-muted-foreground sm:text-base">Kelola akun dan preferensi tampilan aplikasi.</p>
    </div>

    <nav class="flex w-fit max-w-full gap-1 overflow-x-auto rounded-lg border bg-muted/60 p-1" aria-label="Pengaturan">
        @foreach ($items as $item)
            <a href="{{ route($item['route']) }}"
                @if (request()->routeIs($item['route'])) aria-current="page" @endif
                class="flex h-8 shrink-0 items-center gap-2 rounded-md px-3 text-sm font-medium transition-colors {{ request()->routeIs($item['route']) ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                <svg class="icon icon-sm"><use href="#icon-{{ $item['icon'] }}"></use></svg>
                {{ $item['title'] }}
            </a>
        @endforeach
    </nav>

    <div class="min-w-0">
        {{ $slot }}
    </div>
</div>
