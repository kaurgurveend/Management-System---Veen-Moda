<?php

namespace App\Providers;

// TAMBAHKAN DUA BARIS INI DI ATAS:
use Illuminate\Support\Facades\Gate; 
use App\Models\User;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sekarang Gate dan User sudah dikenali karena ada 'use' di atas
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });

        // Share data stok kritis ke semua view (untuk badge notifikasi di sidebar)
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $criticalStockCount = ProductVariant::where('stock', '<', 2)->count();
                $view->with('criticalStockCount', $criticalStockCount);
            }
        });
    }
}