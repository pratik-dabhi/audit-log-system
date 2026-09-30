<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/order', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/order/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/order/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/order/{id}/update', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/order/{id}/delete', [OrderController::class, 'delete'])->name('orders.destroy');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit_log.index');

});

