@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-plus-square"></i> Tambah Stok - {{ $product->name }}</h2>
            <p class="mb-0 text-muted">Tambahkan stok untuk setiap varian warna (hanya staff yang dapat mengakses fitur ini)</p>
        </div>
        <a href="{{ route('fabrics.index') }}" class="btn btn-light">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('fabrics.store-add-stock', $product->id) }}" method="POST">
                @csrf

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Warna</th>
                                <th>Stok Saat Ini</th>
                                <th>Tambah Stok (pcs)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->variants as $index => $variant)
                                <tr>
                                    <td><strong>{{ $variant->color }}</strong></td>
                                    <td>{{ $variant->stock }} pcs</td>
                                    <td>
                                        <input type="hidden" name="variants[{{ $index }}][variant_id]" value="{{ $variant->id }}">
                                        <input type="number" name="variants[{{ $index }}][add_stock]" class="form-control" value="0" min="0">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-grid mt-3">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan Tambahan Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
