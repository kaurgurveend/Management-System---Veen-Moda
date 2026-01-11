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

                            <div class="searchable-select">
                                <div class="form-control d-flex justify-content-between align-items-center" id="product_select_control" tabindex="0" role="combobox" aria-expanded="false">
                                    <span id="product_select_display">-- Pilih Produk & Warna --</span>
                                    <i class="bi bi-caret-down-fill"></i>
                                </div>

                                <div class="searchable-dropdown d-none shadow-sm bg-white border mt-1 p-3" id="product_dropdown" style="max-height: 320px; overflow: auto;">
                                    <input type="search" id="product_search" class="form-control mb-2" placeholder="Ketikan produk atau warna untuk mencari...">
                                    <div id="product_list" class="list-group">
                                        {{-- Will be populated by JS --}}
                                    </div>
                                    <div id="product_no_results" class="text-muted text-center py-3 d-none">Tidak ada hasil</div>
                                </div>

                                <!-- Hidden select kept for form submission and compatibility -->
                                <select name="product_variant_id" id="product_variant_id" class="form-select d-none @error('product_variant_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Produk & Warna --</option>
                                    @foreach($products as $product)
                                        <optgroup label="{{ $product->name }} ({{ $product->category->name }})">
                                            @foreach($product->variants as $variant)
                                                <option value="{{ $variant->id }}" 
                                                        data-stock="{{ $variant->stock }}"
                                                        data-price="{{ $product->price }}"
                                                        data-product="{{ $product->name }}"
                                                        {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                                    {{ $variant->color }} - Stok: {{ $variant->stock }} pcs
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

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

        // Build dropdown items (integrated searchable dropdown)
        const productDropdown = document.getElementById('product_dropdown');
        const productList = document.getElementById('product_list');
        const productSearchInput = document.getElementById('product_search');
        const productControl = document.getElementById('product_select_control');
        const productDisplay = document.getElementById('product_select_display');
        const noResults = document.getElementById('product_no_results');

        // Parse original optgroups/options from hidden select
        const groups = [];
        Array.from(variantSelect.children).forEach(node => {
            if (node.tagName.toLowerCase() === 'optgroup') {
                const label = node.label || '';
                const items = Array.from(node.children).map(opt => ({
                    value: opt.value,
                    text: opt.textContent.trim(),
                    product: opt.dataset.product || '',
                    stock: opt.dataset.stock || '',
                    price: opt.dataset.price || ''
                }));
                groups.push({ label, items });
            }
        });

        function buildList(filter = '') {
            productList.innerHTML = '';
            const q = filter.trim().toLowerCase();
            let totalMatches = 0;

            groups.forEach(group => {
                const matched = group.items.filter(i => {
                    if (!q) return true;
                    return i.product.toLowerCase().includes(q) || i.text.toLowerCase().includes(q);
                });

                if (matched.length === 0) return;

                const groupHeader = document.createElement('div');
                groupHeader.className = 'fw-bold small text-muted mb-1 mt-2';
                groupHeader.textContent = group.label;
                productList.appendChild(groupHeader);

                matched.forEach(item => {
                    totalMatches++;
                    const div = document.createElement('button');
                    div.type = 'button';
                    div.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    div.dataset.value = item.value;
                    div.dataset.stock = item.stock;
                    div.dataset.price = item.price;
                    div.textContent = item.text;

                    div.addEventListener('click', function() {
                        // select option in hidden select
                        variantSelect.value = this.dataset.value;
                        variantSelect.dispatchEvent(new Event('change'));
                        // update display
                        productDisplay.textContent = this.textContent;
                        hideDropdown();
                    });

                    productList.appendChild(div);
                });
            });

            if (totalMatches === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }

        function showDropdown() {
            productDropdown.classList.remove('d-none');
            productControl.setAttribute('aria-expanded', 'true');
            // focus search input
            productSearchInput.focus();
        }

        function hideDropdown() {
            productDropdown.classList.add('d-none');
            productControl.setAttribute('aria-expanded', 'false');
        }

        // Toggle on control click
        productControl.addEventListener('click', function(e) {
            if (productDropdown.classList.contains('d-none')) {
                buildList('');
                showDropdown();
            } else {
                hideDropdown();
            }
        });

        // Clicking outside closes dropdown
        document.addEventListener('click', function(e) {
            if (!productControl.contains(e.target) && !productDropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        // Filter as user types (debounce)
        let dropdownFilterTimeout = null;
        productSearchInput.addEventListener('input', function() {
            clearTimeout(dropdownFilterTimeout);
            dropdownFilterTimeout = setTimeout(() => {
                buildList(this.value);
            }, 120);
        });

        // Enter selects first visible item
        productSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const first = productList.querySelector('.list-group-item');
                if (first) first.click();
            }
            if (e.key === 'Escape') {
                hideDropdown();
                productControl.focus();
            }
        });

        // When hidden select changes (programmatic), update UI and stock info
        variantSelect.addEventListener('change', function() {
            const selected = variantSelect.options[variantSelect.selectedIndex];
            if (selected && selected.value) {
                productDisplay.textContent = selected.dataset.product + ' — ' + selected.text;
                // ensure price default and stock info update
                updateStockInfo();
            } else {
                productDisplay.textContent = '-- Pilih Produk & Warna --';
                stockInfo.innerHTML = '';
            }
        });

        // Initialize display if there's a preselected value
        if (variantSelect.value) {
            const sel = variantSelect.options[variantSelect.selectedIndex];
            productDisplay.textContent = sel.dataset.product + ' — ' + sel.textContent.trim();
        }

        // Initial build (hidden)
        buildList('');

        // Initial calculation
        updateStockInfo();
    });
</script>
@endpush

<style>
/* Responsive Design */
@media (max-width: 768px) {
    .card-header.d-flex {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    .card-header .btn {
        width: 100%;
    }
    .row > [class*="col-"] {
        margin-bottom: 1rem;
    }
    .col-md-6, .col-md-4 {
        width: 100% !important;
        max-width: 100% !important;
    }
}

@media (max-width: 576px) {
    h5 {
        font-size: 1rem;
    }
    .form-label {
        font-size: 0.9rem;
    }
    .input-group-text {
        font-size: 0.85rem;
        padding: 0.375rem 0.5rem;
    }
}
</style>

