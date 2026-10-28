@extends('layouts.app')

@section('title', 'Piutang')

@section('content')
    @php
        $totalPiutang = array_sum(array_column($receivables, 'total'));
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Piutang</h1>
            <p class="text-muted-foreground">Daftar piutang pelanggan yang belum dibayar.</p>
        </div>
        <div class="rounded-lg border bg-card px-4 py-2 text-left shadow-sm sm:text-right">
            <span class="block text-xs text-muted-foreground">Total Piutang ({{ count($receivables) }} transaksi)</span>
            <span class="text-lg font-semibold tabular-nums">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="table-wrapper mt-4">
        <table id="piutang-table" class="data-table w-full min-w-[52rem]">
            <thead>
                <tr>
                    <th class="w-14">No</th>
                    <th class="w-48">Nama</th>
                    <th class="w-28">Tanggal</th>
                    <th>Barang</th>
                    <th class="w-32 text-right">Harga</th>
                    <th class="w-32 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receivables as $receivable)
                    <tr>
                        <td class="tabular-nums text-muted-foreground">{{ $loop->iteration }}</td>
                        <td class="font-medium">{{ $receivable['nama'] }}</td>
                        <td class="whitespace-nowrap text-muted-foreground" data-order="{{ $receivable['tanggal'] }}">
                            {{ \Illuminate\Support\Carbon::parse($receivable['tanggal'])->format('d/m/Y') }}
                        </td>
                        <td>{{ $receivable['barang'] }}</td>
                        <td class="text-right tabular-nums" data-order="{{ $receivable['harga'] }}">
                            Rp {{ number_format($receivable['harga'], 0, ',', '.') }}
                        </td>
                        <td class="text-right font-semibold tabular-nums" data-order="{{ $receivable['total'] }}">
                            Rp {{ number_format($receivable['total'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('partials.datatables', [
        'tableId' => 'piutang-table',
        'searchPlaceholder' => 'Cari nama atau barang...',
        'emptyMessage' => 'Belum ada data piutang',
    ])
@endsection
