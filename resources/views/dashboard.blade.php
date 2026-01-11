@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <style>
        /* Notification Bubble */
        .notification-bubble {
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 1050;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .notification-bubble:hover {
            transform: scale(1.1);
        }
        
        .notification-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(220, 38, 38, 0.4);
            position: relative;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(220, 38, 38, 0.4);
            }
            50% {
                box-shadow: 0 4px 30px rgba(220, 38, 38, 0.6);
            }
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .notification-popup {
            display: none;
            position: fixed;
            top: 100px;
            right: 30px;
            width: 400px;
            max-height: 80vh;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 2px solid #fecaca;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.2);
            z-index: 1049;
            animation: slideInRight 0.3s ease;
        }
        
        .notification-popup.show {
            display: block;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .notification-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 1.25rem;
            border-radius: 18px 18px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        
        .notification-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        .notification-body {
            padding: 1.5rem;
            max-height: 500px;
            overflow-y: auto;
        }
        
        @media (max-width: 576px) {
            .notification-bubble {
                top: 80px;
                right: 15px;
            }
            
            .notification-icon {
                width: 50px;
                height: 50px;
            }
            
            .notification-popup {
                top: 80px;
                right: 15px;
                left: 15px;
                width: auto;
            }
        }
        
        /* Glassmorphism Cards - Soft & Easy on Eyes */
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15) !important;
            border-radius: 16px !important;
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(31, 38, 135, 0.25) !important;
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%) !important;
            backdrop-filter: blur(20px);
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
            border-radius: 20px !important;
            overflow: hidden;
            position: relative;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color-start), var(--card-color-end));
        }
        
        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #64748b !important;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Alert Styles - Soft Colors */
        .alert-soft {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 2px solid var(--alert-border-color);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }
        
        .alert-soft .alert-heading {
            color: var(--alert-text-color) !important;
        }
        
        .alert-soft.alert-danger {
            --alert-border-color: #fecaca;
            --alert-text-color: #dc2626;
        }
        
        .alert-soft.alert-warning {
            --alert-border-color: #fed7aa;
            --alert-text-color: #d97706;
        }
        
        /* Table Card Styles */
        .table-card {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(20px);
            border: none !important;
            border-radius: 20px !important;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08) !important;
        }
        
        .table-card .card-header {
            background: linear-gradient(135deg, var(--header-color-start), var(--header-color-end)) !important;
            border: none !important;
            padding: 1.25rem 1.5rem;
            color: white !important;
        }
        
        .table-card .card-header h6 {
            color: white !important;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
        }
        
        .table-card .table {
            color: #334155 !important;
            margin-bottom: 0;
        }
        
        .table-card .table thead th {
            background: #f8fafc !important;
            color: #475569 !important;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .table-card .table tbody tr {
            border-bottom: 1px solid #f1f5f9 !important;
            transition: all 0.2s ease;
        }
        
        .table-card .table tbody tr:hover {
            background: #f8fafc !important;
        }
        
        .table-card .table td {
            color: #334155 !important;
            padding: 1rem 0.75rem;
        }
        
        /* Badge Styles - Soft Colors */
        .badge-soft {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .badge-soft.bg-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            color: #dc2626 !important;
        }
        
        .badge-soft.bg-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
            color: #d97706 !important;
        }
        
        .badge-soft.bg-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%) !important;
            color: #059669 !important;
        }
        
        .badge-soft.bg-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
            color: #2563eb !important;
        }
        
        .badge-soft.bg-secondary {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%) !important;
            color: #475569 !important;
        }
        
        /* Button Styles */
        .btn-gradient {
            background: linear-gradient(135deg, var(--btn-color-start), var(--btn-color-end));
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        /* Critical Stock List */
        .critical-stock-list {
            background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
            border: 2px solid #fecaca;
            border-radius: 16px;
            padding: 1.5rem;
        }
        
        .critical-stock-item {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            border-left: 4px solid #dc2626;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        .critical-stock-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.1) 100%);
            backdrop-filter: blur(20px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }
        
        .page-header h1 {
            color: #ffffff !important;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: rgba(255, 255, 255, 0.95) !important;
            font-size: 1.125rem;
        }
        
        @media (max-width: 768px) {
            .row > [class*="col-"] {
                margin-bottom: 1rem;
            }
            .stat-number {
                font-size: 1.5rem;
            }
            .stat-icon-wrapper {
                width: 50px;
                height: 50px;
            }
        }
        
        @media (max-width: 576px) {
            .stat-number {
                font-size: 1.25rem;
            }
            .page-header {
                padding: 1.5rem;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
                <p class="mb-0">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong></p>
            </div>
        </div>
    </div>

    <!-- Notification Bubble for Critical Stock -->
    @if($criticalStockCount > 0 || $lowStockCount > 0)
    <div class="notification-bubble" id="notificationBubble" onclick="toggleNotification()">
        <div class="notification-icon">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.75rem; color: #dc2626;"></i>
            <div class="notification-badge">{{ $criticalStockCount + $lowStockCount }}</div>
        </div>
    </div>
    
    <div class="notification-popup" id="notificationPopup">
        <div class="notification-header">
            <h6 class="mb-0 fw-bold"><i class="bi bi-bell-fill"></i> Peringatan Stok</h6>
            <button class="notification-close" onclick="toggleNotification()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="notification-body">
            <div class="mb-3 d-flex gap-2 flex-wrap">
                @if($criticalStockCount > 0)
                    <span class="badge badge-soft bg-danger">
                        <i class="bi bi-exclamation-triangle"></i> {{ $criticalStockCount }} Stok Kritis
                    </span>
                @endif
                @if($lowStockCount > 0)
                    <span class="badge badge-soft bg-warning">
                        <i class="bi bi-exclamation-circle"></i> {{ $lowStockCount }} Stok Rendah
                    </span>
                @endif
            </div>
            
            @if($criticalStockCount > 0)
                <div class="mb-3">
                    <strong style="color: #dc2626; margin-bottom: 0.75rem; display: block; font-size: 0.9rem;">
                        <i class="bi bi-list-ul"></i> Produk Kritis (< 2 pcs):
                    </strong>
                    @foreach($criticalStockItems as $item)
                        <div class="critical-stock-item">
                            <div>
                                <strong style="color: #1e293b; font-size: 0.9rem;">{{ $item->product->name }}</strong>
                                <div class="mt-1">
                                    <span class="badge badge-soft bg-secondary me-2" style="font-size: 0.75rem;">{{ $item->color }}</span>
                                    <span class="badge badge-soft bg-danger" style="font-size: 0.75rem;">{{ $item->stock }} pcs</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($criticalStockCount > 5)
                        <div class="text-muted mt-2" style="color: #64748b !important; font-size: 0.85rem;">
                            <i class="bi bi-three-dots"></i> ... dan {{ $criticalStockCount - 5 }} produk lainnya
                        </div>
                    @endif
                </div>
            @endif
            
            <div class="d-grid gap-2">
                <a href="{{ route('fabrics.index') }}" class="btn btn-gradient" style="--btn-color-start: #dc2626; --btn-color-end: #ef4444;">
                    <i class="bi bi-box-seam"></i> Lihat & Kelola Stok
                </a>
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('supplier-shipments.create') }}" class="btn btn-gradient" style="--btn-color-start: #6366f1; --btn-color-end: #8b5cf6;">
                        <i class="bi bi-truck"></i> Input Barang Masuk
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    <script>
        function toggleNotification() {
            const popup = document.getElementById('notificationPopup');
            popup.classList.toggle('show');
        }
        
        // Close popup when clicking outside
        document.addEventListener('click', function(event) {
            const popup = document.getElementById('notificationPopup');
            const bubble = document.getElementById('notificationBubble');
            
            if (popup.classList.contains('show') && 
                !popup.contains(event.target) && 
                !bubble.contains(event.target)) {
                popup.classList.remove('show');
            }
        });
    </script>
    @endif
    
    <!-- Statistik Utama -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #6366f1; --card-color-end: #8b5cf6;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Total Produk</p>
                            <h2 class="stat-number mb-0">{{ $totalProducts }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-box-seam" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #10b981; --card-color-end: #059669;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Total Stok</p>
                            <h2 class="stat-number mb-0">{{ $totalStock }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-stack" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #f59e0b; --card-color-end: #d97706;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Kategori</p>
                            <h2 class="stat-number mb-0">{{ $totalCategories }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-tags" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #ef4444; --card-color-end: #dc2626;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Stok Rendah</p>
                            <h2 class="stat-number mb-0">{{ $lowStockProducts }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Stats -->
    @if(Auth::user()->role === 'admin')
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #8b5cf6; --card-color-end: #7c3aed;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Barang Masuk</p>
                            <h2 class="stat-number mb-0">{{ \App\Models\SupplierShipment::count() }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-truck" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #06b6d4; --card-color-end: #0891b2;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Hutang Supplier</p>
                            <h2 class="stat-number mb-0">{{ \App\Models\SupplierShipment::where('payment_status', 'hutang')->count() }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-clock-history" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #64748b; --card-color-end: #475569;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Total Staff</p>
                            <h2 class="stat-number mb-0">{{ $totalUsers }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-people" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="--card-color-start: #10b981; --card-color-end: #059669;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Staff Aktif</p>
                            <h2 class="stat-number mb-0">{{ $activeUsers }}</h2>
                        </div>
                        <div class="stat-icon-wrapper">
                            <i class="bi bi-person-check" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Produk Stok Rendah -->
    @if($lowStockItems->count() > 0)
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="table-card" style="--header-color-start: #ef4444; --header-color-end: #dc2626;">
                <div class="card-header">
                    <h6><i class="bi bi-exclamation-triangle-fill"></i> Produk Stok Rendah</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
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
                                <tr>
                                    <td><strong>{{ $item->product->name }}</strong></td>
                                    <td>{{ $item->color }}</td>
                                    <td>
                                        @if($item->stock < 2)
                                            <span class="badge badge-soft bg-danger">{{ $item->stock }} pcs</span>
                                        @else
                                            <span class="badge badge-soft bg-warning">{{ $item->stock }} pcs</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->stock < 2)
                                            <span class="badge badge-soft bg-danger"><i class="bi bi-exclamation-triangle"></i> Kritis</span>
                                        @else
                                            <span class="badge badge-soft bg-warning"><i class="bi bi-exclamation-circle"></i> Rendah</span>
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
            <div class="table-card" style="--header-color-start: #6366f1; --header-color-end: #8b5cf6;">
                <div class="card-header">
                    <h6><i class="bi bi-bar-chart-fill"></i> Stok per Kategori</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
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
                                    <td><strong>{{ $stat['name'] }}</strong></td>
                                    <td>{{ $stat['products_count'] }}</td>
                                    <td><span class="badge badge-soft bg-info">{{ $stat['total_stock'] }} pcs</span></td>
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
    <div class="row g-4 mt-2">
        <div class="col-md-12">
            <div class="table-card" style="--header-color-start: #64748b; --header-color-end: #475569;">
                <div class="card-header">
                    <h6><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
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
                                    <td><strong>{{ $activity->user->name ?? 'Unknown' }}</strong></td>
                                    <td>{{ $activity->activity }}</td>
                                    <td><span class="badge badge-soft bg-secondary">{{ $activity->created_at->diffForHumans() }}</span></td>
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