<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Tampilkan form registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Tangani proses registrasi pengguna.
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'learning_path' => 'nullable|in:web-development,mobile-development,data-science',
            'role' => 'required|in:admin,siswa,mentor',
        ]);

        // Simpan data pengguna ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'learning_path' => $request->learning_path,
            'role' => $request->role,
        ]);

        // Login pengguna secara otomatis setelah registrasi
        auth()->login($user);

        // Redirect ke halaman beranda atau dashboard
        return redirect('/dashboard')->with('success', 'Registrasi berhasil!');
    }
}
