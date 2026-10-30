@extends('layouts.app')

@section('title', 'Help Center')

@section('content')
    <div>
        <h1 class="text-2xl font-bold tracking-tight">Help Center</h1>
        <p class="text-muted-foreground">Panduan singkat penggunaan aplikasi piutang BMT.</p>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <svg class="icon text-muted-foreground"><use href="#icon-wallet"></use></svg>
                    Catat Piutang
                </h3>
                <p class="card-description">Mencatat transaksi piutang baru.</p>
            </div>
            <div class="card-content">
                <ol class="list-decimal space-y-1.5 ps-5 text-sm text-muted-foreground">
                    <li>Buka menu <span class="font-medium text-foreground">Master Data &rarr; Data Piutang</span>.</li>
                    <li>Pilih peminjam lalu isi tanggal transaksi.</li>
                    <li>Isi nama barang, harga, dan total. Angka otomatis diberi titik ribuan.</li>
                    <li>Klik <span class="font-medium text-foreground">Simpan</span>, data langsung masuk ke halaman Piutang.</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <svg class="icon text-muted-foreground"><use href="#icon-users"></use></svg>
                    Kelola Peminjam
                </h3>
                <p class="card-description">Menambah dan memperbarui data peminjam.</p>
            </div>
            <div class="card-content">
                <ol class="list-decimal space-y-1.5 ps-5 text-sm text-muted-foreground">
                    <li>Buka menu <span class="font-medium text-foreground">Master Data &rarr; Data Peminjam</span>.</li>
                    <li>Klik <span class="font-medium text-foreground">Tambah Peminjam</span> untuk mengisi nama, alamat, dan no HP.</li>
                    <li>Gunakan ikon pensil untuk mengubah data, ikon hapus untuk menghapus.</li>
                    <li>Peminjam yang sudah punya piutang tidak bisa dihapus.</li>
                </ol>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <svg class="icon text-muted-foreground"><use href="#icon-download"></use></svg>
                    Lihat &amp; Ekspor Data
                </h3>
                <p class="card-description">Melihat daftar piutang dan mengunduhnya.</p>
            </div>
            <div class="card-content">
                <ol class="list-decimal space-y-1.5 ps-5 text-sm text-muted-foreground">
                    <li>Halaman <span class="font-medium text-foreground">Piutang</span> menampilkan seluruh piutang yang tercatat.</li>
                    <li>Gunakan kolom pencarian untuk mencari nama, barang, atau tanggal.</li>
                    <li>Klik <span class="font-medium text-foreground">Excel</span> atau <span class="font-medium text-foreground">PDF</span> untuk mengunduh data.</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Pertanyaan Umum</h3>
            <p class="card-description">Hal yang sering ditanyakan pengguna.</p>
        </div>
        <div class="card-content">
            <dl class="divide-y">
                <div class="space-y-1 py-3 first:pt-0 last:pb-0">
                    <dt class="text-sm font-medium">Apa bedanya halaman Piutang dan Data Piutang?</dt>
                    <dd class="text-sm text-muted-foreground">Halaman Piutang hanya untuk melihat daftar piutang. Pencatatan piutang baru dilakukan di Master Data &rarr; Data Piutang.</dd>
                </div>
                <div class="space-y-1 py-3 first:pt-0 last:pb-0">
                    <dt class="text-sm font-medium">Apakah data piutang bisa diubah atau dihapus?</dt>
                    <dd class="text-sm text-muted-foreground">Belum. Piutang yang sudah dicatat hanya bisa dilihat dan diekspor.</dd>
                </div>
                <div class="space-y-1 py-3 first:pt-0 last:pb-0">
                    <dt class="text-sm font-medium">Kenapa peminjam gagal dihapus?</dt>
                    <dd class="text-sm text-muted-foreground">Peminjam yang masih memiliki data piutang tidak bisa dihapus agar riwayat piutang tetap utuh.</dd>
                </div>
                <div class="space-y-1 py-3 first:pt-0 last:pb-0">
                    <dt class="text-sm font-medium">Di mana mengubah nama dan password akun?</dt>
                    <dd class="text-sm text-muted-foreground">Buka menu Settings &rarr; Akun, lalu simpan perubahan.</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-4 flex flex-col items-start justify-between gap-4 rounded-lg border bg-card p-5 shadow-sm sm:flex-row sm:items-center">
        <div class="flex items-start gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                <svg class="icon"><use href="#icon-phone"></use></svg>
            </span>
            <div class="space-y-0.5">
                <p class="text-sm font-medium">Masih butuh bantuan?</p>
                <p class="text-sm text-muted-foreground">Hubungi admin BMT lewat WhatsApp.</p>
            </div>
        </div>
        <a href="https://wa.me/6283132563845?text=Halo%20admin%20BMT%20Cinde%20Barokah%2C%20saya%20ingin%20bertanya."
            target="_blank" rel="noopener" class="btn btn-primary gap-2">
            <svg class="icon icon-sm"><use href="#icon-phone"></use></svg>
            Chat WhatsApp
        </a>
    </div>
@endsection
