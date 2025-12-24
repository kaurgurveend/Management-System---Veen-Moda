@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <!-- Notifikasi Stok Rendah/Kritis -->
    @if($criticalStockCount > 0 || $lowStockCount > 0)
    <div class="alert alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" 
         style="border-left: 4px solid {{ $criticalStockCount > 0 ? '#dc3545' : '#ffc107' }} !important; background: {{ $criticalStockCount > 0 ? '#fff5f5' : '#fffbf0' }};">
        <div class="d-flex align-items-start">
            <div class="me-3">
                @if($criticalStockCount > 0)
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 2rem; color: #dc3545;"></i>
                @else
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 2rem; color: #ffc107;"></i>
                @endif
            </div>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2" style="color: {{ $criticalStockCount > 0 ? '#dc3545' : '#856404' }};">
                    <i class="bi bi-bell-fill"></i> 
                    @if($criticalStockCount > 0)
                        ⚠️ Peringatan Stok Kritis!
                    @else
                        ⚠️ Peringatan Stok Rendah!
                    @endif
                </h5>
                <div class="mb-3">
                    @if($criticalStockCount > 0)
                        <span class="badge bg-danger me-2 mb-2" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                            <i class="bi bi-exclamation-triangle"></i> {{ $criticalStockCount }} Produk Stok Kritis (< 2 pcs)
                        </span>
                    @endif
                    @if($lowStockCount > 0)
                        <span class="badge bg-warning text-dark me-2 mb-2" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                            <i class="bi bi-exclamation-circle"></i> {{ $lowStockCount }} Produk Stok Rendah (2-4 pcs)
                        </span>
                    @endif
                </div>
                @if($criticalStockCount > 0)
                    <div class="mt-3 mb-3 p-3 rounded" style="background: #fff; border: 1px solid #fecaca;">
                        <strong class="text-danger mb-2 d-block">
                            <i class="bi bi-list-ul"></i> Produk Stok Kritis yang Perlu Segera Diisi:
                        </strong>
                        <ul class="mb-0" style="list-style: none; padding-left: 0;">
                            @foreach($criticalStockItems as $item)
                                <li class="mb-2 pb-2 border-bottom">
                                    <strong>{{ $item->product->name }}</strong> 
                                    <span class="text-muted">-</span>
                                    Warna: <span class="badge bg-secondary">{{ $item->color }}</span> 
                                    <span class="text-muted">-</span>
                                    Stok: <span class="badge bg-danger">{{ $item->stock }} pcs</span>
                                </li>
                            @endforeach
                            @if($criticalStockCount > 5)
                                <li class="text-muted mt-2">
                                    <i class="bi bi-three-dots"></i> ... dan {{ $criticalStockCount - 5 }} produk lainnya
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
                <div class="mt-3">
                    <a href="{{ route('fabrics.index') }}" class="btn btn-sm {{ $criticalStockCount > 0 ? 'btn-danger' : 'btn-warning' }}">
                        <i class="bi bi-box-seam"></i> Lihat & Kelola Stok
                    </a>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('supplier-shipments.create') }}" class="btn btn-sm btn-primary ms-2">
                            <i class="bi bi-truck"></i> Input Barang Masuk
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Statistik Utama -->
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #6366f1 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Produk</h6>
                            <h2 class="mb-0">{{ $totalProducts }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Stok</h6>
                            <h2 class="mb-0">{{ $totalStock }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-stack text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Kategori</h6>
                            <h2 class="mb-0">{{ $totalCategories }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-tags text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Stok Rendah</h6>
                            <h2 class="mb-0">{{ $lowStockProducts }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Stats -->
    @if(Auth::user()->role === 'admin')
    <div class="row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #8b5cf6 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Barang Masuk</h6>
                            <h2 class="mb-0">{{ \App\Models\SupplierShipment::count() }}</h2>
                        </div>
                        <div class="p-3 rounded" style="background-color: rgba(139, 92, 246, 0.1);">
                            <i class="bi bi-truck" style="font-size: 1.5rem; color: #8b5cf6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #06b6d4 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Hutang Supplier</h6>
                            <h2 class="mb-0">{{ \App\Models\SupplierShipment::where('payment_status', 'hutang')->count() }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock-history text-info" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #64748b !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Staff</h6>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-secondary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Staff Aktif</h6>
                            <h2 class="mb-0">{{ $activeUsers }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-person-check text-success" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Produk Stok Rendah -->
    @if($lowStockItems->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">⚠️ Produk Stok Rendah</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Warna</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr style="border-left: 4px solid {{ $item->stock < 2 ? '#dc3545' : '#ffc107' }};">
                                    <td><small>{{ $item->product->name }}</small></td>
                                    <td><small>{{ $item->color }}</small></td>
                                    <td>
                                        @if($item->stock < 2)
                                            <span class="badge bg-danger" style="font-size: 0.75rem;">{{ $item->stock }} pcs</span>
                                        @else
                                            <span class="badge bg-warning text-dark" style="font-size: 0.75rem;">{{ $item->stock }} pcs</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->stock < 2)
                                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Kritis</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-circle"></i> Rendah</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik per Kategori -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">📊 Stok per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Produk</th>
                                    <th>Total Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryStats as $stat)
                                <tr>
                                    <td>{{ $stat['name'] }}</td>
                                    <td>{{ $stat['products_count'] }}</td>
                                    <td><strong>{{ $stat['total_stock'] }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Activity Logs - Hanya untuk Admin -->
    @if(Auth::user()->role === 'admin' && $recentActivities->count() > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">📝 Aktivitas Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->user->name ?? 'Unknown' }}</td>
                                    <td>{{ $activity->activity }}</td>
                                    <td><small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection