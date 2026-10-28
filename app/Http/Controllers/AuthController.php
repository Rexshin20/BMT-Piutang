<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.signin');
    }

    public function signinSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('dashboard'))
            ->with('status', 'Selamat datang kembali, ' . Auth::user()->name . '.');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function signupSubmit(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return redirect()->route('auth.signin')->with('status', 'Account created. Please sign in.');
    }

    public function forgot()
    {
        return view('auth.forgot-password');
    }

    public function forgotSubmit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return back()->with('status', 'Password reset link sent to ' . $request->input('email'));
    }

    public function otp()
    {
        return view('auth.otp');
    }

    public function otpSubmit(Request $request)
    {
        return redirect()->route('dashboard')->with('status', 'Two-factor authentication verified.');
    }

    public function signout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.signin')->with('status', 'Anda telah keluar.');
    }
}
