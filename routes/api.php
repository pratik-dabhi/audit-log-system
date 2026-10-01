<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', OrderController::class)->names('api.orders');
    Route::get('audit-logs', [AuditLogController::class, 'index']);
    Route::get('orders/{order}/audit-logs', [AuditLogController::class, 'orderLogs']);
});
