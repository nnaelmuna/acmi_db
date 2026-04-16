<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    // show page sign in
    public function showLoginForm() 
    {
        return view('auth.login');
    }

    // proses login
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember'); // Menangkap checkbox remember me

        // 2. Coba melakukan autentikasi
        if (Auth::attempt($credentials, $remember)) {
            
            // 3. Keamanan: Regenerasi session id
            $request->session()->regenerate();

            // 4. Redirect ke halaman yang sebelumnya ingin diakses user, atau default ke dashboard
            return redirect()->intended('/dashboard');
        }

        // 5. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Memproses proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Mencegah CSRF setelah logout

        return redirect('/');
    }
}
