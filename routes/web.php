<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// kalau belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Halaman yang WAJIB Login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Customer
    Route::get('/katalog', function () {
        if (request()->filled('motorcycle_id')) {
            session(['selected_motorcycle_id' => request('motorcycle_id')]);
        }
        return view('katalog');
    })->name('katalog.index');

    Route::get('/katalog/reset-motor', function () {
        session()->forget('selected_motorcycle_id');
        return redirect()->route('katalog.index');
    })->name('katalog.resetMotor');

    Route::get('/cart', fn () => view('customer.cart'))->name('cart.index');
    Route::get('/orders', fn () => view('customer.orders.index'))->name('orders.index');

    // Route Admin
    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak! Halaman ini khusus Admin.');
        }
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/categories', fn () => view('admin.categories.index'))->name('categories.index');
    Route::get('/admin/spareparts', fn () => view('admin.spareparts.index'))->name('spareparts.index');
    Route::get('/admin/orders', fn () => view('admin.orders.index'))->name('admin.orders.index');
});