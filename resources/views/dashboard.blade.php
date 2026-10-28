@extends('layouts.app')

@section('title', 'Dashboard')

@section('topnav')
    <a href="{{ route('dashboard') }}" class="rounded-md bg-muted px-3 py-1.5 text-sm font-medium text-foreground">Overview</a>
    <a href="{{ route('piutang.index') }}" class="px-3 py-1.5 text-sm font-medium text-muted-foreground">Piutang</a>
    <a href="{{ route('master.borrowers') }}" class="px-3 py-1.5 text-sm font-medium text-muted-foreground">Data Peminjam</a>
    <a href="{{ route('settings.account') }}" class="px-3 py-1.5 text-sm font-medium text-muted-foreground">Settings</a>
@endsection

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('master.borrowers') }}" class="btn btn-outline">Data Peminjam</a>
            <a href="{{ route('piutang.index') }}" class="btn btn-primary">Lihat Piutang</a>
        </div>
    </div>

    <div data-tabs class="space-y-4">
        <div class="w-full overflow-x-auto pb-2">
            <div class="tab-list">
                <button type="button" class="tab-trigger active" data-tab="overview" aria-selected="true">Overview</button>
                <button type="button" class="tab-trigger" data-tab="analytics" aria-selected="false">Analytics</button>
                <button type="button" class="tab-trigger" disabled>Reports</button>
                <button type="button" class="tab-trigger" disabled>Notifications</button>
            </div>
        </div>

        {{-- Overview tab --}}
        <div class="tab-panel active space-y-4" data-tab-panel="overview">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($metrics as $metric)
                    <div class="card">
                        <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="card-title text-sm font-medium">{{ $metric['title'] }}</h3>
                            <svg class="icon text-muted-foreground"><use href="#icon-{{ $metric['icon'] }}"></use></svg>
                        </div>
                        <div class="card-content">
                            <div class="text-2xl font-bold tabular-nums">{{ $metric['value'] }}</div>
                            <p class="text-xs text-muted-foreground">{{ $metric['change'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
                <div class="card col-span-1 lg:col-span-4">
                    <div class="card-header">
                        <h3 class="card-title">Piutang per Bulan</h3>
                        <p class="card-description">Total piutang tiap bulan</p>
                    </div>
                    <div class="card-content ps-2">
                        @php
                            $maxOverview = $overview ? max(array_column($overview, 'total')) : 0;
                            $chartCeiling = max(500000, (int) (ceil($maxOverview / 500000) * 500000));
                            $ticks = [0.25, 0.5, 0.75];
                            $formatRupiah = function (float $value): string {
                                if ($value >= 1000000) {
                                    return 'Rp ' . rtrim(rtrim(number_format($value / 1000000, 1, ',', '.'), '0'), ',') . ' jt';
                                }
                                if ($value >= 1000) {
                                    return 'Rp ' . number_format($value / 1000, 0, ',', '.') . ' rb';
                                }
                                return 'Rp ' . number_format($value, 0, ',', '.');
                            };
                        @endphp
                        <div class="relative h-[350px] pl-24">
                            <div class="pointer-events-none absolute inset-x-24 inset-y-0">
                                @foreach ($ticks as $ratio)
                                    <div class="absolute inset-x-0 border-t border-dashed border-border/60" style="bottom: {{ $ratio * 290 }}px">
                                        <span class="absolute -left-24 -top-2 w-20 pr-2 text-right text-xs text-muted-foreground">{{ $formatRupiah($chartCeiling * $ratio) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex h-[310px] items-end gap-2 pt-4">
                                @foreach ($overview as $month)
                                    <div class="group flex h-full flex-1 flex-col items-center justify-end gap-2">
                                        <div class="w-full max-w-10 rounded-t bg-primary transition-opacity group-hover:opacity-80"
                                            style="height: {{ $chartCeiling > 0 ? round(($month['total'] / $chartCeiling) * 260) : 0 }}px"
                                            title="{{ $month['name'] }}: Rp {{ number_format($month['total'], 0, ',', '.') }}"></div>
                                        <span class="text-xs text-muted-foreground">{{ $month['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card col-span-1 lg:col-span-3">
                    <div class="card-header">
                        <h3 class="card-title">Piutang Terbaru</h3>
                        <p class="card-description">{{ $transactionsThisMonth }} transaksi tercatat bulan ini.</p>
                    </div>
                    <div class="card-content">
                        <div class="space-y-8">
                            @foreach ($recentPiutang as $item)
                                <div class="flex items-center gap-4">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-muted text-xs font-semibold">{{ $item['initials'] }}</span>
                                    <div class="flex flex-1 flex-wrap items-center justify-between gap-2">
                                        <div class="space-y-1">
                                            <p class="text-sm font-medium leading-none">{{ $item['name'] }}</p>
                                            <p class="text-sm text-muted-foreground">{{ $item['meta'] }}</p>
                                        </div>
                                        <div class="font-medium tabular-nums">{{ $item['amount'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('piutang.index') }}" class="mt-6 inline-flex text-sm font-medium text-primary hover:underline">
                            Lihat semua piutang
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Analytics tab --}}
        <div class="tab-panel space-y-4" data-tab-panel="analytics">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tren Piutang 7 Hari</h3>
                    <p class="card-description">Total piutang harian dari transaksi terbaru</p>
                </div>
                <div class="card-content px-6">
                    @php
                        $dailyMax = max(array_column($daily, 'total')) ?: 1;
                        $count = count($daily);
                        $points = [];
                        foreach ($daily as $i => $point) {
                            $x = 40 + ($count > 1 ? ($i * (620 / ($count - 1))) : 310);
                            $y = 270 - ($point['total'] / $dailyMax) * 220;
                            $points[] = round($x) . ',' . round($y);
                        }
                        $area = 'M ' . str_replace(',', ' ', $points[0]) . ' L ' . implode(' L ', array_map(fn ($p) => str_replace(',', ' ', $p), $points)) . ' L 660 270 L 40 270 Z';
                    @endphp
                    <svg viewBox="0 0 700 300" class="h-[300px] w-full" preserveAspectRatio="none" role="img" aria-label="Total piutang harian 7 hari terakhir">
                        @foreach ([0.25, 0.5, 0.75, 1] as $ratio)
                            <line x1="40" x2="660" y1="{{ 270 - $ratio * 220 }}" y2="{{ 270 - $ratio * 220 }}" stroke="currentColor" class="text-border" stroke-dasharray="4 4" />
                        @endforeach
                        <path d="{{ $area }}" fill="currentColor" class="text-primary/15" />
                        <polyline points="{{ implode(' ', $points) }}" fill="none" stroke="currentColor" class="text-primary" stroke-width="2" vector-effect="non-scaling-stroke" />
                        @foreach ($daily as $i => $point)
                            @php
                                $cx = 40 + ($count > 1 ? ($i * (620 / ($count - 1))) : 310);
                                $cy = 270 - ($point['total'] / $dailyMax) * 220;
                            @endphp
                            <circle cx="{{ round($cx) }}" cy="{{ round($cy) }}" r="3" fill="currentColor" class="text-primary" />
                            <text x="{{ round($cx) }}" y="292" text-anchor="middle" font-size="12" fill="currentColor" class="text-muted-foreground">{{ $point['name'] }}</text>
                        @endforeach
                    </svg>
                    <div class="mt-2 flex items-center justify-center gap-6 text-xs text-muted-foreground">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-primary"></span> Total piutang per hari</span>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($analyticsMetrics as $metric)
                    <div class="card">
                        <div class="card-header flex flex-row items-center justify-between space-y-0 pb-2">
                            <h3 class="card-title text-sm font-medium">{{ $metric['title'] }}</h3>
                            <svg class="icon text-muted-foreground"><use href="#icon-{{ $metric['icon'] }}"></use></svg>
                        </div>
                        <div class="card-content">
                            <div class="text-2xl font-bold tabular-nums">{{ $metric['value'] }}</div>
                            <p class="text-xs text-muted-foreground">{{ $metric['change'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
                <div class="card col-span-1 lg:col-span-4">
                    <div class="card-header">
                        <h3 class="card-title">Peminjam Teratas</h3>
                        <p class="card-description">Total piutang per peminjam</p>
                    </div>
                    <div class="card-content">
                        @php $borrowerMax = $topBorrowers ? max(array_column($topBorrowers, 'value')) : 1; @endphp
                        <ul class="space-y-3">
                            @foreach ($topBorrowers as $item)
                                <li class="flex items-center justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="mb-1 truncate text-xs text-muted-foreground">{{ $item['name'] }}</div>
                                        <div class="h-2.5 w-full rounded-full bg-muted">
                                            <div class="h-2.5 rounded-full bg-primary" style="width: {{ $borrowerMax > 0 ? round(($item['value'] / $borrowerMax) * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="ps-2 text-xs font-medium tabular-nums">Rp {{ number_format($item['value'], 0, ',', '.') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card col-span-1 lg:col-span-3">
                    <div class="card-header">
                        <h3 class="card-title">Barang Teratas</h3>
                        <p class="card-description">Porsi piutang per barang</p>
                    </div>
                    <div class="card-content">
                        @php $itemMax = $topItems ? max(array_column($topItems, 'percent')) : 1; @endphp
                        <ul class="space-y-3">
                            @foreach ($topItems as $item)
                                <li class="flex items-center justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="mb-1 truncate text-xs text-muted-foreground">{{ $item['name'] }}</div>
                                        <div class="h-2.5 w-full rounded-full bg-muted">
                                            <div class="h-2.5 rounded-full bg-muted-foreground" style="width: {{ $itemMax > 0 ? round(($item['percent'] / $itemMax) * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="ps-2 text-xs font-medium tabular-nums">{{ $item['percent'] }}%</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
