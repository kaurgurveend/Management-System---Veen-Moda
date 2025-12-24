<?php

namespace App\Http\Controllers;

use App\Models\SupplierShipment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplierShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SupplierShipment::query();

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        $shipments = $query->orderBy('received_date', 'desc')->paginate(15);

        return view('supplier-shipments.index', compact('shipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier-shipments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'quantity_pieces' => 'required|integer|min:1',
            'payment_status' => 'required|in:lunas,hutang',
            'due_date' => 'nullable|required_if:payment_status,hutang|date|after:today',
            'cost_price' => 'required|numeric|min:0',
            'additional_costs' => 'nullable|numeric|min:0',
            'received_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Set default for additional_costs if not provided
        if (!isset($validated['additional_costs'])) {
            $validated['additional_costs'] = 0;
        }

        $shipment = SupplierShipment::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Menambah barang masuk dari supplier: ' . $shipment->supplier_name . ' - ' . $shipment->product_name
        ]);

        return redirect()->route('supplier-shipments.index')
            ->with('success', 'Data barang masuk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shipment = SupplierShipment::findOrFail($id);
        return view('supplier-shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shipment = SupplierShipment::findOrFail($id);
        return view('supplier-shipments.edit', compact('shipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shipment = SupplierShipment::findOrFail($id);

        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'quantity_pieces' => 'required|integer|min:1',
            'payment_status' => 'required|in:lunas,hutang',
            'due_date' => 'nullable|required_if:payment_status,hutang|date',
            'cost_price' => 'required|numeric|min:0',
            'additional_costs' => 'nullable|numeric|min:0',
            'received_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        // Set default for additional_costs if not provided
        if (!isset($validated['additional_costs'])) {
            $validated['additional_costs'] = 0;
        }

        $shipment->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Mengupdate barang masuk: ' . $shipment->supplier_name . ' - ' . $shipment->product_name
        ]);

        return redirect()->route('supplier-shipments.index')
            ->with('success', 'Data barang masuk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shipment = SupplierShipment::findOrFail($id);
        
        // Delete payment proof if exists
        if ($shipment->payment_proof && Storage::disk('public')->exists($shipment->payment_proof)) {
            Storage::disk('public')->delete($shipment->payment_proof);
        }
        
        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Menghapus barang masuk: ' . $shipment->supplier_name . ' - ' . $shipment->product_name
        ]);

        $shipment->delete();

        return redirect()->route('supplier-shipments.index')
            ->with('success', 'Data barang masuk berhasil dihapus!');
    }

    /**
     * Upload payment proof and mark as paid
     */
    public function uploadPaymentProof(Request $request, string $id)
    {
        $shipment = SupplierShipment::findOrFail($id);

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Delete old payment proof if exists
        if ($shipment->payment_proof && Storage::disk('public')->exists($shipment->payment_proof)) {
            Storage::disk('public')->delete($shipment->payment_proof);
        }

        // Upload new payment proof
        $file = $request->file('payment_proof');
        $filename = 'payment_proof_' . $shipment->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment-proofs', $filename, 'public');

        // Update shipment status to lunas
        $shipment->update([
            'payment_proof' => $path,
            'payment_status' => 'lunas',
            'paid_at' => now()
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Upload bukti pembayaran untuk: ' . $shipment->supplier_name . ' - ' . $shipment->product_name
        ]);

        return redirect()->route('supplier-shipments.index')
            ->with('success', 'Bukti pembayaran berhasil diupload! Status otomatis berubah menjadi Lunas.');
    }

    /**
     * Delete payment proof and mark as debt again
     */
    public function deletePaymentProof(string $id)
    {
        $shipment = SupplierShipment::findOrFail($id);

        if ($shipment->payment_proof && Storage::disk('public')->exists($shipment->payment_proof)) {
            Storage::disk('public')->delete($shipment->payment_proof);
        }

        $shipment->update([
            'payment_proof' => null,
            'payment_status' => 'hutang',
            'paid_at' => null
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Menghapus bukti pembayaran untuk: ' . $shipment->supplier_name . ' - ' . $shipment->product_name
        ]);

        return redirect()->route('supplier-shipments.index')
            ->with('success', 'Bukti pembayaran berhasil dihapus! Status kembali menjadi Hutang.');
    }
}