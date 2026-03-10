<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\StockTransferController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/warehouses/{id}/inventory', [InventoryController::class, 'show']);
    Route::middleware('has_role:admin')->group(function () {
        Route::post('/stock-transfers', [StockTransferController::class, 'store']);
    });
});
