<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MotorcycleController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SparepartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/reset-motor', [CatalogController::class, 'resetMotor'])->name('katalog.resetMotor');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Route Admin
    Route::get('/admin/dashboard', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak! Halaman ini khusus Admin.');
        }

        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('/admin/categories', CategoryController::class)->except(['create', 'show', 'edit'])->names('categories');
    Route::resource('/admin/motorcycles', MotorcycleController::class)->except(['create', 'show', 'edit'])->names('motorcycles');
    Route::resource('/admin/spareparts', SparepartController::class)->names('spareparts');
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});
