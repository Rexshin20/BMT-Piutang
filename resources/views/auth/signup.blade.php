@extends('layouts.auth')

@section('title', 'Sign Up')

@section('content')
    <div class="auth-card mx-auto w-full max-w-sm p-6">
        <div class="mb-4 space-y-1.5">
            <h1 class="text-lg font-semibold tracking-tight">Create an account</h1>
            <p class="text-sm text-muted-foreground">
                Enter your email and password to create an account.
                <br>
                Already have an account?
                <a href="{{ route('auth.signin') }}" class="underline underline-offset-4 hover:text-primary">Sign In</a>
            </p>
        </div>

        <form data-demo-form data-redirect="{{ route('auth.signin') }}" data-loading="Creating account..." data-message="Account created. Please sign in." class="grid gap-3">
            <div class="space-y-2">
                <label class="label" for="signup-email">Email</label>
                <input id="signup-email" name="email" type="email" class="input" placeholder="name@example.com" autocomplete="email" required>
            </div>

            <div class="space-y-2">
                <label class="label" for="signup-password">Password</label>
                <div class="relative">
                    <input id="signup-password" name="password" type="password" class="input pe-10" placeholder="********" autocomplete="new-password" required>
                    <button type="button" class="absolute right-0 top-0 flex h-9 w-9 items-center justify-center text-muted-foreground hover:text-foreground" data-password-toggle="signup-password" aria-label="Show password">
                        <svg class="icon"><use href="#icon-eye"></use></svg>
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <label class="label" for="signup-confirm">Confirm Password</label>
                <div class="relative">
                    <input id="signup-confirm" name="password_confirmation" type="password" class="input pe-10" placeholder="********" autocomplete="new-password" required>
                    <button type="button" class="absolute right-0 top-0 flex h-9 w-9 items-center justify-center text-muted-foreground hover:text-foreground" data-password-toggle="signup-confirm" aria-label="Show password">
                        <svg class="icon"><use href="#icon-eye"></use></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-2">
                <svg class="icon"><use href="#icon-user-plus"></use></svg>
                Create Account
            </button>

            <div class="auth-separator">
                <span>Or continue with</span>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button type="button" class="btn btn-outline" onclick="App.showToast('GitHub sign up is not configured.')">
                    <svg class="icon"><use href="#brand-github"></use></svg>
                    GitHub
                </button>
                <button type="button" class="btn btn-outline" onclick="App.showToast('Facebook sign up is not configured.')">
                    <svg class="icon"><use href="#brand-facebook"></use></svg>
                    Facebook
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-muted-foreground">
            By creating an account, you agree to our
            <a href="#" class="underline underline-offset-4 hover:text-primary">Terms of Service</a>
            and
            <a href="#" class="underline underline-offset-4 hover:text-primary">Privacy Policy</a>.
        </p>
    </div>
@endsection
