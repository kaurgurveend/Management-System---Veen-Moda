<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEEN MODA TEXTILE - Sistem Manajemen Stok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f5f7fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar { min-width: 280px; max-width: 280px; background: #1e293b; color: #fff; transition: all 0.3s; min-height: 100vh; box-shadow: 4px 0 12px rgba(0,0,0,0.08); }
        #sidebar.active { margin-left: -280px; }
        #sidebar .sidebar-header { padding: 30px 24px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        #sidebar .sidebar-header h4 { margin: 0; font-size: 1.25em; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
        #sidebar .sidebar-header p { margin: 5px 0 0; font-size: 0.75em; opacity: 0.9; letter-spacing: 1px; }
        #sidebar ul li a { padding: 14px 24px; font-size: 0.95em; display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.7); text-decoration: none; border-left: 3px solid transparent; transition: all 0.2s; }
        #sidebar ul li a i { font-size: 1.1em; width: 20px; }
        #sidebar ul li a:hover { background: rgba(255,255,255,0.05); color: #fff; border-left-color: #6366f1; }
        #sidebar ul li a.active { background: rgba(99,102,241,0.15); color: #fff; border-left-color: #6366f1; font-weight: 600; }
        #content { width: 100%; padding: 20px; }
        @guest
            #wrapper { display: block; }
            #content { display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        @endguest
    </style>
</head>
<body>
    <div id="wrapper">
        @auth
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4>Veen Moda</h4>
                <p>Textile Management</p>
            </div>
            <ul class="list-unstyled components mt-3">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li>
                    <a href="{{ route('fabrics.index') }}" class="{{ request()->routeIs('fabrics.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Daftar Stok
                        @if(isset($criticalStockCount) && $criticalStockCount > 0)
                            <span class="badge bg-danger ms-2" style="font-size: 0.65rem;">{{ $criticalStockCount }}</span>
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
                <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #f87171;">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
            </ul>
        </nav>
        @endauth

        <div id="content">
            @auth
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4" style="border-radius: 8px;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-list"></i> Menu
                    </button>
                    <span class="ms-auto d-flex align-items-center gap-2">
                        <i class="bi bi-person-circle text-muted"></i>
                        <span class="text-muted">{{ Auth::user()->name }}</span>
                    </span>
                </div>
            </nav>
            @endauth

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('sidebarCollapse');
            if(btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('sidebar').classList.toggle('active');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>