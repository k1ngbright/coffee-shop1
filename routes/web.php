<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CouponController;

// หน้าแรก → redirect ไปหน้า POS
Route::get('/', function () {
    return redirect()->route('orders.index');
});

// ===== Order routes =====
Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
Route::post('/orders/apply-coupon', [OrderController::class, 'applyCoupon'])->name('orders.apply-coupon');

// ===== Coupon routes =====
Route::resource('coupons', CouponController::class);

// ===== Admin: จัดการเมนู =====
Route::prefix('admin')->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('admin.menu.show');
    Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
});