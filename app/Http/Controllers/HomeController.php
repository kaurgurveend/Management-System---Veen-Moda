<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman Dashboard dengan statistik.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Statistik untuk semua user
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStock = ProductVariant::sum('stock');
        $lowStockProducts = ProductVariant::where('stock', '<', 5)->count();
        
        // Statistik khusus admin
        $totalUsers = 0;
        $activeUsers = 0;
        if ($user->role === 'admin') {
            $totalUsers = User::where('role', 'staff')->count();
            $activeUsers = User::where('role', 'staff')->where('is_active', true)->count();
        }
        
        // Activity logs terbaru (5 terakhir) - Hanya untuk admin
        $recentActivities = collect();
        if ($user->role === 'admin') {
            $recentActivities = ActivityLog::with('user')
                ->latest()
                ->take(5)
                ->get();
        }
        
        // Produk dengan stok rendah (untuk semua user)
        $lowStockItems = ProductVariant::with('product')
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();
        
        // Statistik stok untuk notifikasi (untuk semua user)
        $criticalStockCount = ProductVariant::where('stock', '<', 2)->count();
        $lowStockCount = ProductVariant::where('stock', '>=', 2)->where('stock', '<', 5)->count();
        $criticalStockItems = ProductVariant::with('product')
            ->where('stock', '<', 2)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
        
        // Statistik per kategori
        $categoryStats = Category::withCount('products')
            ->with(['products.variants'])
            ->get()
            ->map(function ($category) {
                $totalStock = $category->products->sum(function ($product) {
                    return $product->variants->sum('stock');
                });
                return [
                    'name' => $category->name,
                    'products_count' => $category->products_count,
                    'total_stock' => $totalStock,
                ];
            });
        
        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockProducts',
            'totalUsers',
            'activeUsers',
            'recentActivities',
            'lowStockItems',
            'categoryStats',
            'criticalStockCount',
            'lowStockCount',
            'criticalStockItems'
        ));
    }
    
    /**
     * Halaman Activity Logs (Khusus Admin)
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();
        
        // Filter berdasarkan user
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }
        
        $activities = $query->paginate(20);
        $users = User::all();
        
        return view('activity-logs', compact('activities', 'users'));
    }
}
