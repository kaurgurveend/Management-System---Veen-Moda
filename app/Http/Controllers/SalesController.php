<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ActivityLog;
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
