@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="auth-card mx-auto w-full max-w-sm p-6">
        <div class="mb-6 space-y-1.5">
            <h1 class="text-lg font-semibold tracking-tight">Masuk</h1>
            <p class="text-sm text-muted-foreground">
                Masuk untuk mengelola piutang dan data peminjam BMT Cinde Barokah.
            </p>
        </div>

        <form method="POST" action="{{ route('auth.signin.post') }}" class="grid gap-4">
            @csrf

            <div class="space-y-2">
                <label class="label" for="signin-email">Email</label>
                <input id="signin-email" name="email" type="email" class="input" value="{{ old('email') }}"
                    placeholder="nama@email.com" autocomplete="email" required autofocus>
                @error('email')
                    <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="label" for="signin-password">Password</label>
                <div class="relative">
                    <input id="signin-password" name="password" type="password" class="input pe-10"
                        placeholder="••••••••" autocomplete="current-password" required>
                    <button type="button" class="absolute right-0 top-0 flex h-9 w-9 items-center justify-center text-muted-foreground hover:text-foreground"
                        data-password-toggle="signin-password" aria-label="Tampilkan password">
                        <svg class="icon"><use href="#icon-eye"></use></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs font-medium text-destructive">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-muted-foreground">
                <input type="checkbox" name="remember" class="size-4 rounded border-input accent-primary" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>

            <button type="submit" class="btn btn-primary mt-1 w-full">
                <svg class="icon"><use href="#icon-arrow-right"></use></svg>
                Masuk
            </button>
        </form>
    </div>
@endsection
