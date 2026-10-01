<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/orders', 'order.index')->name('orders.index');
    Route::view('/orders/create', 'order.create')->name('orders.create');
    Route::view('/orders/{id}/edit', 'order.edit')->name('orders.edit');
    Route::view('/audit-logs', 'audit-logs.index')->name('audit-logs.index');
});
