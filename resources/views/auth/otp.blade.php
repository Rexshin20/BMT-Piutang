@extends('layouts.auth')

@section('title', 'Two-factor Authentication')

@section('content')
    <div class="auth-card mx-auto w-full max-w-md p-6">
        <div class="mb-4 space-y-1.5">
            <h1 class="text-base font-semibold tracking-tight">Two-factor Authentication</h1>
            <p class="text-sm text-muted-foreground">
                Please enter the authentication code. <br> We have sent the authentication code to your email.
            </p>
        </div>

        <form data-demo-form data-redirect="{{ route('dashboard') }}" data-loading="Verifying..." data-message="Verified successfully." class="grid gap-2">
            <div class="flex items-center justify-between gap-2">
                <div class="flex gap-1">
                    <input type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="1" class="h-12 w-10 rounded-md border border-input bg-transparent text-center text-lg shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" data-otp-input aria-label="Digit 1">
                    <input type="text" inputmode="numeric" maxlength="1" class="h-12 w-10 rounded-md border border-input bg-transparent text-center text-lg shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" data-otp-input aria-label="Digit 2">
                </div>
                <span class="h-0.5 w-2 rounded bg-muted-foreground/40"></span>
                <div class="flex gap-1">
                    <input type="text" inputmode="numeric" maxlength="1" class="h-12 w-10 rounded-md border border-input bg-transparent text-center text-lg shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" data-otp-input aria-label="Digit 3">
                    <input type="text" inputmode="numeric" maxlength="1" class="h-12 w-10 rounded-md border border-input bg-transparent text-center text-lg shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" data-otp-input aria-label="Digit 4">
                </div>
                <span class="h-0.5 w-2 rounded bg-muted-foreground/40"></span>
                <div class="flex gap-1">
                    <input type="text" inputmode="numeric" maxlength="1" class="h-12 w-10 rounded-md border border-input bg-transparent text-center text-lg shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" data-otp-input aria-label="Digit 5">
                    <input type="text" inputmode="numeric" maxlength="1" class="h-12 w-10 rounded-md border border-input bg-transparent text-center text-lg shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring" data-otp-input aria-label="Digit 6">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-2" data-otp-submit disabled>Verify</button>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            Haven't received it?
            <a href="{{ route('auth.signin') }}" class="underline underline-offset-4 hover:text-primary">Resend a new code.</a>.
        </p>
    </div>
@endsection
