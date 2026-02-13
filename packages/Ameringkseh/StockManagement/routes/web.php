<?php

use Illuminate\Support\Facades\Route;
use Ameringkseh\StockManagement\Http\Controllers\StockManagementController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/stok-management', [StockManagementController::class, 'index'])->name('stock.index');
    Route::post('/stok-management/update', [StockManagementController::class, 'update'])->name('stock.update');
    Route::delete('/stok-management/{id}', [StockManagementController::class, 'destroy'])->name('stock.destroy');
});
