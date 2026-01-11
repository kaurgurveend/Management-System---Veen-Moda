<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sales::with(['productVariant.product', 'user'])->latest();

        // Filter berdasarkan tanggal
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        // Filter berdasarkan produk
        if ($request->has('product_id') && $request->product_id != '') {
            $query->whereHas('productVariant', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        $sales = $query->paginate(20);
        $products = Product::all();

        // Statistik
        $totalSales = Sales::sum('total_price');
        $totalQuantity = Sales::sum('quantity');
        $todaySales = Sales::whereDate('created_at', today())->sum('total_price');

        return view('sales.index', compact('sales', 'products', 'totalSales', 'totalQuantity', 'todaySales'));
    }

    /**
     * Show sales reports (admin only).
     */
    public function reports(Request $request)
    {
        // Hanya admin yang dapat mengakses laporan
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Query dasar untuk total keseluruhan
        $query = Sales::query();

        // Filter rentang tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Filter user/staff
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Total keseluruhan berdasarkan filter
        $overallTotal = (clone $query)->sum('total_price');
        $overallQuantity = (clone $query)->sum('quantity');

        // Agregasi per staff
        $perStaff = Sales::select('user_id', DB::raw('SUM(total_price) as total_sales'), DB::raw('SUM(quantity) as total_quantity'))
            ->when($request->filled('start_date'), function($q) use($request){
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($q) use($request){
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            // Filter user/staff untuk agregasi
            ->when($request->filled('user_id'), function($q) use($request){
                $q->where('user_id', $request->user_id);
            })
            ->groupBy('user_id')
            ->get()
            ->sortByDesc('total_sales');

        // Lampirkan data user untuk masing-masing baris agregasi
        $userIds = $perStaff->pluck('user_id')->unique()->filter()->values();
        $userMap = User::whereIn('id', $userIds)->get()->keyBy('id');

        $perStaff = $perStaff->map(function($row) use ($userMap) {
            $row->user = $userMap->get($row->user_id);
            return $row;
        });

        // Ambil semua user dengan role staff untuk dropdown filter
        $users = User::where('role', 'staff')->get();

        return view('sales.reports', compact('perStaff', 'overallTotal', 'overallQuantity', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with(['variants'])->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $variant = ProductVariant::findOrFail($request->product_variant_id);

                // Cek stok tersedia
                if ($variant->stock < $request->quantity) {
                    throw new \Exception('Stok tidak mencukupi! Stok tersedia: ' . $variant->stock);
                }

                $totalPrice = $request->quantity * $request->price_per_unit;

                // Simpan penjualan
                $sale = Sales::create([
                    'product_variant_id' => $request->product_variant_id,
                    'user_id' => Auth::id(),
                    'quantity' => $request->quantity,
                    'price_per_unit' => $request->price_per_unit,
                    'total_price' => $totalPrice,
                    'notes' => $request->notes,
                ]);

                // Kurangi stok otomatis
                $variant->decrement('stock', $request->quantity);

                // Catat aktivitas
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'activity' => "Menjual " . $request->quantity . " pcs " . $variant->product->name . " (Warna: " . $variant->color . ") - Total: Rp " . number_format($totalPrice, 0, ',', '.')
                ]);
            });

            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dicatat dan stok berkurang otomatis!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}