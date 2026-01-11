<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Auth;

// 1. Halaman Depan (Guest)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 2. Route Autentikasi (Login, Register, Logout)
// Disable register: hanya login dan logout yang aktif
Auth::routes(['register' => false]);

// Fallback untuk GET logout (redirect ke POST form)
Route::get('/logout', function () {
    return view('logout-form');
})->name('logout.form');

// 3. Semua Route yang butuh Login dan Akun Aktif
Route::middleware(['auth', 'user.active'])->group(function () {

    // Alihkan /home ke dashboard agar seragam
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // --- FITUR KAIN & STOK ---
    // Lihat Daftar Stok (Admin & Staff bisa lihat)
    Route::get('/fabrics', [FabricController::class, 'index'])->name('fabrics.index');
    
    // Tambah Kain Baru (Admin & Staff bisa tambah)
    Route::get('/fabrics/create', [FabricController::class, 'create'])->name('fabrics.create');
    Route::post('/fabrics/store', [FabricController::class, 'store'])->name('fabrics.store');

    // Tambah Stok (Staff only workflow)
    Route::get('/fabrics/{id}/add-stock', [FabricController::class, 'addStock'])->name('fabrics.add-stock');
    Route::post('/fabrics/{id}/add-stock', [FabricController::class, 'storeAddStock'])->name('fabrics.store-add-stock');
    
    // Edit Produk (Admin bisa edit semua, Staff akan diarahkan ke Add Stock)
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
        
        // Pengingat Pembayaran (Admin) - place BEFORE resource to avoid matching by resource's show route
        Route::get('/supplier-shipments/reminders', [\App\Http\Controllers\SupplierShipmentController::class, 'reminders'])->name('supplier-shipments.reminders');
        Route::post('/supplier-shipments/{id}/mark-reminder-sent', [\App\Http\Controllers\SupplierShipmentController::class, 'markReminderSent'])->name('supplier-shipments.mark-reminder-sent');
        Route::post('/supplier-shipments/mark-reminders-sent', [\App\Http\Controllers\SupplierShipmentController::class, 'markAllRemindersSent'])->name('supplier-shipments.mark-all-reminders-sent');

        // Supplier Shipments (Barang Masuk)
        Route::resource('supplier-shipments', \App\Http\Controllers\SupplierShipmentController::class);
        Route::post('/supplier-shipments/{id}/upload-payment-proof', [\App\Http\Controllers\SupplierShipmentController::class, 'uploadPaymentProof'])->name('supplier-shipments.upload-payment-proof');
        Route::delete('/supplier-shipments/{id}/delete-payment-proof', [\App\Http\Controllers\SupplierShipmentController::class, 'deletePaymentProof'])->name('supplier-shipments.delete-payment-proof');
        
        // Activity Log
        Route::get('/activity-logs', [\App\Http\Controllers\HomeController::class, 'activityLogs'])->name('activity-logs');

        // Laporan Penjualan (Per Staff & Keseluruhan)
        Route::get('/sales/reports', [\App\Http\Controllers\SalesController::class, 'reports'])->name('sales.reports');
        
        // --- HAPUS KAIN (HANYA ADMIN) ---
        Route::delete('/fabrics/{id}', [FabricController::class, 'destroy'])->name('fabrics.destroy');
    });

});