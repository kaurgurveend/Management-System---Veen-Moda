@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-cart-check"></i> Daftar Penjualan</h2>
            <p class="text-muted mb-0">Riwayat penjualan dan pengurangan stok otomatis</p>
        </div>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Penjualan Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Penjualan</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #6366f1 !important;">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Terjual</h6>
                    <h3 class="mb-0">{{ number_format($totalQuantity, 0, ',', '.') }} pcs</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Penjualan Hari Ini</h6>
                    <h3 class="mb-0">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('sales.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Filter Produk</label>
                    <select name="product_id" class="form-select">
                        <option value="">-- Semua Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary me-2"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Penjualan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Warna</th>
                            <th>Jumlah</th>
                            <th>Harga/Unit</th>
                            <th>Total</th>
                            <th>Staff</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>
                                    <small class="text-muted">
                                        {{ $sale->created_at->format('d M Y') }}<br>
                                        <span class="text-muted">{{ $sale->created_at->format('H:i') }}</span>
                                    </small>
                                </td>
                                <td>{{ $sale->productVariant->product->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $sale->productVariant->color }}</span>
                                </td>
                                <td><strong>{{ $sale->quantity }}</strong> pcs</td>
                                <td>Rp {{ number_format($sale->price_per_unit, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</strong></td>
                                <td>{{ $sale->user->name }}</td>
                                <td>
                                    @if($sale->notes)
                                        <small class="text-muted">{{ Str::limit($sale->notes, 30) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">Belum ada data penjualan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

