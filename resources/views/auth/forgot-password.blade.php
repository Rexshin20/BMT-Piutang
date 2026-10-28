@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-card mx-auto w-full max-w-sm p-6">
        <div class="mb-4 space-y-1.5">
            <h1 class="text-lg font-semibold tracking-tight">Forgot Password</h1>
            <p class="text-sm text-muted-foreground">
                Enter your registered email and <br> we will send you a link to reset your password.
            </p>
        </div>

        <form data-demo-form data-redirect="{{ route('auth.otp') }}" data-loading="Sending email..." data-message="Password reset email sent." class="grid gap-2">
            <div class="space-y-2">
                <label class="label" for="forgot-email">Email</label>
                <input id="forgot-email" name="email" type="email" class="input" placeholder="name@example.com" autocomplete="email" required>
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                Continue
                <svg class="icon"><use href="#icon-arrow-right"></use></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            Don't have an account?
            <a href="{{ route('auth.signup') }}" class="underline underline-offset-4 hover:text-primary">Sign up</a>.
        </p>
    </div>
@endsection
