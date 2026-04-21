<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'Email is required.',
                'email.email' => 'Invalid email format.',
                'password.required' => 'Password is required.',
            ]
        );

        // 2. Key rate limit berdasarkan email + IP
        $throttleKey = strtolower($request->email) . '|' . $request->ip();

        // 3. Batasi percobaan login
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ]);
        }

        // 4. Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'email' => 'Email not found.',
            ])->onlyInput('email');
        }

        // 5. Cek password
        if (!Hash::check($request->password, $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'password' => 'Incorrect password.',
            ])->onlyInput('email');
        }

        // 6. Login user
        Auth::login($user, $request->boolean('remember'));
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}