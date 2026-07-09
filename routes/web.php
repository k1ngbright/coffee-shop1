<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuPageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AuthController;

// ===== หน้าแรก → หน้าเมนู public =====
Route::get('/', function () {
    return redirect()->route('menu');
});

// ===== หน้าเมนู public (guest ดูได้) =====
Route::get('/menu', [MenuPageController::class, 'index'])->name('menu');

// ===== Auth routes =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== Order routes (ต้อง login ก่อน) =====
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/apply-coupon', [OrderController::class, 'applyCoupon'])->name('orders.apply-coupon');
});

// ===== Admin routes (ต้อง login + เป็น admin) =====
Route::middleware(['auth', 'is_admin'])->group(function () {
    // จัดการคูปอง
    Route::resource('coupons', CouponController::class);

    // จัดการเมนู
    Route::prefix('admin')->group(function () {
        Route::get('/menu', [MenuController::class, 'index'])->name('admin.menu.index');
        Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
        Route::post('/menu', [MenuController::class, 'store'])->name('admin.menu.store');
        Route::get('/menu/{id}', [MenuController::class, 'show'])->name('admin.menu.show');
        Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
        Route::put('/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    });
});