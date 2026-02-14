<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/simpan-pesanan', [OrderController::class, 'store'])->name('pesanan.store');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (protected by admin middleware)
Route::middleware('admin')->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Menu Management
    Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
    
    // Stock Management
    Route::get('/stok', [StockController::class, 'index'])->name('admin.stok');
    Route::post('/stok', [StockController::class, 'update'])->name('admin.stok.update');
    Route::delete('/stok/{id}', [StockController::class, 'destroy'])->name('admin.stok.destroy');
    
    // Order Management
    Route::get('/pesanan', [OrderController::class, 'index'])->name('admin.pesanan');
    Route::post('/pesanan/status', [OrderController::class, 'updateStatus'])->name('admin.pesanan.status');
    Route::delete('/pesanan/{id}', [OrderController::class, 'destroy'])->name('admin.pesanan.destroy');
});
