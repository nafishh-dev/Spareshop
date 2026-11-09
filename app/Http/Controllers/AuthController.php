<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin() {
        return view('auth.login');
    }

    // 2. Proses Login & Buat Session
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //mengecek password_hash dan membuat Session
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            session(['role_aktif' => Auth::user()->role]);
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/katalog');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    //Tampilkan Halaman Register
    public function showRegister() {
        return view('auth.register');
    }

    //Proses Register Akun Baru (Create User)
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer'
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    //Proses Logout & Hapus Session
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}