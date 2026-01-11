@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0" style="color: #ffffff !important;">Manajemen Kategori Kain</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> + Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-left-success shadow" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-left-danger shadow" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="fw-bold">{{ $category->name }}</td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $category->products_count }}</span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

<style>
/* Responsive Design */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
    .table-responsive {
        font-size: 0.85rem;
    }
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    .btn-group .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
}

@media (max-width: 576px) {
    h2 {
        font-size: 1.25rem;
    }
    .table {
        font-size: 0.8rem;
    }
    .table th, .table td {
        padding: 0.5rem 0.375rem;
    }
    .badge {
        font-size: 0.75rem;
    }
}
</style>
@endsection
