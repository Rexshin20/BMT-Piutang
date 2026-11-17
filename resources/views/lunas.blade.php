@extends('layouts.app')

@section('title', 'Lunas')

@section('content')
    @php
        $totalPiutang = $receivables->sum('total');
        $allNotas = $receivables->pluck('nomor_nota')->filter()->unique()->sort()->values();
        $notasByNama = $receivables
            ->groupBy(fn ($receivable) => $receivable->peminjam->nama)
            ->map(fn ($rows) => $rows->pluck('nomor_nota')->filter()->unique()->sort()->values());
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Lunas</h1>
            <p class="text-muted-foreground">Daftar piutang yang sudah dibayar lunas.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="rounded-lg border bg-card px-4 py-2 text-left shadow-sm sm:text-right">
                <span class="block text-xs text-muted-foreground">Total Lunas ({{ $receivables->count() }} transaksi)</span>
                <span class="text-lg font-semibold tabular-nums">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2">
        <div class="flex items-center gap-2">
            <label class="text-sm text-muted-foreground" for="filter-nama">Nama</label>
            <select id="filter-nama" class="select select-search w-56">
                <option value="">Semua nama</option>
                @foreach ($receivables->pluck('peminjam.nama')->unique()->sort()->values() as $nama)
                    <option value="{{ $nama }}">{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-muted-foreground" for="filter-nota">Nota</label>
            <select id="filter-nota" class="select select-search w-56">
                <option value="">Semua nota</option>
                @foreach ($allNotas as $nota)
                    <option value="{{ $nota }}">{{ $nota }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-wrapper mt-3">
        <table id="lunas-table" class="data-table table-center w-full min-w-[62rem]">
            <thead>
                <tr>
                    <th class="w-14 text-center">No</th>
                    <th class="w-40 text-center">Nama</th>
                    <th class="w-40 text-center">Nota</th>
                    <th class="w-28 text-center">Tanggal</th>
                    <th class="text-start">Barang</th>
                    <th class="w-20 text-center">Unit</th>
                    <th class="w-32 text-center">Harga</th>
                    <th class="w-32 text-center">Total</th>
                    <th class="w-32 text-center">Tanggal Lunas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receivables as $receivable)
                    <tr>
                        <td class="text-center tabular-nums text-muted-foreground">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap text-center font-medium">{{ $receivable->peminjam->nama }}</td>
                        <td class="whitespace-nowrap text-center text-muted-foreground">{{ $receivable->nomor_nota ?? '-' }}</td>
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
                        <td class="whitespace-nowrap text-center text-muted-foreground" data-order="{{ $receivable->lunas_at->format('Y-m-d H:i') }}">
                            {{ $receivable->lunas_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('partials.datatables', [
        'tableId' => 'lunas-table',
        'searchPlaceholder' => 'Cari nama, barang, atau tanggal...',
        'emptyMessage' => 'Belum ada piutang lunas',
        'order' => [[8, 'desc']],
        'exportTitle' => 'Data Piutang Lunas',
        'pdfWidths' => [26, 100, 95, 66, '*', 34, 72, 72, 66],
        'pdfAlign' => ['center', 'center', 'center', 'center', 'left', 'center', 'center', 'center', 'center'],
        'pdfTotalColumn' => 7,
        'pdfColumnOrder' => [0, 1, 2, 3, 4, 5, 6, 8, 7],
    ])

    @include('partials.searchable-select')

    @push('scripts')
        <script>
            jQuery(function ($) {
                var table = $('#lunas-table').DataTable();
                var notaSelect = $('#filter-nota');
                var allNotas = @json($allNotas);
                var notasByNama = @json($notasByNama);
                var namaSelect = document.getElementById('filter-nama');

                function fillNotaOptions(notas) {
                    notaSelect.empty().append(new Option('Semua nota', ''));
                    notas.forEach(function (nota) {
                        notaSelect.append(new Option(nota, nota));
                    });
                    notaSelect.trigger('change.select2');
                }

                function searchExact(column, value, redraw) {
                    table.column(column).search(
                        value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '',
                        true,
                        false
                    );
                    if (redraw !== false) table.draw();
                }

                $('#filter-nama').on('change', function () {
                    var nama = this.value;
                    fillNotaOptions(nama ? (notasByNama[nama] || []) : allNotas);
                    searchExact(1, nama, false);
                    searchExact(2, '', true);
                });

                $('#filter-nota').on('change', function () {
                    searchExact(2, this.value);
                });

                fillNotaOptions(namaSelect.value ? (notasByNama[namaSelect.value] || []) : allNotas);
            });
        </script>
    @endpush
@endsection
