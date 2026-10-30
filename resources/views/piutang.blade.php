@extends('layouts.app')

@section('title', 'Piutang')

@section('content')
    @php
        $totalPiutang = $receivables->sum('total');
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Piutang</h1>
            <p class="text-muted-foreground">Daftar piutang pelanggan yang belum dibayar.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="rounded-lg border bg-card px-4 py-2 text-left shadow-sm sm:text-right">
                <span class="block text-xs text-muted-foreground">Total Piutang ({{ $receivables->count() }} transaksi)</span>
                <span class="text-lg font-semibold tabular-nums">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="table-wrapper mt-4">
        <table id="piutang-table" class="data-table table-center w-full min-w-[56rem]">
            <thead>
                <tr>
                    <th class="w-14 text-center">No</th>
                    <th class="w-44 text-center">Nama</th>
                    <th class="w-28 text-center">Tanggal</th>
                    <th class="text-start">Barang</th>
                    <th class="w-20 text-center">Unit</th>
                    <th class="w-32 text-center">Harga</th>
                    <th class="w-32 text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receivables as $receivable)
                    <tr>
                        <td class="text-center tabular-nums text-muted-foreground">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap text-center font-medium">{{ $receivable->peminjam->nama }}</td>
                        <td class="whitespace-nowrap text-center text-muted-foreground" data-order="{{ $receivable->tanggal->format('Y-m-d') }}">
                            {{ $receivable->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="text-start">{{ $receivable->barang }}</td>
                        <td class="text-center tabular-nums" data-order="{{ $receivable->unit }}">{{ $receivable->unit }}</td>
                        <td class="text-center tabular-nums" data-order="{{ $receivable->harga }}">
                            Rp {{ number_format($receivable->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-semibold tabular-nums" data-order="{{ $receivable->total }}">
                            Rp {{ number_format($receivable->total, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('partials.datatables', [
        'tableId' => 'piutang-table',
        'searchPlaceholder' => 'Cari nama, barang, atau tanggal...',
        'emptyMessage' => 'Belum ada data piutang',
        'order' => [[2, 'desc']],
        'exportTitle' => 'Data Piutang',
        'pdfWidths' => [28, 120, 70, '*', 40, 85, 85],
        'pdfAlign' => ['center', 'center', 'center', 'left', 'center', 'center', 'center'],
    ])
@endsection
