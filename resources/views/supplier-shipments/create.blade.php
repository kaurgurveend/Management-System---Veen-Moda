@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="mb-1"><i class="bi bi-plus-circle"></i> Tambah Barang Masuk</h2>
        <p class="text-muted mb-0">Input data pembelian barang dari supplier</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('supplier-shipments.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" value="{{ old('supplier_name') }}" required>
                                @error('supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jumlah (Pieces) <span class="text-danger">*</span></label>
                                <input type="number" name="quantity_pieces" class="form-control @error('quantity_pieces') is-invalid @enderror" value="{{ old('quantity_pieces') }}" min="1" required>
                                @error('quantity_pieces')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Terima <span class="text-danger">*</span></label>
                                <input type="date" name="received_date" class="form-control @error('received_date') is-invalid @enderror" value="{{ old('received_date', date('Y-m-d')) }}" required>
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
                                    <input type="number" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price') }}" min="0" step="0.01" required>
                                </div>
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Biaya Lain-lain (per pcs)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="additional_costs" id="additional_costs" class="form-control @error('additional_costs') is-invalid @enderror" value="{{ old('additional_costs', 0) }}" min="0" step="0.01">
                                </div>
                                @error('additional_costs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">HPP (Auto Calculate)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="hpp_display" class="form-control bg-light" value="0" readonly>
                                </div>
                                <small class="text-muted">HPP = Modal + Biaya Lain</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_status" id="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                                    <option value="">Pilih Status</option>
                                    <option value="lunas" {{ old('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="hutang" {{ old('payment_status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6" id="due_date_wrapper" style="display: none;">
                                <label class="form-label">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
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
                                <i class="bi bi-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-info-circle"></i> Panduan</h6>
                    <ul class="small mb-0">
                        <li>Isi semua field yang bertanda <span class="text-danger">*</span></li>
                        <li><strong>Modal dari Supplier:</strong> Harga beli dari supplier</li>
                        <li><strong>Biaya Lain-lain:</strong> Ongkir, pajak, dll (opsional)</li>
                        <li><strong>HPP:</strong> Otomatis dihitung = Modal + Biaya Lain</li>
                        <li>Jika status hutang, tanggal jatuh tempo wajib diisi</li>
                        <li>Notifikasi akan muncul 6 minggu sebelum jatuh tempo</li>
                    </ul>
                </div>
            </div>
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

    // Trigger on page load if old value exists
    if (document.getElementById('payment_status').value === 'hutang') {
        document.getElementById('due_date_wrapper').style.display = 'block';
        document.getElementById('due_date').required = true;
    }

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