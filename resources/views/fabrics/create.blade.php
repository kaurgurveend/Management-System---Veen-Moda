@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Input Kain Baru</h5>
                    <a href="{{ route('fabrics.index') }}" class="btn btn-light btn-sm">Kembali ke Daftar</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fabrics.store') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jenis Kain</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Nama Kain</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Contoh: Santilli Tipe A" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Harga per Pcs</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price') }}" required>
                                </div>
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3 text-secondary">Detail Warna & Stok</h5>
                        
                        <div id="wrapper-warna">
                            <div class="row g-3 mb-3 baris-warna">
                                <div class="col-md-6">
                                    <label class="form-label">Warna</label>
                                    <input type="text" name="colors[]" class="form-control" placeholder="Masukan Warna (Contoh: Navy)" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Jumlah Stok (Pcs)</label>
                                    <input type="number" name="stocks[]" class="form-control" value="0" min="0" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-danger w-100 btn-hapus" style="display:none;">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="button" id="btn-tambah-baris" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus"></i> + Tambah Warna Lain
                            </button>
                        </div>

                        <div class="d-grid border-top pt-3">
                            <button type="submit" class="btn btn-success btn-lg">Simpan Data Kain</button>
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
        const wrapper = document.getElementById('wrapper-warna');
        const btnTambah = document.getElementById('btn-tambah-baris');

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