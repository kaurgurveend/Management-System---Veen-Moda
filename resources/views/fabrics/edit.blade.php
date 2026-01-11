@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Kain: {{ $product->name }}</h5>
                    <a href="{{ route('fabrics.index') }}" class="btn btn-light btn-sm">Kembali ke Daftar</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fabrics.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($isAdmin)
                            {{-- Admin: Bisa edit semua --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jenis Kain</label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Nama Kain</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           placeholder="Contoh: Santilli Tipe A" value="{{ $product->name }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Harga per Pcs</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ $product->price }}" required>
                                    </div>
                                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3 text-secondary">Detail Warna & Stok</h5>
                            
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle"></i> <strong>Admin:</strong> Anda dapat mengubah semua data produk termasuk nama, harga, kategori, warna, dan stok.
                            </div>
                            
                            <div id="wrapper-warna">
                                @foreach($product->variants as $index => $variant)
                                <div class="row g-3 mb-3 baris-warna">
                                    <div class="col-md-6">
                                        <label class="form-label">Warna</label>
                                        <input type="text" name="colors[]" class="form-control" placeholder="Masukan Warna (Contoh: Navy)" value="{{ $variant->color }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Jumlah Stok (Pcs)</label>
                                        <input type="number" name="stocks[]" class="form-control" value="{{ $variant->stock }}" min="0" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger w-100 btn-hapus" {{ $loop->first ? 'style=display:none;' : '' }}>Hapus</button>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mb-4">
                                <button type="button" id="btn-tambah-baris" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-plus"></i> + Tambah Warna Lain
                                </button>
                            </div>
                        @else
                            {{-- Staff: Hanya bisa edit stok --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jenis Kain</label>
                                    <input type="text" class="form-control bg-light" value="{{ $product->category->name }}" readonly>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Nama Kain</label>
                                    <input type="text" class="form-control bg-light" value="{{ $product->name }}" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Harga per Pcs</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control bg-light" value="{{ number_format($product->price, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3 text-secondary">Detail Warna & Stok</h5>
                            
                            <div class="alert alert-warning mb-3">
                                <i class="bi bi-exclamation-triangle"></i> <strong>Staff:</strong> Anda hanya dapat mengubah jumlah stok. Untuk mengubah nama, harga, kategori, atau warna produk, hubungi admin.
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40%;">Warna</th>
                                            <th style="width: 30%;">Stok Saat Ini</th>
                                            <th style="width: 30%;">Stok Baru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $index => $variant)
                                        <tr>
                                            <td>
                                                <strong>{{ $variant->color }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary fs-6">{{ $variant->stock }} pcs</span>
                                            </td>
                                            <td>
                                                <input type="hidden" name="variants[{{ $index }}][variant_id]" value="{{ $variant->id }}">
                                                <input type="number" 
                                                       name="variants[{{ $index }}][stock]" 
                                                       class="form-control" 
                                                       value="{{ $variant->stock }}" 
                                                       min="0" 
                                                       required>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="d-grid border-top pt-3">
                            <button type="submit" class="btn btn-success btn-lg">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
    .col-md-4, .col-md-5, .col-md-3, .col-md-6, .col-md-2 {
        width: 100% !important;
        max-width: 100% !important;
    }
    .d-flex.align-items-end {
        margin-top: 1rem;
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
@endsection

@if($isAdmin)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('wrapper-warna');
        const btnTambah = document.getElementById('btn-tambah-baris');

        if (!wrapper || !btnTambah) return;

        // Fungsi Tambah Baris
        btnTambah.onclick = function() {
            // Ambil elemen baris pertama untuk di-copy
            const barisBaru = document.querySelector('.baris-warna').cloneNode(true);
            
            // Bersihkan inputan di baris baru
            const inputs = barisBaru.querySelectorAll('input');
            inputs[0].value = ''; // Kosongkan nama warna
            inputs[1].value = '0'; // Reset stok jadi 0
            
            // Munculkan tombol hapus di baris baru
            const btnHapus = barisBaru.querySelector('.btn-hapus');
            btnHapus.style.display = 'block';

            // Tambahkan baris baru ke dalam wrapper
            wrapper.appendChild(barisBaru);

            // Fokuskan ke input warna yang baru dibuat
            inputs[0].focus();
        };

        // Fungsi Hapus Baris (Gunakan Event Delegation)
        wrapper.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-hapus')) {
                const baris = e.target.closest('.baris-warna');
                baris.remove();
            }
        });
    });
</script>
@endpush
@endif