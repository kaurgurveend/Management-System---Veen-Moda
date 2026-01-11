@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="mb-1 text-white"><i class="bi bi-pencil"></i> Edit Barang Masuk</h2>
        <p class="text-white mb-0">Update data pembelian barang dari supplier</p>
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
                            <div class="col-md-12">
                                <label class="form-label fw-bold"><i class="bi bi-receipt"></i> Total Harga di Nota <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white fw-bold">Rp</span>
                                    <input type="number" name="invoice_total" id="invoice_total" class="form-control form-control-lg @error('invoice_total') is-invalid @enderror" value="{{ old('invoice_total', $shipment->invoice_total ?? '') }}" min="0" step="0.01" required style="font-size: 1.1rem; font-weight: 600;">
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Masukkan total harga yang tertera di nota/invoice supplier</small>
                                @error('invoice_total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select name="payment_status" id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                        <option value="">Pilih Status</option>
                                        <option value="lunas" {{ old('payment_status', $shipment->payment_status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="hutang" {{ old('payment_status', $shipment->payment_status) == 'hutang' ? 'selected' : '' }}>Hutang</option>
                                    </select>
                                    <i class="bi bi-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 pe-none" style="color: #6c757d;"></i>
                                </div>
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
            <div class="card border-primary mb-3" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                <div class="card-body">
                    <h6 class="card-title text-primary fw-bold"><i class="bi bi-calculator"></i> Ringkasan</h6>
                    <hr class="my-2">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Total Modal</td>
                            <td class="text-end"><strong class="text-dark" id="summary_total_modal">Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total HPP</td>
                            <td class="text-end"><strong class="text-dark" id="summary_total_hpp">Rp {{ number_format($shipment->total_hpp, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Biaya Tambahan</td>
                            <td class="text-end text-info"><strong>Rp {{ number_format($shipment->additional_costs * $shipment->quantity_pieces, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                    <hr class="my-2">
                    <div class="mb-0">
                        <small class="text-primary fw-bold d-block"><i class="bi bi-receipt-cutoff"></i> Total di Nota</small>
                        <strong class="text-primary" style="font-size: 1.3rem;" id="summary_invoice_total">Rp {{ number_format($shipment->invoice_total ?? 0, 0, ',', '.') }}</strong>
                    </div>
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
        const quantity = parseFloat(document.querySelector('input[name="quantity_pieces"]').value) || 0;
        const invoiceTotal = parseFloat(document.getElementById('invoice_total').value) || 0;
        
        const hpp = costPrice + additionalCosts;
        document.getElementById('hpp_display').value = hpp.toLocaleString('id-ID');
        
        // Update summary
        const totalModal = costPrice * quantity;
        const totalHpp = hpp * quantity;
        
        document.getElementById('summary_total_modal').textContent = 'Rp ' + totalModal.toLocaleString('id-ID');
        document.getElementById('summary_total_hpp').textContent = 'Rp ' + totalHpp.toLocaleString('id-ID');
        document.getElementById('summary_invoice_total').textContent = 'Rp ' + invoiceTotal.toLocaleString('id-ID');
    }

    // Update summary when invoice total changes
    document.getElementById('invoice_total').addEventListener('input', function() {
        const invoiceTotal = parseFloat(this.value) || 0;
        document.getElementById('summary_invoice_total').textContent = 'Rp ' + invoiceTotal.toLocaleString('id-ID');
    });

    document.getElementById('cost_price').addEventListener('input', calculateHPP);
    document.getElementById('additional_costs').addEventListener('input', calculateHPP);
    document.querySelector('input[name="quantity_pieces"]').addEventListener('input', calculateHPP);

    // Initial calculation
    calculateHPP();
</script>
@endpush

<style>
/* Card Styling - Light Background */
.card {
    background-color: #ffffff !important;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-body {
    color: #212529;
}

.form-label {
    color: #212529;
    font-weight: 500;
}

.form-control, .form-select {
    background-color: #ffffff;
    color: #212529;
    border: 1px solid #ced4da;
}

.form-control:focus, .form-select:focus {
    background-color: #ffffff;
    color: #212529;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.input-group-text {
    background-color: #e9ecef;
    color: #495057;
    border: 1px solid #ced4da;
}

/* Invoice Total Highlight */
#invoice_total {
    border-left: 3px solid #007bff;
}

/* Summary Card */
.card.border-primary {
    border-width: 2px !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .row > [class*="col-"] {
        margin-bottom: 1rem;
    }
    .col-md-6, .col-lg-8, .col-lg-4 {
        width: 100% !important;
        max-width: 100% !important;
    }
    h2 {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    h2 {
        font-size: 1.25rem;
    }
    .form-label {
        font-size: 0.9rem;
    }
    .input-group-text {
        font-size: 0.85rem;
        padding: 0.375rem 0.5rem;
    }
    .input-group-lg .form-control {
        font-size: 1rem;
    }
}
</style>
@endsection