<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEEN MODA TEXTILE - Sistem Manajemen Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            min-height: 100vh; 
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e22ce 100%);
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Shapes */
        .shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            animation: float 20s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 70%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 80%;
            left: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.5;
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.8;
            }
        }
        #wrapper { 
            display: flex; 
            width: 100%; 
            align-items: stretch; 
            position: relative;
            z-index: 2;
        }
        #sidebar { 
            min-width: 280px; 
            max-width: 280px; 
            background: rgba(15, 23, 42, 0.95); 
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            color: #fff; 
            transition: all 0.3s; 
            min-height: 100vh; 
            box-shadow: 4px 0 30px rgba(0,0,0,0.4);
            border-right: 1px solid rgba(255,255,255,0.15);
            z-index: 1000;
        }
        #sidebar.active { margin-left: -280px; }
        #sidebar .sidebar-header { 
            padding: 25px 20px; 
            background: linear-gradient(135deg, rgba(99,102,241,0.95) 0%, rgba(139,92,246,0.95) 100%); 
            backdrop-filter: blur(10px);
            text-align: center; 
            border-bottom: 2px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        #sidebar .sidebar-header h4 { 
            margin: 0; 
            font-size: 1.4em; 
            font-weight: 700; 
            letter-spacing: 1px; 
            text-transform: uppercase; 
            font-family: 'Poppins', sans-serif;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        #sidebar .sidebar-header p { 
            margin: 8px 0 0; 
            font-size: 0.8em; 
            opacity: 0.95; 
            letter-spacing: 2px; 
            color: rgba(255,255,255,0.95);
            font-weight: 300;
        }
        #sidebar { 
            display: flex;
            flex-direction: column;
        }
        #sidebar ul { 
            padding: 15px 0;
            flex: 1;
            overflow-y: auto;
            max-height: calc(100vh - 400px);
        }
        #sidebar ul li { 
            margin: 3px 10px;
        }
        #sidebar ul li a { 
            padding: 12px 16px; 
            font-size: clamp(0.8rem, 2vw, 0.95em);
            display: flex; 
            align-items: center; 
            gap: 10px; 
            color: rgba(255,255,255,0.9); 
            text-decoration: none; 
            border-left: 3px solid transparent; 
            border-radius: 6px;
            transition: all 0.3s ease; 
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            margin-bottom: 3px;
            pointer-events: auto;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* Ensure sidebar and its children always accept clicks */
        #sidebar, #sidebar * { pointer-events: auto; }
        #sidebar ul li a i { 
            font-size: clamp(1rem, 2.5vw, 1.2em);
            width: clamp(20px, 5vw, 24px);
            text-align: center;
            flex-shrink: 0;
        }
        #sidebar ul li a:hover { 
            background: rgba(255,255,255,0.15); 
            color: #fff; 
            border-left-color: #6366f1;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        #sidebar ul li a.active { 
            background: linear-gradient(90deg, rgba(99,102,241,0.3) 0%, rgba(139,92,246,0.2) 100%); 
            color: #fff; 
            border-left-color: #6366f1; 
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        #sidebar ul li a.active i {
            color: #a5b4fc;
        }
        #sidebar .badge {
            margin-left: auto;
            font-size: clamp(0.6rem, 1.5vw, 0.7rem);
            padding: 3px 6px;
            font-weight: 600;
            flex-shrink: 0;
        }
        #content { 
            width: 100%; 
            padding: 20px; 
            position: relative;
            z-index: 2;
        }
        
        /* Mobile responsiveness improvements */
        #backdrop { display: none !important; }
        #backdrop.visible { display: none !important; }
        
        /* Glassmorphism Cards */
        .card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: rgba(255, 255, 255, 0.2) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: white;
            font-weight: 600;
        }
        
        .card-body {
            color: rgba(255, 255, 255, 0.95);
        }
        
        /* Text Colors - Enhanced Readability */
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff !important;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        h1 { font-size: 2rem; }
        h2 { font-size: 1.75rem; }
        h3 { font-size: 1.5rem; }
        
        /* Force white color for all headings */
        .card-header h1, .card-header h2, .card-header h3, 
        .card-header h4, .card-header h5, .card-header h6 {
            color: #ffffff !important;
        }
        
        .text-muted {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        p {
            color: rgba(255, 255, 255, 0.95);
        }
        
        label {
            color: rgba(255, 255, 255, 0.95);
        }
        
        small {
            color: rgba(255, 255, 255, 0.9);
        }
        
        strong {
            color: #ffffff;
        }
        
        /* Buttons */
        .btn-primary {
            background: white;
            color: #1e3c72;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            background: #f0f0f0;
            color: #1e3c72;
        }
        
        .btn-primary:active,
        .btn-primary:focus,
        .btn-primary.active {
            background: white !important;
            color: #1e3c72 !important;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2) !important;
        }
        
        .btn-outline-primary {
            border-color: rgba(255,255,255,0.5);
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .btn-outline-primary:hover {
            background: rgba(255,255,255,0.2);
            border-color: white;
            color: white;
        }
        
        .btn-outline-primary:active,
        .btn-outline-primary:focus,
        .btn-outline-primary.active {
            background: rgba(255,255,255,0.2) !important;
            border-color: white !important;
            color: white !important;
        }
        
        .btn-success {
            background: rgba(16, 185, 129, 0.9);
            border: none;
            color: white;
        }
        
        .btn-success:active,
        .btn-success:focus,
        .btn-success.active {
            background: rgba(16, 185, 129, 0.9) !important;
            color: white !important;
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.9);
            border: none;
            color: white;
        }
        
        .btn-danger:active,
        .btn-danger:focus,
        .btn-danger.active {
            background: rgba(239, 68, 68, 0.9) !important;
            color: white !important;
        }
        
        .btn-warning {
            background: rgba(245, 158, 11, 0.9);
            border: none;
            color: white;
        }
        
        .btn-warning:active,
        .btn-warning:focus,
        .btn-warning.active {
            background: rgba(245, 158, 11, 0.9) !important;
            color: white !important;
        }
        
        .btn-secondary {
            background: rgba(100, 116, 139, 0.95) !important;
            border: none;
            color: white !important;
        }
        
        .btn-secondary:hover {
            background: rgba(71, 85, 105, 0.95) !important;
            color: white !important;
        }
        
        .btn-secondary:active,
        .btn-secondary:focus,
        .btn-secondary.active {
            background: rgba(100, 116, 139, 0.95) !important;
            color: white !important;
        }
        
        .btn-outline-secondary {
            border-color: rgba(255,255,255,0.5) !important;
            color: white !important;
            background: rgba(255,255,255,0.1) !important;
        }
        
        .btn-outline-secondary:hover {
            background: rgba(255,255,255,0.2) !important;
            border-color: white !important;
            color: white !important;
        }
        
        .btn-outline-secondary:active,
        .btn-outline-secondary:focus,
        .btn-outline-secondary.active {
            background: rgba(255,255,255,0.2) !important;
            border-color: white !important;
            color: white !important;
        }
        
        /* Button Spacing - Auto gap for all button groups */
        .btn-group {
            gap: 0.5rem;
        }
        
        .d-flex .btn + .btn,
        .d-inline .btn + .btn,
        .btn + .btn {
            margin-left: 0.5rem;
        }
        
        /* Fix button groups inside forms */
        form.d-inline + form.d-inline {
            margin-left: 0.5rem;
        }
        
        .d-flex.gap-2 {
            gap: 0.5rem !important;
        }
        
        /* Tables - Enhanced Readability */
        .table {
            color: #ffffff;
            font-weight: 400;
        }
        
        .table-light {
            background: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff;
            font-weight: 600;
        }
        
        .table-bordered {
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        
        .table-bordered td, .table-bordered th {
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
        
        .table-hover tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Form Controls - Enhanced Readability */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-weight: 400;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-label {
            color: #ffffff;
            font-weight: 500;
        }
        
        /* Alerts */
        .alert {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.4);
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.2);
            border-color: rgba(245, 158, 11, 0.4);
        }
        
        .alert-info {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
        }
        
        /* Badges */
        .badge {
            font-weight: 500;
        }
        
        /* Pagination */
        .pagination .page-link {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .pagination .page-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .pagination .page-item.active .page-link {
            background: white;
            color: #1e3c72;
            border-color: white;
        }
        
        /* Input Groups */
        .input-group-text {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        /* Modal */
        .modal-content {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-close {
            filter: invert(1);
        }
        
        /* Dropdown */
        .dropdown-menu {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        /* Links */
        a {
            color: rgba(255, 255, 255, 0.9);
        }
        
        a:hover {
            color: white;
        }
        
        /* Small text adjustments */
        small {
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Background light for readonly inputs */
        .bg-light {
            background: rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.7) !important;
        }

        /* High-contrast light theme inside main content area */
        #content, #content * { color: #111827 !important; }
        #content h1, #content h2, #content h3, #content h4, #content h5, #content h6 { color: #0b0f19 !important; text-shadow: none; }
        #content p, #content label, #content small, #content strong { color: #111827 !important; }
        #content a { color: #0b0f19 !important; text-decoration: none; }
        #content a:hover { text-decoration: underline; }
        #content .text-muted, #content .text-secondary { color: #111827 !important; opacity: 1 !important; }
        #content .card { background: #ffffff !important; backdrop-filter: none !important; border: 1px solid #e5e7eb !important; box-shadow: 0 1px 2px rgba(0,0,0,0.06) !important; }
        #content .card-header { background: #f8fafc !important; color: #0b0f19 !important; border-bottom: 1px solid #e5e7eb !important; }
        #content .card-body { color: #111827 !important; }
        #content .table { color: #111827 !important; background: #ffffff !important; }
        #content .table-light { background: #f8fafc !important; color: #0b0f19 !important; }
        #content .table-hover tbody tr:hover { background: #f1f5f9 !important; }
        #content .table-bordered, #content .table-bordered td, #content .table-bordered th { border-color: #e5e7eb !important; }
        #content .form-control, #content .form-select { background: #ffffff !important; color: #111827 !important; border: 1px solid #cbd5e1 !important; }
        #content .form-control:focus, #content .form-select:focus { background: #ffffff !important; border-color: #94a3b8 !important; box-shadow: 0 0 0 3px rgba(148,163,184,0.25) !important; color: #0b0f19 !important; }
        #content .input-group-text { background: #f8fafc !important; border-color: #e5e7eb !important; color: #111827 !important; }
        #content .alert { color: #0b0f19 !important; background: #f8fafc !important; border: 1px solid #e5e7eb !important; }
        #content .navbar { background: #ffffff !important; color: #111827 !important; border: 1px solid #e5e7eb !important; backdrop-filter: none !important; -webkit-backdrop-filter: none !important; }
        #content .navbar .btn, #content .navbar .btn * { color: #111827 !important; }
        #content .badge { color: #111827 !important; }
        #content *:focus, #content *:active { color: #0b0f19 !important; }

        @media (max-width: 767.98px) {
            /* Hide sidebar by default on mobile, show as overlay */
            #sidebar { 
                margin-left: -280px; 
                position: fixed; 
                left: 0; 
                top: 0; 
                bottom: 0; 
                z-index: 1050;
                min-width: 250px;
                max-width: 250px;
            }
            #sidebar.show { margin-left: 0; pointer-events: auto; }
            #sidebar .sidebar-header { 
                padding: 20px 15px;
            }
            #sidebar .sidebar-header h4 { 
                font-size: 1.2em;
            }
            #sidebar .sidebar-header p { 
                font-size: 0.7em;
            }
            #sidebar ul { 
                padding: 10px 0;
                max-height: calc(100vh - 350px);
            }
            #sidebar ul li { 
                margin: 2px 8px;
            }
            #sidebar ul li a { 
                padding: 10px 12px;
                font-size: 0.85em;
                gap: 8px;
            }
            #content { padding: 12px; }
            .navbar { padding: 0.5rem 1rem; }
            .navbar .container-fluid { padding: 0; }
        }
        
        @media (max-width: 576px) {
            #sidebar { 
                min-width: 220px;
                max-width: 220px;
            }
            #sidebar .sidebar-header { 
                padding: 15px 12px;
            }
            #sidebar .sidebar-header h4 { 
                font-size: 1.1em;
                letter-spacing: 0.5px;
            }
            #sidebar .sidebar-header p { 
                font-size: 0.65em;
                letter-spacing: 1px;
            }
            #sidebar ul { 
                padding: 8px 0;
                max-height: calc(100vh - 320px);
            }
            #sidebar ul li { 
                margin: 2px 6px;
            }
            #sidebar ul li a { 
                padding: 8px 10px;
                font-size: 0.8em;
                gap: 6px;
                border-radius: 4px;
            }
            #sidebar ul li a i { 
                font-size: 1rem;
                width: 20px;
            }
            #sidebar .badge {
                font-size: 0.6rem;
                padding: 2px 4px;
            }
            #content { padding: 10px; }
            .navbar { padding: 0.5rem; }
            .navbar .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
        }
        @guest
            #wrapper { display: block; }
            #content { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        @endguest

        /* Fix: hide large decorative hero SVGs that should only appear on landing/welcome */
        svg[viewBox="0 0 440 376"],
        svg[viewBox="0 0 438 104"],
        .w-\[448px\] {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            max-width: 0 !important;
            overflow: hidden !important;
        }
    </style>
</head>
<body>
    <div class="shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div id="wrapper">
        @auth
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4>VEENMODA</h4>
                <p>Textile Management</p>
            </div>
            
            @php
                $criticalStockCount = \App\Models\ProductVariant::where('stock', '<', 2)->count();
                $lowStockCount = \App\Models\ProductVariant::where('stock', '>=', 2)->where('stock', '<', 5)->count();
                $totalProducts = \App\Models\Product::count();
                $totalStock = \App\Models\ProductVariant::sum('stock');
                $todaySales = \App\Models\Sales::whereDate('created_at', today())->sum('total_price');
                $recentActivities = \App\Models\ActivityLog::with('user')->latest()->take(3)->get();
            @endphp
            
            @if(Auth::user()->role === 'admin')
            <!-- Quick Stats untuk Admin -->
            <div style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7); margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Quick Stats</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #a5b4fc;">{{ $totalProducts }}</div>
                        <div style="font-size: 0.7rem; color: rgba(255,255,255,0.8);">Produk</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #a5b4fc;">{{ number_format($totalStock) }}</div>
                        <div style="font-size: 0.7rem; color: rgba(255,255,255,0.8);">Total Stok</div>
                    </div>
                </div>
                @if($criticalStockCount > 0)
                <div style="margin-top: 10px; padding: 10px; background: rgba(239,68,68,0.2); border-left: 3px solid #ef4444; border-radius: 6px;">
                    <div style="font-size: 0.85rem; font-weight: 600; color: #fecaca;">
                        <i class="bi bi-exclamation-triangle"></i> {{ $criticalStockCount }} Stok Kritis
                    </div>
                </div>
                @endif
            </div>
            @endif
            
            <ul class="list-unstyled components mt-2">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li>
                    <a href="{{ route('fabrics.index') }}" class="{{ request()->routeIs('fabrics.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Daftar Stok
                        @if($criticalStockCount > 0)
                            <span class="badge bg-danger">{{ $criticalStockCount }}</span>
                        @endif
                    </a>
                </li>
                <li><a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i> Penjualan</a></li>
                @if(Auth::user()->role === 'admin')
                    <li><a href="{{ route('supplier-shipments.index') }}" class="{{ request()->routeIs('supplier-shipments.*') ? 'active' : '' }}"><i class="bi bi-truck"></i> Barang Masuk</a></li>
                    <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="bi bi-tags"></i> Kategori</a></li>
                    <li><a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-people"></i> Staff</a></li>
                    <li><a href="{{ route('activity-logs') }}" class="{{ request()->routeIs('activity-logs') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Activity Log</a></li>
                @endif
            </ul>
            
            @if(Auth::user()->role === 'admin' && $recentActivities->count() > 0)
            <!-- Recent Activity untuk Admin -->
            <div style="padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 10px;">
                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.7); margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Recent Activity</div>
                <div style="max-height: 150px; overflow-y: auto;">
                    @foreach($recentActivities as $activity)
                    <div style="padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.8rem;">
                        <div style="color: rgba(255,255,255,0.9); margin-bottom: 4px;">{{ Str::limit($activity->activity, 40) }}</div>
                        <div style="color: rgba(255,255,255,0.6); font-size: 0.7rem;">
                            {{ $activity->user->name ?? 'Unknown' }} • {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div style="margin-top: auto; padding: 15px 20px; border-top: 1px solid rgba(255,255,255,0.1); flex-shrink: 0;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: flex-start; gap: 12px; padding: 12px 16px; color: rgba(255,255,255,0.9); text-decoration: none; border-radius: 8px; transition: all 0.3s; background: rgba(239,68,68,0.1); border: none; border-left: 3px solid #ef4444; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.95em; font-weight: 500; pointer-events: auto;">
                        <i class="bi bi-box-arrow-right" style="font-size: 1.1em; flex-shrink: 0;"></i>
                        <span style="font-weight: 500;">Logout</span>
                    </button>
                </form>
            </div>
        </nav>
        @endauth

        <div id="content">
            @auth
            <nav class="navbar navbar-expand-lg mb-4" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 8px 32px rgba(0,0,0,0.1);">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="bi bi-list"></i> Menu
                    </button>
                    <span class="ms-auto d-flex align-items-center gap-2" style="color: white;">
                        <i class="bi bi-person-circle"></i>
                        <span style="font-weight: 500;">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->role === 'admin')
                            <span class="badge" style="background: rgba(99,102,241,0.8);">Admin</span>
                        @else
                            <span class="badge" style="background: rgba(139,92,246,0.8);">Staff</span>
                        @endif
                    </span>
                </div>
            </nav>
            @endauth

            @yield('content')
        </div>
    </div>

    <div id="backdrop"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('backdrop');

            function toggleMobileSidebar() {
                const isMobile = window.innerWidth < 768;
                if (!sidebar) return;
                if (isMobile) {
                    sidebar.classList.toggle('show');
                } else {
                    sidebar.classList.toggle('active');
                }
            }

            if (btn && sidebar) {
                btn.addEventListener('click', toggleMobileSidebar);
            }
            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                });
            }
            // Close mobile sidebar on any menu item click
            if (sidebar) {
                sidebar.querySelectorAll('a[href]').forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        if (window.innerWidth < 768) {
                            // Close overlay first
                            sidebar.classList.remove('show');
                            // Force navigation to ensure it always proceeds
                            const url = link.getAttribute('href');
                            if (url && url !== '#') {
                                e.preventDefault();
                                window.location.href = url;
                            }
                        }
                    });
                });
            }

            // Close mobile sidebar on resize to desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768 && sidebar) {
                    sidebar.classList.remove('show');
                }
            });

            // Allow closing with Escape key (useful while inspecting)
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    if (sidebar) sidebar.classList.remove('show');
                }
            });

            // Force navigation for sidebar links even under DevTools overlays
            (function(){
                function getSidebarLinkFromEvent(e){
                    return e.target && e.target.closest ? e.target.closest('#sidebar a[href]') : null;
                }
                function getUrl(link){
                    if (!link) return null;
                    const url = link.getAttribute('href');
                    return (url && url !== '#') ? url : null;
                }
                function closeOverlayIfMobile(){
                    if (window.innerWidth < 768) {
                    if (sidebar) sidebar.classList.remove('show');
                    }
                }
                function navigate(url){
                    // Use assign to ensure history updates
                    setTimeout(function(){ window.location.assign(url); }, 0);
                }
                function isInModal(element){
                    if (!element) return false;
                    
                    // Check if any modal is currently visible
                    const openModals = document.querySelectorAll('.modal.show');
                    if (openModals.length === 0) return false;
                    
                    // Check if clicking on modal, modal-backdrop, or any modal child
                    let current = element;
                    while (current && current !== document.body) {
                        if (current.classList && 
                            (current.classList.contains('modal') || 
                             current.classList.contains('modal-backdrop'))) {
                            return true;
                        }
                        current = current.parentElement;
                    }
                    
                    return false;
                }
                // Click capture
                document.addEventListener('click', function(e){
                    // Don't interfere with modal interactions
                    if (isInModal(e.target)) return;
                    
                    const link = getSidebarLinkFromEvent(e);
                    const url = getUrl(link);
                    if (!url) return;
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    closeOverlayIfMobile();
                    navigate(url);
                }, true);
                // Pointer up capture (covers some DevTools/touch emulations)
                document.addEventListener('pointerup', function(e){
                    // Don't interfere with modal interactions
                    if (isInModal(e.target)) return;
                    
                    const link = getSidebarLinkFromEvent(e);
                    const url = getUrl(link);
                    if (!url) return;
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    closeOverlayIfMobile();
                    navigate(url);
                }, true);
                // Keyboard activate (Enter/Space)
                document.addEventListener('keydown', function(e){
                    // Don't interfere with modal interactions
                    if (isInModal(e.target)) return;
                    
                    if (e.key !== 'Enter' && e.key !== ' ') return;
                    const focused = document.activeElement;
                    const link = focused && focused.closest ? focused.closest('#sidebar a[href]') : null;
                    const url = getUrl(link);
                    if (!url) return;
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    closeOverlayIfMobile();
                    navigate(url);
                }, true);
            })();
        });
    </script>

    <script>
        // Remove oversized landing illustrations on non-root pages (failsafe)
        (function(){
            try {
                if (window.location.pathname !== '/') {
                    document.addEventListener('DOMContentLoaded', function(){
                        document.querySelectorAll('svg').forEach(function(s){
                            try {
                                var r = s.getBoundingClientRect();
                                var viewBox = s.getAttribute('viewBox') || '';
                                var cls = s.getAttribute('class') || '';
                                if (r.width > 300 || r.height > 200 || viewBox === '0 0 440 376' || viewBox === '0 0 438 104' || /w-\[448px\]/.test(cls)) {
                                    s.remove();
                                }
                            } catch (e) { /* ignore per-element errors */ }
                        });
                        document.querySelectorAll('[class*="w-\[448px\]"]').forEach(function(el){ el.remove(); });
                    });
                }
            } catch (e) { /* global fail-safe */ }
        })();
    </script>

    @stack('scripts')
</body>
</html>