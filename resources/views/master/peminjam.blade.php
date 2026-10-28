@extends('layouts.app')

@section('title', 'Data Peminjam')

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">Data Peminjam</h1>
            <p class="text-muted-foreground">Data peminjam: nama, alamat, dan nomor HP.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="rounded-lg border bg-card px-4 py-2 text-left shadow-sm sm:text-right">
                <span class="block text-xs text-muted-foreground">Total Peminjam</span>
                <span class="text-lg font-semibold tabular-nums">{{ count($borrowers) }}</span>
            </div>
            <button type="button" class="btn btn-primary gap-2" data-add-peminjam>
                <svg class="icon icon-sm"><use href="#icon-plus"></use></svg>
                Tambah Peminjam
            </button>
        </div>
    </div>

    <div class="table-wrapper mt-4">
        <table id="peminjam-table" class="data-table w-full min-w-[60rem]">
            <thead>
                <tr>
                    <th class="w-14">No</th>
                    <th class="w-52">Nama</th>
                    <th>Alamat</th>
                    <th class="w-40">No HP</th>
                    <th data-orderable="false" data-searchable="false" class="w-24 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrowers as $borrower)
                    <tr>
                        <td class="tabular-nums text-muted-foreground">{{ $loop->iteration }}</td>
                        <td class="whitespace-nowrap font-medium">{{ $borrower->nama }}</td>
                        <td class="text-muted-foreground">{{ $borrower->alamat }}</td>
                        <td class="whitespace-nowrap tabular-nums text-muted-foreground">{{ $borrower->telepon }}</td>
                        <td class="text-right">
                            <div class="flex justify-end gap-1">
                                <button type="button" class="btn btn-ghost btn-icon" aria-label="Edit {{ $borrower->nama }}"
                                    data-edit-peminjam
                                    data-id="{{ $borrower->id }}"
                                    data-nama="{{ $borrower->nama }}"
                                    data-alamat="{{ $borrower->alamat }}"
                                    data-telepon="{{ $borrower->telepon }}">
                                    <svg class="icon icon-sm"><use href="#icon-pencil"></use></svg>
                                </button>
                                <button type="button" class="btn btn-ghost btn-icon text-destructive" aria-label="Hapus {{ $borrower->nama }}"
                                    data-delete-peminjam
                                    data-id="{{ $borrower->id }}"
                                    data-nama="{{ $borrower->nama }}">
                                    <svg class="icon icon-sm"><use href="#icon-trash-2"></use></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tambah / edit peminjam --}}
    <div class="dialog" id="peminjam-dialog" role="dialog" aria-modal="true" aria-labelledby="peminjam-dialog-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <div class="dialog-header">
                <h2 id="peminjam-dialog-title" class="dialog-title">Tambah Peminjam</h2>
                <p id="peminjam-dialog-description" class="dialog-description">Isi nama, alamat, dan nomor HP peminjam.</p>
            </div>
            <form method="POST" action="{{ route('master.borrowers.store') }}" id="peminjam-form">
                @csrf
                <input type="hidden" name="_method" value="POST" id="peminjam-method">
                <input type="hidden" name="_id" value="{{ old('_id') }}">

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="label" for="peminjam-nama">Nama</label>
                        <input id="peminjam-nama" name="nama" type="text" class="input"
                            value="{{ old('nama') }}" required autofocus>
                        @error('nama')
                            <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="peminjam-alamat">Alamat</label>
                        <textarea id="peminjam-alamat" name="alamat" class="textarea" rows="2" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="label" for="peminjam-telepon">No HP</label>
                        <input id="peminjam-telepon" name="telepon" type="text" inputmode="tel" class="input"
                            value="{{ old('telepon') }}" required>
                        @error('telepon')
                            <p data-form-error class="text-xs font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Batal</button>
                    <button type="submit" class="btn btn-primary" id="peminjam-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hapus peminjam --}}
    <div class="dialog" id="delete-peminjam-dialog" role="dialog" aria-modal="true" aria-labelledby="delete-peminjam-title">
        <div class="dialog-overlay" data-dialog-close></div>
        <div class="dialog-panel max-w-md">
            <div class="dialog-header">
                <h2 id="delete-peminjam-title" class="dialog-title">Hapus Peminjam</h2>
                <p class="dialog-description">
                    Data <span id="delete-peminjam-name" class="font-medium text-foreground"></span> akan dihapus permanen.
                </p>
            </div>
            <form method="POST" action="" id="delete-peminjam-form">
                @csrf
                @method('DELETE')
                <div class="dialog-footer">
                    <button type="button" class="btn btn-outline" data-dialog-close>Batal</button>
                    <button type="submit" class="btn btn-destructive">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.datatables', [
        'tableId' => 'peminjam-table',
        'searchPlaceholder' => 'Cari nama, alamat, atau no HP...',
        'emptyMessage' => 'Belum ada data peminjam',
    ])
@endsection

@push('scripts')
    <script>
        jQuery(function ($) {
            const form = document.getElementById('peminjam-form');
            const method = document.getElementById('peminjam-method');
            const title = document.getElementById('peminjam-dialog-title');
            const description = document.getElementById('peminjam-dialog-description');
            const submit = document.getElementById('peminjam-submit');
            const deleteForm = document.getElementById('delete-peminjam-form');
            const deleteName = document.getElementById('delete-peminjam-name');
            const base = @json(url('/master/peminjam'));
            const storeUrl = @json(route('master.borrowers.store'));

            const old = {
                id: @json(old('_id')),
                nama: @json(old('nama')),
                alamat: @json(old('alamat')),
                telepon: @json(old('telepon')),
            };

            function setFields(values) {
                form.elements.nama.value = values.nama || '';
                form.elements.alamat.value = values.alamat || '';
                form.elements.telepon.value = values.telepon || '';
            }

            function clearErrors() {
                form.querySelectorAll('[data-form-error]').forEach((el) => el.remove());
            }

            function showAddDialog(preserveErrors) {
                if (!preserveErrors) clearErrors();
                form.reset();
                form.action = storeUrl;
                method.value = 'POST';
                form.elements._id.value = '';
                setFields({});
                title.textContent = 'Tambah Peminjam';
                description.textContent = 'Isi nama, alamat, dan nomor HP peminjam.';
                submit.textContent = 'Simpan';
                window.App.openDialog('peminjam-dialog');
            }

            function showEditDialog(data, preserveErrors) {
                if (!preserveErrors) clearErrors();
                form.reset();
                form.action = base + '/' + data.id;
                method.value = 'PUT';
                form.elements._id.value = data.id;
                setFields(data);
                title.textContent = 'Edit Peminjam';
                description.textContent = 'Perbarui data peminjam.';
                submit.textContent = 'Perbarui';
                window.App.openDialog('peminjam-dialog');
            }

            document.addEventListener('click', function (event) {
                if (event.target.closest('[data-add-peminjam]')) {
                    event.preventDefault();
                    showAddDialog();
                    return;
                }

                const edit = event.target.closest('[data-edit-peminjam]');
                if (edit) {
                    event.preventDefault();
                    showEditDialog(edit.dataset);
                    return;
                }

                const remove = event.target.closest('[data-delete-peminjam]');
                if (remove) {
                    event.preventDefault();
                    deleteName.textContent = remove.dataset.nama;
                    deleteForm.action = base + '/' + remove.dataset.id;
                    window.App.openDialog('delete-peminjam-dialog');
                }
            });

            @if ($errors->any())
                if (old.id) {
                    showEditDialog(old, true);
                } else {
                    showAddDialog(true);
                    setFields(old);
                }
            @endif
        });
    </script>
@endpush
