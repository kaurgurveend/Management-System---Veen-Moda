@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Kategori Kain Baru</h5>
                    <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kategori</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Contoh: Katun, Sutra, Linen" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="4" placeholder="Deskripsi kategori...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-grid border-top pt-3">
                            <button type="submit" class="btn btn-success btn-lg">Simpan Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
