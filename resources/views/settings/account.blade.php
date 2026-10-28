@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    <x-settings-layout>
        <div class="flex flex-col">
            <div>
                <h3 class="text-lg font-medium">Akun</h3>
                <p class="text-sm text-muted-foreground">Perbarui nama, email, dan password akun Anda.</p>
            </div>
            <div class="my-4 border-t"></div>

            <form method="POST" action="{{ route('settings.account.update') }}" class="space-y-8 lg:max-w-xl">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-green-700 text-lg font-semibold text-white">
                        {{ $user->initials() }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate font-medium">{{ $user->name }}</p>
                        <p class="truncate text-sm text-muted-foreground">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="label" for="account-name">Nama</label>
                    <input id="account-name" name="name" type="text" class="input @error('name') border-destructive @enderror"
                        value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" required autocomplete="name">
                    @error('name')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                    <p class="form-description">Nama ini tampil di profil dan di seluruh aplikasi.</p>
                </div>

                <div class="space-y-2">
                    <label class="label" for="account-email">Email</label>
                    <input id="account-email" name="email" type="email" class="input @error('email') border-destructive @enderror"
                        value="{{ old('email', $user->email) }}" placeholder="nama@bmt.test" required autocomplete="email">
                    @error('email')
                        <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                    @enderror
                    <p class="form-description">Email digunakan untuk masuk ke aplikasi.</p>
                </div>

                <div class="border-t pt-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-medium">Ganti Password</h4>
                        <p class="text-sm text-muted-foreground">Isi bagian ini hanya jika ingin mengganti password. Perubahan nama dan email tidak memerlukan password.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="label" for="account-current-password">Password saat ini</label>
                            <input id="account-current-password" name="current_password" type="password"
                                class="input @error('current_password') border-destructive @enderror"
                                placeholder="Masukkan password saat ini" autocomplete="current-password">
                            @error('current_password')
                                <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="label" for="account-password">Password baru</label>
                            <input id="account-password" name="password" type="password"
                                class="input @error('password') border-destructive @enderror"
                                placeholder="Minimal 8 karakter" autocomplete="new-password">
                            @error('password')
                                <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="label" for="account-password-confirmation">Konfirmasi password baru</label>
                            <input id="account-password-confirmation" name="password_confirmation" type="password"
                                class="input" placeholder="Ulangi password baru" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            </form>
        </div>
    </x-settings-layout>
@endsection
