@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Input Penjualan Baru</h5>
                    <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Produk & Warna <span class="text-danger">*</span></label>
                            <select name="product_variant_id" id="product_variant_id" class="form-select @error('product_variant_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Produk & Warna --</option>
                                @foreach($products as $product)
                                    <optgroup label="{{ $product->name }} ({{ $product->category->name }})">
                                        @foreach($product->variants as $variant)
                                            <option value="{{ $variant->id }}" 
                                                    data-stock="{{ $variant->stock }}"
                                                    data-price="{{ $product->price }}"
                                                    {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->color }} - Stok: {{ $variant->stock }} pcs
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('product_variant_id') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                            <small class="text-muted mt-1 d-block" id="stock-info"></small>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jumlah (Pcs) <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       value="{{ old('quantity', 1) }}" min="1" required>
                                @error('quantity') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Harga per Unit (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price_per_unit" id="price_per_unit" 
                                           class="form-control @error('price_per_unit') is-invalid @enderror" 
                                           value="{{ old('price_per_unit') }}" min="0" step="100" required>
                                </div>
                                @error('price_per_unit') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="total_price_display" class="form-control" readonly 
                                       value="0" style="font-size: 1.2rem; font-weight: bold;">
                            </div>
                            <small class="text-muted">Otomatis dihitung dari jumlah × harga per unit</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3" placeholder="Tambahkan catatan jika diperlukan...">{{ old('notes') }}</textarea>
                            @error('notes') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> <strong>Perhatian:</strong> Setelah penjualan disimpan, stok akan otomatis berkurang sesuai jumlah yang dijual.
                        </div>

                        <div class="d-grid border-top pt-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Simpan Penjualan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantSelect = document.getElementById('product_variant_id');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price_per_unit');
        const totalDisplay = document.getElementById('total_price_display');
        const stockInfo = document.getElementById('stock-info');

        function updateStockInfo() {
            const selectedOption = variantSelect.options[variantSelect.selectedIndex];
            if (selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock);
                const price = parseFloat(selectedOption.dataset.price);
                
                stockInfo.innerHTML = `<span class="badge ${stock < 5 ? 'bg-warning' : 'bg-success'}">Stok tersedia: ${stock} pcs</span>`;
                
                // Set default price if empty
                if (!priceInput.value && price > 0) {
                    priceInput.value = price;
                }
                
                // Set max quantity
                quantityInput.max = stock;
                
                // Validate quantity
                if (parseInt(quantityInput.value) > stock) {
                    quantityInput.setCustomValidity('Jumlah melebihi stok tersedia!');
                } else {
                    quantityInput.setCustomValidity('');
                }
            } else {
                stockInfo.innerHTML = '';
                quantityInput.max = '';
            }
            calculateTotal();
        }

        function calculateTotal() {
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const total = quantity * price;
            totalDisplay.value = total.toLocaleString('id-ID');
        }

        variantSelect.addEventListener('change', updateStockInfo);
        quantityInput.addEventListener('input', function() {
            const selectedOption = variantSelect.options[variantSelect.selectedIndex];
            if (selectedOption.value) {
                const stock = parseInt(selectedOption.dataset.stock);
                if (parseInt(this.value) > stock) {
                    this.setCustomValidity('Jumlah melebihi stok tersedia!');
                } else {
                    this.setCustomValidity('');
                }
            }
            calculateTotal();
        });
        priceInput.addEventListener('input', calculateTotal);

        // Initial calculation
        updateStockInfo();
    });
</script>
@endpush

