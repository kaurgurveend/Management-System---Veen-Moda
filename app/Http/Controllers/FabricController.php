<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FabricController extends Controller
{
    /**
     * Menampilkan daftar semua kain (Monitoring Stok)
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']);

        // Fitur Pencarian berdasarkan nama kain
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('fabrics.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan form input kain baru (ADMIN & STAFF BISA AKSES)
     */
    public function create()
    {
        // HAPUS PENGECEKAN ADMIN - Biarkan Staff juga bisa akses
        $categories = Category::all();
        return view('fabrics.create', compact('categories'));
    }

    /**
     * Menyimpan kain baru beserta varian warnanya (ADMIN & STAFF BISA AKSES)
     */
    public function store(Request $request)
    {
        // HAPUS PENGECEKAN ADMIN - Biarkan Staff juga bisa simpan
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'colors'      => 'required|array|min:1',
            'stocks'      => 'required|array|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Simpan Data Utama
                $product = Product::create([
                    'category_id' => $request->category_id,
                    'name'        => $request->name,
                    'price'       => $request->price,
                ]);

                // Simpan Varian Warna
                foreach ($request->colors as $index => $colorName) {
                    if (!empty($colorName)) {
                        $product->variants()->create([
                            'color' => $colorName,
                            'stock' => $request->stocks[$index],
                        ]);
                    }
                }

                // Catat Aktivitas
                ActivityLog::create([
                    'user_id'  => Auth::id(),
                    'activity' => "Menambah kain baru: " . $request->name
                ]);
            });

            return redirect()->route('fabrics.index')->with('success', 'Kain berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit kain (Admin bisa edit semua, Staff hanya bisa edit stok)
     */
    public function edit($id)
    {
        // Jika bukan admin, arahkan ke halaman tambah stok (staff hanya boleh menambah stok)
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('fabrics.add-stock', $id);
        }

        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();
        $isAdmin = Auth::user()->role === 'admin';
        return view('fabrics.edit', compact('product', 'categories', 'isAdmin'));
    }

    /**
     * Mengupdate data kain dan stoknya (Admin bisa edit semua, Staff hanya bisa edit stok)
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $isAdmin = Auth::user()->role === 'admin';

        if ($isAdmin) {
            // Admin: Bisa edit semua
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'colors'      => 'required|array|min:1',
                'stocks'      => 'required|array|min:1',
            ]);

            try {
                DB::transaction(function () use ($request, $product) {
                    // Update data utama
                    $product->update([
                        'category_id' => $request->category_id,
                        'name'        => $request->name,
                        'price'       => $request->price,
                    ]);

                    // Update varian (hapus lama, simpan baru)
                    $product->variants()->delete();
                    foreach ($request->colors as $index => $colorName) {
                        if (!empty($colorName)) {
                            $product->variants()->create([
                                'color' => $colorName,
                                'stock' => $request->stocks[$index],
                            ]);
                        }
                    }

                    ActivityLog::create([
                        'user_id'  => Auth::id(),
                        'activity' => "Mengupdate kain: " . $product->name . " (Nama, Harga, Kategori, Warna, Stok)"
                    ]);
                });

                return redirect()->route('fabrics.index')->with('success', 'Data kain berhasil diperbarui!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
            }
        } else {
            // Staff: Hanya bisa edit stok
            $request->validate([
                'variants' => 'required|array|min:1',
                'variants.*.variant_id' => 'required|exists:product_variants,id',
                'variants.*.stock' => 'required|integer|min:0',
            ]);

            try {
                DB::transaction(function () use ($request, $product) {
                    foreach ($request->variants as $variantData) {
                        $variant = ProductVariant::findOrFail($variantData['variant_id']);
                        $oldStock = $variant->stock;
                        $newStock = $variantData['stock'] ?? 0;
                        
                        if ($newStock != $oldStock) {
                            $variant->update(['stock' => $newStock]);
                            
                            ActivityLog::create([
                                'user_id'  => Auth::id(),
                                'activity' => "Mengupdate stok " . $product->name . " (Warna: " . $variant->color . ") - Stok: " . $oldStock . " → " . $newStock
                            ]);
                        }
                    }
                });

                return redirect()->route('fabrics.index')->with('success', 'Stok berhasil diperbarui!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal update stok: ' . $e->getMessage());
            }
        }
    }

    /**
     * Menampilkan form tambah stok (UNTUK STAFF)
     */
    public function addStock($id)
    {
        // Hanya staff yang bisa akses (admin tidak perlu fitur ini)
        if (Auth::user()->role === 'admin') {
            return redirect()->route('fabrics.edit', $id)->with('info', 'Admin dapat menggunakan menu Edit untuk mengubah stok.');
        }

        $product = Product::with('variants')->findOrFail($id);
        return view('fabrics.add-stock', compact('product'));
    }

    /**
     * Menyimpan penambahan stok (UNTUK STAFF)
     */
    public function storeAddStock(Request $request, $id)
    {
        // Hanya staff yang bisa akses
        if (Auth::user()->role === 'admin') {
            abort(403, 'Admin harus menggunakan menu Edit untuk mengubah stok.');
        }

        $product = Product::with('variants')->findOrFail($id);

        $request->validate([
            'variants' => 'required|array|min:1',
            'variants.*.variant_id' => 'required|exists:product_variants,id',
            'variants.*.add_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $product) {
                foreach ($request->variants as $variantData) {
                    $variant = ProductVariant::findOrFail($variantData['variant_id']);
                    $addStock = $variantData['add_stock'] ?? 0;

                    if ($addStock > 0) {
                        $oldStock = $variant->stock;
                        $variant->increment('stock', $addStock);

                        ActivityLog::create([
                            'user_id'  => Auth::id(),
                            'activity' => "Menambah stok " . $addStock . " pcs untuk " . $product->name . " (Warna: " . $variant->color . ") - Stok: " . $oldStock . " → " . $variant->stock
                        ]);
                    }
                }
            });

            return redirect()->route('fabrics.index')->with('success', 'Stok berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah stok: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kain (Hanya Admin)
     */
    public function destroy($id)
    {
        // Pastikan hanya admin yang bisa hapus
        if (Auth::user()->role !== 'admin') {
            abort(403, 'AKSES DITOLAK. HANYA ADMIN YANG DAPAT MENGHAPUS PRODUK.');
        }

        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        ActivityLog::create([
            'user_id'  => Auth::id(),
            'activity' => "Menghapus kain: " . $name
        ]);

        return redirect()->route('fabrics.index')->with('success', 'Kain berhasil dihapus!');
    }
}