<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Halaman untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Halaman yang WAJIB Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/katalog', function () {
        return view("katalog");
        //return "Halo Pembeli: " . Auth::user()->name; // <-- 2. Gunakan Auth::user()
    });

    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin') { // <-- Gunakan Auth::user()
            abort(403, 'Akses ditolak! Halaman ini khusus Admin.');
        }
        return view("admin.dashboard");
    });
});