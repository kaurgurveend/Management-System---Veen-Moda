@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-box-seam"></i> Daftar Stok Kain</h2>
            <p class="text-muted mb-0">Monitoring stok kain real-time</p>
        </div>
        <a href="{{ route('fabrics.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Input Kain Baru
        </a>
    </div>

    <!-- Legend Indikator Stok -->
    <div class="card mb-3 border-0 shadow-sm">
        <div class="card-body py-2">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <small class="text-muted fw-bold">Indikator Stok:</small>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success">≥ 5 pcs</span>
                    <small class="text-muted">Aman</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark">2-4 pcs</span>
                    <small class="text-muted">Rendah (Kuning)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger">< 2 pcs</span>
                    <small class="text-muted">Kritis (Merah)</small>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('fabrics.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama kain..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <div class="position-relative">
                        <select name="category_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="bi bi-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 pe-none" style="color: #6c757d;"></i>
                    </div>
                </div>
                <div class="col-md-5">
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('fabrics.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Product List with Variants -->
    <div class="row fabrics-list">
        @forelse($products as $product)
        <div class="col-12 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Product Info -->
                        <div class="col-md-3">
                            <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                            <h5 class="mb-1">{{ $product->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-tag"></i> Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Variants Grid -->
                        <div class="col-md-7">
                            <div class="d-flex align-items-center mb-2">
                                <small class="text-muted me-2">
                                    <i class="bi bi-palette"></i> {{ $product->variants->count() }} Warna
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-box"></i> Total Stok: <strong>{{ $product->variants->sum('stock') }}</strong>
                                </small>
                            </div>
                            
                            <div class="row g-2">
                                @foreach($product->variants as $variant)
                                <div class="col-auto">
                                    <div class="variant-card p-2 border rounded text-center {{ $variant->stock < 2 ? 'border-danger bg-danger bg-opacity-10' : ($variant->stock < 5 ? 'border-warning bg-warning bg-opacity-10' : 'bg-light') }}" 
                                         style="min-width: 85px;"
                                         title="{{ $variant->color }} - {{ $variant->stock }} pcs">
                                        <div class="d-flex flex-column">
                                            <small class="text-uppercase fw-semibold" style="font-size: 0.7rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $variant->color }}
                                            </small>
                                            <span class="badge {{ $variant->stock < 2 ? 'bg-danger' : ($variant->stock < 5 ? 'bg-warning text-dark' : 'bg-success') }} mt-1">
                                                {{ $variant->stock }} pcs
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if($product->variants->count() > 8)
                            <button class="btn btn-sm btn-link mt-2 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#moreColors{{ $product->id }}">
                                <small><i class="bi bi-chevron-down"></i> Lihat Semua Warna</small>
                            </button>
                            <div class="collapse" id="moreColors{{ $product->id }}">
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Warna</th>
                                                <th>Stok</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variants as $variant)
                                            <tr>
                                                <td>{{ $variant->color }}</td>
                                                <td><strong>{{ $variant->stock }}</strong> pcs</td>
                                                <td>
                                                    @if($variant->stock < 2)
                                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Kritis</span>
                                                    @elseif($variant->stock < 5)
                                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle"></i> Rendah</span>
                                                    @else
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aman</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="col-md-2 text-end">
                            <div class="d-flex gap-2 justify-content-end actions">
                                    @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('fabrics.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('fabrics.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kain ini beserta semua variannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada data kain di gudang</p>
                    <a href="{{ route('fabrics.create') }}" class="btn btn-primary btn-sm mt-3">
                        <i class="bi bi-plus-circle"></i> Tambah Kain Pertama
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="custom-pagination-container">
        <div class="pagination-info mb-3">
            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
        </div>
        <div>
            {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @endif
</div>

<style>
/* Variant card micro-interactions */
.variant-card {
    transition: all 0.18s ease;
}
.variant-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* Fabrics list layout tweaks */
.fabrics-list .card {
    border-radius: 10px;
    margin-bottom: 0.9rem;
}
.fabrics-list .card .card-body {
    padding: 0.9rem 1.1rem;
}
.fabrics-list .col-md-3 h5 { margin-bottom: 0.2rem; font-size: 1.05rem; font-weight: 700; }
.fabrics-list .col-md-3 p { margin-bottom: 0; color: #6b7280; }

/* Better variant layout */
.fabrics-list .row.g-2 { margin-top: 0; }
.fabrics-list .variant-card { min-width: 70px; max-width: 120px; padding: 0.45rem; }
.fabrics-list .variant-card small { font-size: 0.65rem; max-width: 86px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.fabrics-list .variant-card .badge { font-size: 0.72rem; padding: 0.28rem 0.45rem; }

/* Actions alignment */
.actions { align-items: center; }
.actions .btn { min-width: 0; padding: 0.35rem 0.5rem; }

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .fabrics-list .col-md-3, .fabrics-list .col-md-7, .fabrics-list .col-md-2 { width: 100%; }
    .fabrics-list .card .card-body { padding: 0.7rem; }
    .fabrics-list .variant-card { min-width: 60px; padding: 0.35rem; }
    .actions { justify-content: flex-start; gap: 0.5rem; }
}

/* Keep our previous scoped button & SVG fixes */
#content .btn {
    padding: 0.35rem 0.6rem !important;
    font-size: 0.9rem !important;
    border-radius: 0.4rem !important;
}

#content .btn-sm { padding: 0.35rem 0.55rem !important; font-size: 0.82rem !important; }

#content .w-\[448px\],
#content svg[viewBox="0 0 440 376"],
#content svg[viewBox="0 0 438 104"] {
    display: none !important;
}

/* Custom Pagination Container - FIXED VERSION */
.custom-pagination-container {
    margin-top: 2rem;
    padding: 0;
}

/* Pagination info styling */
.custom-pagination-container .pagination-info {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

/* Hide default Laravel pagination info if any */
.custom-pagination-container nav > div:first-child {
    display: none !important;
}

/* Target Laravel default pagination wrapper */
.custom-pagination-container nav { 
    background: transparent !important; 
    padding: 0 !important; 
    border: none !important; 
    box-shadow: none !important; 
    display: block !important;
}

.custom-pagination-container .pagination { 
    margin: 0 !important; 
    display: inline-flex !important; 
    gap: 0.4rem; 
    justify-content: flex-start !important;
}

.custom-pagination-container .page-link {
    background: rgba(255,255,255,0.95);
    color: #1e3c72;
    border: none;
    padding: 0.45rem 0.85rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.custom-pagination-container .page-link:hover { 
    transform: translateY(-2px); 
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.15);
}

.custom-pagination-container .page-item.active .page-link { 
    background: #1e3c72; 
    color: #ffffff; 
    box-shadow: none; 
}

.custom-pagination-container .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Mobile responsive */
@media (max-width: 576px) {
    .custom-pagination-container .pagination {
        flex-wrap: wrap;
    }
}
</style>
@endsection