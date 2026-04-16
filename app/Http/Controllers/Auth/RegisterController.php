<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // show page signup
    public function showRegistrationForm()
    {
        return view('auth.register'); 
    }

    // proses data register
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // pastikan di form ada input password_confirmation
        ]);

        // 2. Simpan user ke database dan enkripsi password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Langsung loginkan user yang baru mendaftar
        Auth::login($user);

        // 4. Redirect ke halaman dashboard/home
        return redirect('/dashboard'); 
    }
}
