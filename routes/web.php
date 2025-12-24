<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Auth;

// 1. Halaman Depan (Guest)
Route::get('/', function () {
    return view('welcome');
});

// 2. Route Autentikasi (Login, Register, Logout)
Auth::routes();

// 3. Semua Route yang butuh Login dan Akun Aktif
Route::middleware(['auth', 'user.active'])->group(function () {

    // Alihkan /home ke dashboard agar seragam
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // --- FITUR KAIN & STOK (Admin & Staff) ---
    // Lihat Daftar Stok
    Route::get('/fabrics', [FabricController::class, 'index'])->name('fabrics.index');
    
    // Form Input Kain
    Route::get('/fabrics/create', [FabricController::class, 'create'])->name('fabrics.create');
    
    // Proses Simpan Kain
    Route::post('/fabrics/store', [FabricController::class, 'store'])->name('fabrics.store');
    
    // Edit & Update Kain
    Route::get('/fabrics/{id}/edit', [FabricController::class, 'edit'])->name('fabrics.edit');
    Route::put('/fabrics/{id}', [FabricController::class, 'update'])->name('fabrics.update');

    // --- FITUR PENJUALAN (Admin & Staff) ---
    Route::resource('sales', \App\Http\Controllers\SalesController::class)->only(['index', 'create', 'store']);


    // --- KHUSUS ADMIN (Gunakan Gate admin-only) ---
    Route::middleware(['can:admin-only'])->group(function () {
        // Kelola User/Staff
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::post('/users/{user}/toggle-active', [\App\Http\Controllers\UserController::class, 'toggleActive'])->name('users.toggle-active');
        
        // Kelola Kategori Kain
        Route::resource('categories', \App\Http\Controllers\CategoryController::class);
        
        // Supplier Shipments (Barang Masuk)
        Route::resource('supplier-shipments', \App\Http\Controllers\SupplierShipmentController::class);
        Route::post('/supplier-shipments/{id}/upload-payment-proof', [\App\Http\Controllers\SupplierShipmentController::class, 'uploadPaymentProof'])->name('supplier-shipments.upload-payment-proof');
        Route::delete('/supplier-shipments/{id}/delete-payment-proof', [\App\Http\Controllers\SupplierShipmentController::class, 'deletePaymentProof'])->name('supplier-shipments.delete-payment-proof');
        
        // Activity Log
        Route::get('/activity-logs', [\App\Http\Controllers\HomeController::class, 'activityLogs'])->name('activity-logs');
        
        // Hapus Kain (hanya admin)
        Route::delete('/fabrics/{id}', [FabricController::class, 'destroy'])->name('fabrics.destroy');
    });

});