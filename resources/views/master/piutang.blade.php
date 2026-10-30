@extends('layouts.app')

@section('title', 'Data Piutang')

@section('content')
    <div>
        <h1 class="text-2xl font-bold tracking-tight">Data Piutang</h1>
        <p class="text-muted-foreground">Catat piutang baru untuk peminjam.</p>
    </div>

    <div class="mt-4 max-w-3xl rounded-lg border bg-card p-5 shadow-sm">
        <form method="POST" action="{{ route('master.piutang.store') }}" class="space-y-4">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
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

                <div class="space-y-2">
                    <label class="label" for="piutang-tanggal">Tanggal</label>
                    <input id="piutang-tanggal" name="tanggal" type="date" class="input"
                        value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                    @error('tanggal')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="space-y-2 sm:col-span-2">
                    <label class="label" for="piutang-barang">Barang</label>
                    <input id="piutang-barang" name="barang" type="text" class="input"
                        value="{{ old('barang') }}" placeholder="Contoh: Beras" required>
                    @error('barang')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="label" for="piutang-unit">Unit</label>
                    <input id="piutang-unit" name="unit" type="number" min="1" step="1" inputmode="numeric"
                        class="input tabular-nums" value="{{ old('unit', 1) }}" required>
                    @error('unit')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <label class="label" for="piutang-harga">Harga</label>
                    <input id="piutang-harga" name="harga" type="text" inputmode="numeric" autocomplete="off"
                        class="input tabular-nums" data-rupiah
                        value="{{ old('harga') ? number_format((int) preg_replace('/\D/', '', old('harga')), 0, ',', '.') : '' }}"
                        placeholder="0" required>
                    @error('harga')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="label" for="piutang-total">Total</label>
                    <input id="piutang-total" name="total" type="text" inputmode="numeric" autocomplete="off"
                        class="input tabular-nums bg-muted/50 text-muted-foreground" readonly
                        value="{{ old('total') ? number_format((int) preg_replace('/\D/', '', old('total')), 0, ',', '.') : '' }}"
                        placeholder="0">
                    <p class="form-description">Otomatis dari Harga × Unit.</p>
                </div>
            </div>

            <div class="flex justify-end border-t pt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-rupiah]').forEach(function (input) {
            function format() {
                const digits = input.value.replace(/\D/g, '');
                input.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
            }

            input.addEventListener('input', format);
            format();
        });

        (function () {
            const harga = document.getElementById('piutang-harga');
            const unit = document.getElementById('piutang-unit');
            const total = document.getElementById('piutang-total');

            function updateTotal() {
                const hargaValue = parseInt(harga.value.replace(/\D/g, '') || '0', 10);
                const unitValue = parseInt(unit.value.replace(/\D/g, '') || '0', 10);
                const result = hargaValue * unitValue;
                total.value = result > 0 ? new Intl.NumberFormat('id-ID').format(result) : '';
            }

            [harga, unit].forEach(function (input) {
                input.addEventListener('input', updateTotal);
            });

            updateTotal();
        })();
    </script>
@endpush
