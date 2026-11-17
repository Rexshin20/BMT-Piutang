@extends('layouts.app')

@section('title', 'Data Piutang')

@section('content')
    <div>
        <h1 class="text-2xl font-bold tracking-tight">Data Piutang</h1>
        <p class="text-muted-foreground">Catat piutang baru. Satu nota bisa berisi beberapa barang.</p>
    </div>

    @php
        $oldItems = old('items');
        if (!is_array($oldItems) || count($oldItems) === 0) {
            $oldItems = [['barang' => '', 'unit' => '1', 'harga' => '']];
        }
    @endphp

    <div class="mt-4 max-w-4xl rounded-lg border bg-card p-5 shadow-sm">
        <form method="POST" action="{{ route('master.piutang.store') }}" class="space-y-4">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <label class="label" for="piutang-nota">Nomor Nota</label>
                    <input id="piutang-nota" name="nomor_nota" type="text" class="input"
                        value="{{ old('nomor_nota') }}" placeholder="Contoh: 001/NS/XI/2026" required>
                    @error('nomor_nota')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="label" for="piutang-tanggal">Tanggal</label>
                    <input id="piutang-tanggal" name="tanggal" type="date" class="input"
                        value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                    @error('tanggal')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="label" for="piutang-peminjam">Peminjam</label>
                <select id="piutang-peminjam" name="peminjam_id" class="select" required>
                    <option value="" disabled {{ old('peminjam_id') ? '' : 'selected' }}>Pilih peminjam</option>
                    @foreach ($borrowers as $borrower)
                        <option value="{{ $borrower->id }}" @selected(old('peminjam_id') == $borrower->id)>{{ $borrower->nama }}</option>
                    @endforeach
                </select>
                @error('peminjam_id')
                    <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-3">
                <div class="space-y-2">
                    <label class="label" for="piutang-jumlah">Jumlah Barang</label>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <input id="piutang-jumlah" type="number" min="1" max="100" step="1" inputmode="numeric"
                            class="input w-24 tabular-nums" value="{{ count($oldItems) }}">
                        <span class="text-xs text-muted-foreground">Ketik jumlah barang (misal 10) — kotak isian menyesuaikan otomatis.</span>
                    </div>
                </div>
                @error('items')
                    <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                @enderror
                <div id="piutang-items" class="space-y-2">
                    @foreach ($oldItems as $i => $item)
                        <div class="piutang-item-row space-y-1">
                            <div class="flex flex-wrap items-center gap-2 rounded-md border px-3 py-2">
                                <span data-row-no class="w-6 shrink-0 text-center text-xs font-semibold tabular-nums text-muted-foreground">{{ $i + 1 }}</span>
                                <input type="text" name="items[{{ $i }}][barang]" class="input min-w-40 flex-1"
                                    placeholder="Nama barang" value="{{ $item['barang'] ?? '' }}" required>
                                <input type="number" name="items[{{ $i }}][unit]" min="1" step="1" inputmode="numeric" data-item-unit
                                    class="input w-20 tabular-nums" placeholder="Unit" value="{{ $item['unit'] ?? 1 }}" required>
                                <input type="text" inputmode="numeric" autocomplete="off" name="items[{{ $i }}][harga]"
                                    data-rupiah data-item-harga class="input w-32 tabular-nums" placeholder="Harga"
                                    value="{{ isset($item['harga']) && $item['harga'] !== '' ? number_format((int) preg_replace('/\D/', '', $item['harga']), 0, ',', '.') : '' }}"
                                    required>
                                <input type="text" data-item-total
                                    class="input w-32 tabular-nums bg-muted/50 text-muted-foreground" placeholder="Total"
                                    readonly tabindex="-1">
                            </div>
                            @php $rowErrors = $errors->get('items.' . $i . '.barang') + $errors->get('items.' . $i . '.unit') + $errors->get('items.' . $i . '.harga'); @endphp
                            @if ($rowErrors)
                                <p class="px-1 text-xs font-medium text-destructive">{{ implode(' ', $rowErrors) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap items-end justify-between gap-4 border-t pt-4">
                <div class="space-y-1">
                    <p class="text-sm text-muted-foreground">Total Keseluruhan</p>
                    <p id="piutang-grand-total" class="text-2xl font-bold tabular-nums">Rp 0</p>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    <template id="piutang-row-template">
        <div class="piutang-item-row space-y-1">
            <div class="flex flex-wrap items-center gap-2 rounded-md border px-3 py-2">
                <span data-row-no class="w-6 shrink-0 text-center text-xs font-semibold tabular-nums text-muted-foreground">1</span>
                <input type="text" name="items[__INDEX__][barang]" class="input min-w-40 flex-1" placeholder="Nama barang" required>
                <input type="number" name="items[__INDEX__][unit]" min="1" step="1" inputmode="numeric" data-item-unit
                    class="input w-20 tabular-nums" placeholder="Unit" value="1" required>
                <input type="text" inputmode="numeric" autocomplete="off" name="items[__INDEX__][harga]"
                    data-rupiah data-item-harga class="input w-32 tabular-nums" placeholder="Harga" required>
                <input type="text" data-item-total
                    class="input w-32 tabular-nums bg-muted/50 text-muted-foreground" placeholder="Total"
                    readonly tabindex="-1">
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        (function () {
            const container = document.getElementById('piutang-items');
            const template = document.getElementById('piutang-row-template');
            const countInput = document.getElementById('piutang-jumlah');
            const grandTotal = document.getElementById('piutang-grand-total');
            const formatter = new Intl.NumberFormat('id-ID');

            function digits(value) {
                return (value || '').replace(/\D/g, '');
            }

            function recalcRow(row) {
                const harga = parseInt(digits(row.querySelector('[data-item-harga]').value) || '0', 10);
                const unit = parseInt(row.querySelector('[data-item-unit]').value) || 0;
                const result = harga * unit;
                row.querySelector('[data-item-total]').value = result > 0 ? formatter.format(result) : '';
            }

            function recalc() {
                let sum = 0;
                container.querySelectorAll('.piutang-item-row').forEach(function (row, index) {
                    const no = row.querySelector('[data-row-no]');
                    if (no) no.textContent = index + 1;
                    recalcRow(row);
                    sum += parseInt(digits(row.querySelector('[data-item-total]').value) || '0', 10);
                });
                grandTotal.textContent = 'Rp ' + formatter.format(sum);
            }

            function syncRows() {
                let target = parseInt(countInput.value) || 1;
                if (target < 1) target = 1;
                if (target > 100) target = 100;
                let rows = container.querySelectorAll('.piutang-item-row');
                while (rows.length < target) {
                    container.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', Date.now() + rows.length));
                    rows = container.querySelectorAll('.piutang-item-row');
                }
                while (rows.length > target) {
                    rows[rows.length - 1].remove();
                    rows = container.querySelectorAll('.piutang-item-row');
                }
                recalc();
            }

            countInput.addEventListener('input', syncRows);
            countInput.addEventListener('change', syncRows);

            container.addEventListener('input', function (e) {
                if (e.target.matches('[data-rupiah]')) {
                    const d = digits(e.target.value);
                    e.target.value = d ? formatter.format(d) : '';
                }
                recalc();
            });

            container.addEventListener('change', recalc);

            recalc();
        })();
    </script>
@endpush
