@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    @php
        $totalSisa = $notas->sum('sisa');
        $allNotas = $notas->pluck('nomor_nota')->filter()->unique()->sort()->values();
        $notasByNama = $notas
            ->groupBy('peminjam')
            ->map(fn ($rows) => $rows->pluck('nomor_nota')->filter()->unique()->sort()->values());
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Pembayaran</h1>
            <p class="text-muted-foreground">Catat cicilan pembayaran piutang per nota.</p>
            <p id="pembayaran-status" class="mt-1 text-sm font-medium text-primary {{ session('status') ? '' : 'hidden' }}">{{ session('status') }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="rounded-lg border bg-card px-4 py-2 text-left shadow-sm sm:text-right">
                <span id="pembayaran-sisa-label" class="block text-xs text-muted-foreground">Sisa Belum Lunas ({{ $notas->count() }} nota)</span>
                <span id="pembayaran-sisa-total" class="text-lg font-semibold tabular-nums" data-sisa="{{ $totalSisa }}"
                    data-nota-count="{{ $notas->count() }}">Rp {{ number_format($totalSisa, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2">
        <div class="flex items-center gap-2">
            <label class="text-sm text-muted-foreground" for="filter-nama">Nama</label>
            <select id="filter-nama" class="select select-search w-56">
                <option value="">Semua nama</option>
                @foreach ($notas->pluck('peminjam')->unique()->sort()->values() as $nama)
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
        <table id="pembayaran-table" class="data-table table-center w-full min-w-[56rem]">
            <thead>
                <tr>
                    <th class="w-14 text-center">No</th>
                    <th class="w-40 text-center">Nama</th>
                    <th class="w-40 text-center">Nota</th>
                    <th class="text-start">Barang</th>
                    <th class="w-32 text-center">Total</th>
                    <th class="w-32 text-center">Dibayar</th>
                    <th class="w-32 text-center">Sisa</th>
                    <th class="w-24 text-center" data-orderable="false" data-searchable="false">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notas as $nota)
                    <tr data-id="{{ $nota->key }}">
                        <td class="text-center tabular-nums text-muted-foreground">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap text-center font-medium">{{ $nota->peminjam }}</td>
                        <td class="whitespace-nowrap text-center text-muted-foreground">{{ $nota->nomor_nota ?? '(tanpa nota)' }}</td>
                        <td class="text-start">
                            <ul class="space-y-0.5">
                                @foreach ($nota->items as $item)
                                    <li>{{ $item->barang }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center font-semibold tabular-nums" data-order="{{ $nota->total }}">
                            Rp {{ number_format($nota->total, 0, ',', '.') }}
                        </td>
                        <td class="text-center tabular-nums text-muted-foreground" data-order="{{ $nota->dibayar }}">
                            Rp {{ number_format($nota->dibayar, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-semibold tabular-nums" data-order="{{ $nota->sisa }}">
                            Rp {{ number_format($nota->sisa, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline btn-sm" data-bayar
                                data-nota="{{ $nota->key }}"
                                data-label="{{ $nota->nomor_nota ?? '(tanpa nota)' }}"
                                data-peminjam="{{ $nota->peminjam }}"
                                data-sisa="{{ $nota->sisa }}">Bayar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 rounded-lg border bg-card shadow-sm">
        <div class="border-b px-4 py-3">
            <h2 class="font-semibold">Riwayat Pembayaran</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table table-center w-full min-w-[40rem]">
                <thead>
                    <tr>
                        <th class="w-32 text-center">Tanggal</th>
                        <th class="w-44 text-center">Nota</th>
                        <th class="w-32 text-center">Jumlah</th>
                        <th class="text-start">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="riwayat-body">
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="whitespace-nowrap text-center text-muted-foreground">{{ $payment->tanggal->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap text-center text-muted-foreground">{{ str_starts_with($payment->nomor_nota, '#') ? '(tanpa nota)' : $payment->nomor_nota }}</td>
                            <td class="text-center font-semibold tabular-nums">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                            <td class="text-start">{{ $payment->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-muted-foreground">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Catat pembayaran --}}
    <div class="dialog" id="bayar-dialog" role="dialog" aria-modal="true" aria-labelledby="bayar-dialog-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <div class="dialog-header">
                <h2 id="bayar-dialog-title" class="dialog-title">Catat Pembayaran</h2>
                <p id="bayar-dialog-description" class="dialog-description">Isi jumlah cicilan yang dibayarkan.</p>
                <p id="bayar-error" class="mt-1 hidden text-xs font-medium text-destructive"></p>
                @error('nomor_nota')
                    <p class="mt-1 text-xs font-medium text-destructive">{{ $message }}</p>
                @enderror
            </div>
            <form method="POST" action="{{ route('pembayaran.store') }}" id="bayar-form">
                @csrf
                <input type="hidden" name="nomor_nota" id="bayar-nota" value="{{ old('nomor_nota') }}">

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="label" for="bayar-jumlah">Jumlah Bayar</label>
                        <input id="bayar-jumlah" name="jumlah" type="text" inputmode="numeric" autocomplete="off"
                            class="input tabular-nums"
                            value="{{ old('jumlah') ? number_format((int) preg_replace('/\D/', '', old('jumlah')), 0, ',', '.') : '' }}"
                            placeholder="0" required>
                        @error('jumlah')
                            <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="bayar-tanggal">Tanggal</label>
                        <input id="bayar-tanggal" name="tanggal" type="date" class="input"
                            value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="bayar-keterangan">Keterangan</label>
                        <input id="bayar-keterangan" name="keterangan" type="text" class="input"
                            value="{{ old('keterangan') }}" placeholder="Opsional">
                        @error('keterangan')
                            <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.datatables', [
        'tableId' => 'pembayaran-table',
        'searchPlaceholder' => 'Cari nama, nota, atau barang...',
        'emptyMessage' => 'Tidak ada nota yang belum lunas',
        'order' => [[2, 'asc']],
        'exportTitle' => 'Data Pembayaran Piutang',
        'exportColumns' => [0, 1, 2, 3, 4, 5, 6],
        'pdfWidths' => [24, 90, 90, '*', 62, 62, 62],
        'pdfAlign' => ['center', 'center', 'center', 'left', 'center', 'center', 'center'],
        'pdfTotalColumn' => 6,
    ])

    @include('partials.searchable-select')

    @push('scripts')
        <script>
            jQuery(function ($) {
                const table = $('#pembayaran-table').DataTable();
                const notaSelect = $('#filter-nota');
                const allNotas = @json($allNotas);
                const notasByNama = @json($notasByNama);
                const namaSelect = document.getElementById('filter-nama');

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
                    const nama = this.value;
                    fillNotaOptions(nama ? (notasByNama[nama] || []) : allNotas);
                    searchExact(1, nama, false);
                    searchExact(2, '', true);
                });

                $('#filter-nota').on('change', function () {
                    searchExact(2, this.value);
                });

                fillNotaOptions(namaSelect.value ? (notasByNama[namaSelect.value] || []) : allNotas);

                const form = document.getElementById('bayar-form');
                const notaInput = document.getElementById('bayar-nota');
                const description = document.getElementById('bayar-dialog-description');
                const jumlah = document.getElementById('bayar-jumlah');
                const formatter = new Intl.NumberFormat('id-ID');
                const statusEl = document.getElementById('pembayaran-status');
                const errorEl = document.getElementById('bayar-error');
                const sisaLabel = document.getElementById('pembayaran-sisa-label');
                const sisaTotal = document.getElementById('pembayaran-sisa-total');
                const submitBtn = form.querySelector('button[type="submit"]');

                function formatRupiah(value) {
                    return 'Rp ' + formatter.format(Number(value));
                }

                function addHistoryRow(data) {
                    const tbody = document.getElementById('riwayat-body');
                    if (!tbody) return;

                    const empty = tbody.querySelector('td[colspan]');
                    if (empty) empty.closest('tr').remove();

                    const tr = document.createElement('tr');
                    [
                        ['whitespace-nowrap text-center text-muted-foreground', data.tanggal],
                        ['whitespace-nowrap text-center text-muted-foreground', data.nota],
                        ['text-center font-semibold tabular-nums', formatRupiah(data.jumlah)],
                        ['text-start', data.keterangan || '-'],
                    ].forEach(function (cell) {
                        const td = document.createElement('td');
                        td.className = cell[0];
                        td.textContent = cell[1];
                        tr.appendChild(td);
                    });

                    tbody.insertBefore(tr, tbody.firstChild);
                }

                function applyPayment(data) {
                    const row = table.row($('tr[data-id="' + data.nota_key + '"]'));

                    if (row.any()) {
                        if (data.lunas) {
                            row.remove();
                        } else {
                            const paid = data.items.reduce(function (sum, item) { return sum + item.dibayar; }, 0);
                            const cells = $(row.node()).children('td');
                            cells.eq(5).text(formatRupiah(paid)).attr('data-order', paid);
                            cells.eq(6).text(formatRupiah(data.nota_sisa)).attr('data-order', data.nota_sisa);
                            row.invalidate();
                        }
                    }

                    table.draw(false);

                    const sisa = Number(sisaTotal.dataset.sisa) - data.jumlah;
                    const notaCount = Number(sisaTotal.dataset.notaCount) - (data.lunas ? 1 : 0);
                    sisaTotal.dataset.sisa = sisa;
                    sisaTotal.dataset.notaCount = notaCount;
                    sisaTotal.textContent = formatRupiah(sisa);
                    sisaLabel.textContent = 'Sisa Belum Lunas (' + notaCount + ' nota)';

                    $('[data-bayar]').each(function () {
                        if (this.dataset.nota === data.nota_key) this.dataset.sisa = data.nota_sisa;
                    });

                    addHistoryRow(data);

                    statusEl.textContent = data.message;
                    statusEl.classList.remove('hidden');

                    if (window.App && window.App.showToast) {
                        window.App.showToast(data.message);
                    }
                }

                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    errorEl.classList.add('hidden');
                    submitBtn.disabled = true;

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: new FormData(form),
                        credentials: 'same-origin',
                    })
                        .then(function (response) {
                            return response.json().then(function (data) {
                                return { ok: response.ok, data: data };
                            });
                        })
                        .then(function (result) {
                            submitBtn.disabled = false;

                            if (!result.ok) {
                                const errors = result.data.errors || {};
                                const firstKey = Object.keys(errors)[0];
                                errorEl.textContent = firstKey
                                    ? errors[firstKey][0]
                                    : (result.data.message || 'Pembayaran gagal disimpan.');
                                errorEl.classList.remove('hidden');
                                return;
                            }

                            applyPayment(result.data);
                            window.App.closeDialog('bayar-dialog');
                        })
                        .catch(function () {
                            submitBtn.disabled = false;
                            errorEl.textContent = 'Pembayaran gagal disimpan. Coba lagi.';
                            errorEl.classList.remove('hidden');
                        });
                });

                jumlah.addEventListener('input', function () {
                    const digits = jumlah.value.replace(/\D/g, '');
                    jumlah.value = digits ? formatter.format(digits) : '';
                });

                document.addEventListener('click', function (event) {
                    const button = event.target.closest('[data-bayar]');
                    if (!button) return;

                    event.preventDefault();
                    form.reset();
                    errorEl.classList.add('hidden');
                    errorEl.textContent = '';
                    notaInput.value = button.dataset.nota;
                    description.textContent = button.dataset.peminjam + ' • Nota ' + button.dataset.label
                        + ' • Sisa Rp ' + formatter.format(button.dataset.sisa);
                    window.App.openDialog('bayar-dialog');
                    jumlah.focus();
                });

                @if ($errors->any() && old('nomor_nota'))
                    window.App.openDialog('bayar-dialog');
                @endif
            });
        </script>
    @endpush
@endsection
