@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-truck"></i> Barang Masuk dari Supplier</h2>
            <p class="text-muted mb-0">Kelola data pembelian barang dari supplier</p>
        </div>
        <a href="{{ route('supplier-shipments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Barang Masuk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Notifications for Approaching Due Dates -->
    @php
        $approachingDue = \App\Models\SupplierShipment::where('payment_status', 'hutang')
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addWeeks(6))
            ->orderBy('due_date', 'asc')
            ->get();
        $overdue = \App\Models\SupplierShipment::where('payment_status', 'hutang')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();
    @endphp

    @if($overdue->count() > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Pembayaran Telah Jatuh Tempo!</h6>
            <ul class="mb-0">
                @foreach($overdue as $item)
                    <li><strong>{{ $item->supplier_name }}</strong> - {{ $item->product_name }} 
                        <span class="badge bg-danger">Jatuh tempo: {{ $item->due_date->format('d/m/Y') }}</span>
                        <span class="text-muted">({{ abs($item->days_until_due) }} hari yang lalu)</span>
                    </li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($approachingDue->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="bi bi-bell-fill"></i> Pengingat Jatuh Tempo (6 Minggu ke Depan)</h6>
            <ul class="mb-0">
                @foreach($approachingDue as $item)
                    <li><strong>{{ $item->supplier_name }}</strong> - {{ $item->product_name }} 
                        <span class="badge bg-warning text-dark">Jatuh tempo: {{ $item->due_date->format('d/m/Y') }}</span>
                        <span class="text-muted">({{ $item->days_until_due }} hari lagi)</span>
                    </li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('supplier-shipments.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama supplier atau produk..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="payment_status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="hutang" {{ request('payment_status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                    <a href="{{ route('supplier-shipments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Pembelian</h6>
                    <h3 class="mb-0">{{ $shipments->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Lunas</h6>
                    <h3 class="mb-0">{{ \App\Models\SupplierShipment::where('payment_status', 'lunas')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Hutang</h6>
                    <h3 class="mb-0">{{ \App\Models\SupplierShipment::where('payment_status', 'hutang')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Modal</h6>
                    <h3 class="mb-0">Rp {{ number_format(\App\Models\SupplierShipment::sum(\DB::raw('cost_price * quantity_pieces')), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Terima</th>
                            <th>Supplier</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Modal Supplier</th>
                            <th>Biaya Lain</th>
                            <th>HPP</th>
                            <th>Status</th>
                            <th>Bukti Bayar</th>
                            <th>Jatuh Tempo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $shipment)
                        <tr>
                            <td>{{ $shipment->received_date->format('d/m/Y') }}</td>
                            <td><strong>{{ $shipment->supplier_name }}</strong></td>
                            <td>{{ $shipment->product_name }}</td>
                            <td><span class="badge bg-secondary">{{ number_format($shipment->quantity_pieces) }} pcs</span></td>
                            <td>Rp {{ number_format($shipment->cost_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($shipment->additional_costs, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($shipment->hpp, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($shipment->payment_status === 'lunas')
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Lunas</span>
                                    @if($shipment->paid_at)
                                        <br><small class="text-muted">{{ $shipment->paid_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Hutang</span>
                                @endif
                            </td>
                            <td>
                                @if($shipment->payment_proof)
                                    <a href="{{ Storage::url($shipment->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Bukti">
                                        <i class="bi bi-file-earmark-check"></i> Lihat
                                    </a>
                                    <form action="{{ route('supplier-shipments.delete-payment-proof', $shipment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus bukti pembayaran? Status akan kembali ke Hutang.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Bukti">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @elseif($shipment->payment_status === 'hutang')
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $shipment->id }}">
                                        <i class="bi bi-upload"></i> Upload
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($shipment->payment_status === 'hutang' && $shipment->due_date)
                                    @if($shipment->is_overdue)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-circle"></i> {{ $shipment->due_date->format('d/m/Y') }}
                                        </span>
                                    @elseif($shipment->is_approaching_due)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-bell"></i> {{ $shipment->due_date->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted"><i class="bi bi-calendar"></i> {{ $shipment->due_date->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('supplier-shipments.edit', $shipment->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('supplier-shipments.destroy', $shipment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Upload Modal -->
                                @if($shipment->payment_status === 'hutang')
                                <div class="modal fade" id="uploadModal{{ $shipment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('supplier-shipments.upload-payment-proof', $shipment->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <p><strong>Supplier:</strong> {{ $shipment->supplier_name }}</p>
                                                    <p><strong>Produk:</strong> {{ $shipment->product_name }}</p>
                                                    <p><strong>Total:</strong> Rp {{ number_format($shipment->total_cost, 0, ',', '.') }}</p>
                                                    <hr>
                                                    <div class="mb-3">
                                                        <label class="form-label">Bukti Transfer (JPG, PNG, atau PDF)</label>
                                                        <input type="file" name="payment_proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                                        <small class="text-muted">Max: 2MB</small>
                                                    </div>
                                                    <div class="alert alert-info">
                                                        <i class="bi bi-info-circle"></i> Setelah upload, status otomatis berubah menjadi <strong>Lunas</strong>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="bi bi-upload"></i> Upload & Tandai Lunas
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2">Belum ada data barang masuk</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection