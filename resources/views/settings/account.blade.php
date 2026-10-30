@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    <x-settings-layout>
        <form method="POST" action="{{ route('settings.account.update') }}" class="max-w-2xl space-y-6">
            @csrf
            @method('PUT')

            <section class="card">
                <div class="card-header">
                    <h2 class="card-title">Profil</h2>
                    <p class="card-description">Nama dan email akun Anda.</p>
                </div>
                <div class="card-content grid gap-5 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="label" for="account-name">Nama</label>
                        <input id="account-name" name="name" type="text"
                            class="input @error('name') border-destructive @enderror"
                            value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" required autocomplete="name">
                        @error('name')
                            <p class="form-message">{{ $message }}</p>
                        @enderror
                        <p class="form-description">Tampil di profil dan di seluruh aplikasi.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="account-email">Email</label>
                        <input id="account-email" name="email" type="email"
                            class="input @error('email') border-destructive @enderror"
                            value="{{ old('email', $user->email) }}" placeholder="nama@email.com" required autocomplete="email">
                        @error('email')
                            <p class="form-message">{{ $message }}</p>
                        @enderror
                        <p class="form-description">Dipakai untuk masuk ke aplikasi.</p>
                    </div>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h2 class="card-title">Ganti password</h2>
                    <p class="card-description">Biarkan kosong jika Anda tidak ingin mengganti password.</p>
                </div>
                <div class="card-content grid gap-5 sm:grid-cols-2">
                    <div class="space-y-2 sm:col-span-2">
                        <label class="label" for="account-current-password">Password saat ini</label>
                        <input id="account-current-password" name="current_password" type="password"
                            class="input @error('current_password') border-destructive @enderror"
                            placeholder="Masukkan password saat ini" autocomplete="current-password">
                        @error('current_password')
                            <p class="form-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="account-password">Password baru</label>
                        <input id="account-password" name="password" type="password"
                            class="input @error('password') border-destructive @enderror"
                            placeholder="Minimal 8 karakter" autocomplete="new-password">
                        @error('password')
                            <p class="form-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label" for="account-password-confirmation">Konfirmasi password baru</label>
                        <input id="account-password-confirmation" name="password_confirmation" type="password"
                            class="input" placeholder="Ulangi password baru" autocomplete="new-password">
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            </div>
        </form>
    </x-settings-layout>
@endsection
