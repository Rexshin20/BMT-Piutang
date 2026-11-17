@extends('layouts.app')

@section('title', 'Piutang')

@section('content')
    @php
        $totalPiutang = $receivables->sum('sisa');
        $allNotas = $receivables->pluck('nomor_nota')->filter()->unique()->sort()->values();
        $notasByNama = $receivables
            ->groupBy(fn ($receivable) => $receivable->peminjam->nama)
            ->map(fn ($rows) => $rows->pluck('nomor_nota')->filter()->unique()->sort()->values());
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Piutang</h1>
            <p class="text-muted-foreground">Daftar piutang pelanggan yang belum dibayar.</p>
            @if (session('status'))
                <p class="mt-1 text-sm font-medium text-primary">{{ session('status') }}</p>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="rounded-lg border bg-card px-4 py-2 text-left shadow-sm sm:text-right">
                <span class="block text-xs text-muted-foreground">Total Piutang ({{ $receivables->count() }} transaksi)</span>
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
        <table id="piutang-table" class="data-table table-center w-full min-w-[64rem]">
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
                    <th class="w-32 text-center">Dibayar</th>
                    <th class="w-32 text-center">Sisa</th>
                    <th class="w-24 text-center" data-orderable="false" data-searchable="false">Aksi</th>
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
                        <td class="text-center tabular-nums text-muted-foreground" data-order="{{ $receivable->dibayar }}">
                            Rp {{ number_format($receivable->dibayar, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-semibold tabular-nums" data-order="{{ $receivable->sisa }}">
                            Rp {{ number_format($receivable->sisa, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-ghost btn-icon" aria-label="Edit {{ $receivable->barang }}"
                                data-edit-piutang
                                data-id="{{ $receivable->id }}"
                                data-peminjam-id="{{ $receivable->peminjam_id }}"
                                data-nota="{{ $receivable->nomor_nota }}"
                                data-tanggal="{{ $receivable->tanggal->format('Y-m-d') }}"
                                data-barang="{{ $receivable->barang }}"
                                data-unit="{{ $receivable->unit }}"
                                data-harga="{{ $receivable->harga }}">
                                <svg class="icon icon-sm"><use href="#icon-pencil"></use></svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Edit piutang --}}
    <div class="dialog" id="edit-piutang-dialog" role="dialog" aria-modal="true" aria-labelledby="edit-piutang-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-lg">
            <div class="dialog-header">
                <h2 id="edit-piutang-title" class="dialog-title">Edit Piutang</h2>
                <p class="dialog-description">Perbaiki data piutang yang salah, lalu simpan.</p>
            </div>
            <form method="POST" action="" id="edit-piutang-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="_id" value="{{ old('_id') }}">

                <div class="space-y-4">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="label" for="edit-nota">Nomor Nota</label>
                            <input id="edit-nota" name="nomor_nota" type="text" class="input"
                                value="{{ old('nomor_nota') }}" required>
                            @error('nomor_nota')
                                <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="label" for="edit-tanggal">Tanggal</label>
                            <input id="edit-tanggal" name="tanggal" type="date" class="input"
                                value="{{ old('tanggal') }}" required>
                            @error('tanggal')
                                <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="edit-peminjam">Peminjam</label>
                        <select id="edit-peminjam" name="peminjam_id" class="select" required>
                            @foreach ($borrowers as $borrower)
                                <option value="{{ $borrower->id }}" @selected(old('peminjam_id') == $borrower->id)>{{ $borrower->nama }}</option>
                            @endforeach
                        </select>
                        @error('peminjam_id')
                            <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="edit-barang">Barang</label>
                        <input id="edit-barang" name="barang" type="text" class="input"
                            value="{{ old('barang') }}" required>
                        @error('barang')
                            <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="space-y-2">
                            <label class="label" for="edit-unit">Unit</label>
                            <input id="edit-unit" name="unit" type="number" min="1" step="1" inputmode="numeric"
                                class="input tabular-nums" value="{{ old('unit') }}" required>
                            @error('unit')
                                <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="label" for="edit-harga">Harga</label>
                            <input id="edit-harga" name="harga" type="text" inputmode="numeric" autocomplete="off"
                                class="input tabular-nums"
                                value="{{ old('harga') ? number_format((int) preg_replace('/\D/', '', old('harga')), 0, ',', '.') : '' }}"
                                required>
                            @error('harga')
                                <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="label" for="edit-total">Total</label>
                            <input id="edit-total" type="text"
                                class="input tabular-nums bg-muted/50 text-muted-foreground" readonly tabindex="-1">
                        </div>
                    </div>
                </div>

                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.datatables', [
        'tableId' => 'piutang-table',
        'searchPlaceholder' => 'Cari nama, barang, atau tanggal...',
        'emptyMessage' => 'Belum ada data piutang',
        'order' => [[3, 'desc']],
        'exportTitle' => 'Data Piutang',
        'exportColumns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        'pdfWidths' => [24, 95, 90, 60, '*', 30, 66, 66, 66, 66],
        'pdfAlign' => ['center', 'center', 'center', 'center', 'left', 'center', 'center', 'center', 'center', 'center'],
        'pdfTotalColumn' => 9,
    ])

    @include('partials.searchable-select')

    @push('scripts')
        <script>
            jQuery(function ($) {
                var table = $('#piutang-table').DataTable();
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

                var editForm = document.getElementById('edit-piutang-form');
                var editPeminjam = document.getElementById('edit-peminjam');
                var editNota = document.getElementById('edit-nota');
                var editTanggal = document.getElementById('edit-tanggal');
                var editBarang = document.getElementById('edit-barang');
                var editUnit = document.getElementById('edit-unit');
                var editHarga = document.getElementById('edit-harga');
                var editTotal = document.getElementById('edit-total');
                var baseUrl = @json(url('/piutang'));
                var formatterRupiah = new Intl.NumberFormat('id-ID');

                function recalcEditTotal() {
                    var harga = parseInt((editHarga.value || '').replace(/\D/g, '') || '0', 10);
                    var unit = parseInt(editUnit.value, 10) || 0;
                    var total = harga * unit;
                    editTotal.value = total > 0 ? 'Rp ' + formatterRupiah.format(total) : '';
                }

                editHarga.addEventListener('input', function () {
                    var digits = editHarga.value.replace(/\D/g, '');
                    editHarga.value = digits ? formatterRupiah.format(digits) : '';
                    recalcEditTotal();
                });

                editUnit.addEventListener('input', recalcEditTotal);

                function clearEditErrors() {
                    editForm.querySelectorAll('[data-form-error]').forEach(function (el) { el.remove(); });
                }

                function showEditDialog(data, preserveErrors) {
                    if (!preserveErrors) clearEditErrors();
                    editForm.action = baseUrl + '/' + data.id;
                    editForm.elements._id.value = data.id;
                    editPeminjam.value = data.peminjamId;
                    editNota.value = data.nota || '';
                    editTanggal.value = data.tanggal || '';
                    editBarang.value = data.barang || '';
                    editUnit.value = data.unit || 1;
                    editHarga.value = data.harga ? formatterRupiah.format(data.harga) : '';
                    recalcEditTotal();
                    window.App.openDialog('edit-piutang-dialog');
                }

                document.addEventListener('click', function (event) {
                    var button = event.target.closest('[data-edit-piutang]');
                    if (!button) return;
                    event.preventDefault();
                    showEditDialog(button.dataset);
                });

                @if ($errors->any() && old('_id'))
                    showEditDialog({
                        id: @json(old('_id')),
                        peminjamId: @json(old('peminjam_id')),
                        nota: @json(old('nomor_nota')),
                        tanggal: @json(old('tanggal')),
                        barang: @json(old('barang')),
                        unit: @json(old('unit')),
                        harga: @json(preg_replace('/\D/', '', (string) old('harga'))),
                    }, true);
                @endif
            });
        </script>
    @endpush
@endsection
