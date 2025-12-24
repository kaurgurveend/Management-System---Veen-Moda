@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="mb-1"><i class="bi bi-pencil"></i> Edit Barang Masuk</h2>
        <p class="text-muted mb-0">Update data pembelian barang dari supplier</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('supplier-shipments.update', $shipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name', $shipment->supplier_name) }}" required>
                                @error('supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $shipment->product_name) }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jumlah (Pieces) <span class="text-danger">*</span></label>
                                <input type="number" name="quantity_pieces" class="form-control @error('quantity_pieces') is-invalid @enderror" value="{{ old('quantity_pieces', $shipment->quantity_pieces) }}" min="1" required>
                                @error('quantity_pieces')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Terima <span class="text-danger">*</span></label>
                                <input type="date" name="received_date" class="form-control @error('received_date') is-invalid @enderror" value="{{ old('received_date', $shipment->received_date->format('Y-m-d')) }}" required>
                                @error('received_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Modal dari Supplier (per pcs) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $shipment->cost_price) }}" min="0" step="0.01" required>
                                </div>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Biaya Lain-lain (per pcs)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="additional_costs" id="additional_costs" class="form-control @error('additional_costs') is-invalid @enderror" value="{{ old('additional_costs', $shipment->additional_costs) }}" min="0" step="0.01">
                                </div>
                                @error('additional_costs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">HPP (Auto Calculate)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="hpp_display" class="form-control bg-light" value="{{ number_format($shipment->hpp, 0, ',', '.') }}" readonly>
                                </div>
                                <small class="text-muted">HPP = Modal + Biaya Lain</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_status" id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                    <option value="">Pilih Status</option>
                                    <option value="lunas" {{ old('payment_status', $shipment->payment_status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="hutang" {{ old('payment_status', $shipment->payment_status) == 'hutang' ? 'selected' : '' }}>Hutang</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="due_date_wrapper" style="display: {{ old('payment_status', $shipment->payment_status) == 'hutang' ? 'block' : 'none' }};">
                                <label class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', $shipment->due_date ? $shipment->due_date->format('Y-m-d') : '') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $shipment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('supplier-shipments.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light mb-3">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-info-circle"></i> Informasi</h6>
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Total Modal</td>
                            <td class="text-end"><strong>Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total HPP</td>
                            <td class="text-end"><strong>Rp {{ number_format($shipment->total_hpp, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Biaya Tambahan</td>
                            <td class="text-end text-info"><strong>Rp {{ number_format($shipment->additional_costs * $shipment->quantity_pieces, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($shipment->payment_status === 'hutang')
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="card-title text-warning"><i class="bi bi-upload"></i> Upload Bukti Bayar</h6>
                    <p class="small text-muted mb-3">Upload bukti transfer untuk mengubah status menjadi Lunas</p>
                    
                    <form action="{{ route('supplier-shipments.upload-payment-proof', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="payment_proof" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                            <small class="text-muted">JPG, PNG, PDF (Max: 2MB)</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-upload"></i> Upload & Tandai Lunas
                        </button>
                    </form>
                </div>
            </div>
            @elseif($shipment->payment_proof)
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="card-title text-success"><i class="bi bi-check-circle"></i> Bukti Pembayaran</h6>
                    <p class="small text-muted">Dibayar: {{ $shipment->paid_at ? $shipment->paid_at->format('d/m/Y H:i') : '-' }}</p>
                    <div class="d-grid gap-2">
                        <a href="{{ Storage::url($shipment->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-file-earmark-check"></i> Lihat Bukti
                        </a>
                        <form action="{{ route('supplier-shipments.delete-payment-proof', $shipment->id) }}" method="POST" onsubmit="return confirm('Hapus bukti pembayaran? Status akan kembali ke Hutang.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Hapus Bukti
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Payment status handler
    document.getElementById('payment_status').addEventListener('change', function() {
        const dueWrapper = document.getElementById('due_date_wrapper');
        const dueInput = document.getElementById('due_date');
        
        if (this.value === 'hutang') {
            dueWrapper.style.display = 'block';
            dueInput.required = true;
        } else {
            dueWrapper.style.display = 'none';
            dueInput.required = false;
            dueInput.value = '';
        }
    });

    // HPP Calculator
    function calculateHPP() {
        const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
        const additionalCosts = parseFloat(document.getElementById('additional_costs').value) || 0;
        const hpp = costPrice + additionalCosts;
        document.getElementById('hpp_display').value = hpp.toLocaleString('id-ID');
    }

    document.getElementById('cost_price').addEventListener('input', calculateHPP);
    document.getElementById('additional_costs').addEventListener('input', calculateHPP);

    // Initial calculation
    calculateHPP();
</script>
@endpush
@endsection