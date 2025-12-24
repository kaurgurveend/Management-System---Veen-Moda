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
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10);

        return view('fabrics.index', compact('products'));
    }

    /**
     * Menampilkan form input kain baru
     */
    public function create()
    {
        $categories = Category::all();
        return view('fabrics.create', compact('categories'));
    }

    /**
     * Menyimpan kain baru beserta varian warnanya
     */
    public function store(Request $request)
    {
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
     * Menampilkan form edit kain
     */
    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();
        return view('fabrics.edit', compact('product', 'categories'));
    }

    /**
     * Mengupdate data kain dan stoknya
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        // Validasi berbeda untuk admin vs staff
        if ($user->role === 'admin') {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'colors'      => 'required|array|min:1',
                'stocks'      => 'required|array|min:1',
            ]);
        } else {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name'        => 'required|string|max:255',
                'price'       => 'required|numeric|min:0',
                'colors'      => 'required|array|min:1',
                'current_stocks' => 'required|array|min:1',
                'add_stocks'  => 'required|array|min:1',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $product, $user) {
                // Update data utama
                $product->update([
                    'category_id' => $request->category_id,
                    'name'        => $request->name,
                    'price'       => $request->price,
                ]);

                if ($user->role === 'admin') {
                    // Admin: Update langsung (hapus lama, simpan baru)
                    $product->variants()->delete();
                    foreach ($request->colors as $index => $colorName) {
                        $product->variants()->create([
                            'color' => $colorName,
                            'stock' => $request->stocks[$index],
                        ]);
                    }
                    ActivityLog::create([
                        'user_id'  => Auth::id(),
                        'activity' => "Mengupdate kain: " . $product->name
                    ]);
                } else {
                    // Staff: Hanya tambah stok (increment), tidak bisa edit langsung
                    $product->variants()->delete();
                    foreach ($request->colors as $index => $colorName) {
                        $currentStock = $request->current_stocks[$index] ?? 0;
                        $addStock = $request->add_stocks[$index] ?? 0;
                        $newStock = $currentStock + $addStock;

                        $product->variants()->create([
                            'color' => $colorName,
                            'stock' => $newStock,
                        ]);

                        if ($addStock > 0) {
                            ActivityLog::create([
                                'user_id'  => Auth::id(),
                                'activity' => "Menambah stok " . $addStock . " pcs untuk " . $product->name . " (Warna: " . $colorName . ")"
                            ]);
                        }
                    }
                    ActivityLog::create([
                        'user_id'  => Auth::id(),
                        'activity' => "Mengupdate data kain: " . $product->name
                    ]);
                }
            });

            return redirect()->route('fabrics.index')->with('success', 'Data kain diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus kain (Hanya Admin)
     */
    public function destroy($id)
    {
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