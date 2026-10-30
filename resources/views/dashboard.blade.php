@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <h1 class="text-2xl font-bold tracking-tight">Dashboard</h1>
        <p class="text-muted-foreground">Ringkasan piutang dan data peminjam BMT.</p>
    </div>

    <div class="space-y-4">
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
                    <div class="divide-y">
                        @foreach ($recentPiutang as $item)
                            <div class="flex items-start justify-between gap-4 py-3 first:pt-0 last:pb-0">
                                <div class="min-w-0 space-y-1">
                                    <p class="truncate text-sm font-medium leading-none">{{ $item['name'] }}</p>
                                    <p class="truncate text-sm text-muted-foreground">{{ $item['meta'] }}</p>
                                </div>
                                <div class="shrink-0 font-medium tabular-nums">{{ $item['amount'] }}</div>
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
@endsection
