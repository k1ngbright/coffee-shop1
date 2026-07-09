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
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // User-friendly URLs (keeping the same route names so blade files don't break)
    Route::get('/history', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/shop', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/shop', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    Route::get('/order/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/order/{order}/cancel', [OrderController::class, 'cancelByUser'])->name('orders.cancel');
    Route::post('/order/{order}/slip', [OrderController::class, 'uploadSlip'])->name('orders.upload-slip');
    Route::post('/shop/apply-coupon', [OrderController::class, 'applyCoupon'])->name('orders.apply-coupon');
});

// ===== Admin routes (ต้อง login + เป็น admin) =====
Route::middleware(['auth', 'is_admin'])->group(function () {
    // จัดการคูปอง
    Route::resource('coupons', CouponController::class);

    // จัดการออเดอร์
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // จัดการเมนู
    Route::prefix('admin')->group(function () {
        Route::get('admin/menu', [MenuController::class, 'index'])->name('admin.menu.index');
        Route::get('admin/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
        Route::post('admin/menu', [MenuController::class, 'store'])->name('admin.menu.store');
        Route::get('admin/menu/{id}', [MenuController::class, 'show'])->name('admin.menu.show');
        Route::get('admin/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
        Route::put('admin/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
        Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    });
});