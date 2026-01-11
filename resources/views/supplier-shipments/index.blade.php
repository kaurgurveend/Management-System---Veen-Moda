@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="color: #ffffff !important;"><i class="bi bi-truck" style="color: #ffffff !important;"></i> Barang Masuk dari Supplier</h2>
            <p class="mb-0" style="color: #ffffff !important;">Kelola data pembelian barang dari supplier</p>
        </div>
        <div class="d-flex gap-2">
            @can('admin-only')
            <a href="{{ route('supplier-shipments.reminders') }}" class="btn btn-warning">
                <i class="bi bi-bell"></i> Pengingat Pembayaran
            </a>
            @endcan
            <a href="{{ route('supplier-shipments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Barang Masuk
            </a>
        </div>
    </div>

    @cannot('admin-only')
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Fitur <strong>Pengingat Pembayaran</strong> hanya tersedia untuk <strong>Admin</strong>. Jika Anda memerlukan akses, hubungi pemilik toko.
    </div>
    @endcannot

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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> 
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                    <div class="position-relative">
                        <select name="payment_status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="hutang" {{ request('payment_status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                        </select>
                        <i class="bi bi-chevron-down position-absolute top-50 end-0 translate-middle-y me-3 pe-none" style="color: rgba(255,255,255,0.7);"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-secondary"><i class="bi bi-search"></i> Filter</button>
                        <a href="{{ route('supplier-shipments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                    </div>
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
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ Storage::url($shipment->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Bukti">
                                            <i class="bi bi-file-earmark-check"></i> Lihat
                                        </a>
                                        <form action="{{ route('supplier-shipments.delete-payment-proof', $shipment->id) }}" method="POST" onsubmit="return confirm('Hapus bukti pembayaran? Status akan kembali ke Hutang.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Bukti">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
                                <div class="d-flex gap-2">
                                    <a href="{{ route('supplier-shipments.edit', $shipment->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('supplier-shipments.destroy', $shipment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
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

    <!-- Upload Modals - Placed outside table for proper functionality -->
    @foreach($shipments as $shipment)
        @if($shipment->payment_status === 'hutang')
        <div class="modal fade" id="uploadModal{{ $shipment->id }}" tabindex="-1" aria-labelledby="uploadModalLabel{{ $shipment->id }}" aria-hidden="true" data-bs-backdrop="false" style="z-index: 9999 !important;">
            <div class="modal-dialog modal-dialog-centered" style="z-index: 10000 !important; pointer-events: auto !important;">
                <div class="modal-content" style="pointer-events: auto !important; background-color: #ffffff !important; color: #212529 !important;">
                    <form action="{{ route('supplier-shipments.upload-payment-proof', $shipment->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm{{ $shipment->id }}" style="pointer-events: auto !important;">
                        @csrf
                        <div class="modal-header" style="pointer-events: auto !important;">
                            <h5 class="modal-title" id="uploadModalLabel{{ $shipment->id }}" style="color: white !important;">Upload Bukti Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="pointer-events: auto !important; cursor: pointer !important;"></button>
                        </div>
                        <div class="modal-body" style="pointer-events: auto !important; background-color: #ffffff !important; color: #212529 !important;">
                            <p class="mb-2" style="color: #212529 !important;"><strong>Supplier:</strong> {{ $shipment->supplier_name }}</p>
                            <p class="mb-2" style="color: #212529 !important;"><strong>Produk:</strong> {{ $shipment->product_name }}</p>
                            <p class="mb-3" style="color: #212529 !important;"><strong>Total di Nota:</strong> Rp {{ number_format($shipment->invoice_total ?? $shipment->total_cost, 0, ',', '.') }}</p>
                            <hr>
                            <div class="mb-3">
                                <label class="form-label fw-bold" style="color: #212529 !important; pointer-events: auto !important; cursor: pointer !important;">Bukti Transfer (JPG, PNG, atau PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="payment_proof" id="payment_proof{{ $shipment->id }}" class="form-control @error('payment_proof') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" required style="pointer-events: auto !important; cursor: pointer !important; color: #212529 !important;">
                                <small class="text-muted" style="color: #6c757d !important;">Maksimal ukuran file: 2MB</small>
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="alert alert-info mb-0" style="background-color: #d1ecf1 !important; color: #0c5460 !important; border-color: #bee5eb !important;">
                                <i class="bi bi-info-circle"></i> Setelah upload, status otomatis berubah menjadi <strong>Lunas</strong>
                            </div>
                        </div>
                        <div class="modal-footer" style="pointer-events: auto !important; background-color: #f8f9fa !important;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="pointer-events: auto !important; cursor: pointer !important;">Batal</button>
                            <button type="submit" class="btn btn-success" style="pointer-events: auto !important; cursor: pointer !important;">
                                <i class="bi bi-upload"></i> Upload & Tandai Lunas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>

<style>
/* Modal Simple Fix - Override layout styles */
.modal {
    z-index: 9999 !important;
    pointer-events: auto !important;
    position: fixed !important;
}

.modal-backdrop {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

.modal.show {
    display: block !important;
    pointer-events: auto !important;
}

.modal-dialog {
    pointer-events: auto !important;
    z-index: 10000 !important;
}

.modal-content {
    background-color: #ffffff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5) !important;
    pointer-events: auto !important;
    color: #212529 !important;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    border-bottom: none !important;
    pointer-events: auto !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1) !important;
    pointer-events: auto !important;
    cursor: pointer !important;
    opacity: 1 !important;
}

.modal-title {
    color: white !important;
    font-weight: 600;
}

.modal-body {
    pointer-events: auto !important;
    background-color: #ffffff !important;
    color: #212529 !important;
}

.modal-body p {
    color: #212529 !important;
    margin-bottom: 0.5rem;
}

.modal-body label {
    color: #212529 !important;
}

.modal-body input,
.modal-body button,
.modal-body label,
.modal-body .form-control,
.modal-body .form-label {
    pointer-events: auto !important;
    cursor: pointer !important;
    color: #212529 !important;
}

.modal-body input[type="file"] {
    cursor: pointer !important;
    pointer-events: auto !important;
}

.modal-footer {
    background-color: #f8f9fa !important;
    border-top: 1px solid #dee2e6 !important;
    pointer-events: auto !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
}

.modal-footer button {
    pointer-events: auto !important;
    cursor: pointer !important;
}

/* Ensure all interactive elements are clickable */
.modal * {
    pointer-events: auto !important;
}

.modal button,
.modal input,
.modal select,
.modal textarea,
.modal a,
.modal label {
    pointer-events: auto !important;
    cursor: pointer !important;
}

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
    .row > [class*="col-"] {
        margin-bottom: 1rem;
    }
    .card-body h3 {
        font-size: 1.25rem;
    }
    .card-body h6 {
        font-size: 0.85rem;
    }
    .table-responsive {
        font-size: 0.8rem;
    }
    .table th, .table td {
        padding: 0.5rem 0.375rem;
    }
    .btn-group-sm {
        flex-direction: column;
        width: 100%;
    }
    .btn-group-sm .btn, .btn-group-sm form {
        width: 100%;
        margin-bottom: 0.25rem;
    }
    .row.g-3 > [class*="col-"] {
        margin-bottom: 1rem;
    }
    .d-flex.align-items-end {
        flex-direction: column;
        align-items: stretch !important;
    }
    .d-flex.align-items-end .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    /* Hide some columns on mobile */
    .table th:nth-child(6),
    .table td:nth-child(6),
    .table th:nth-child(7),
    .table td:nth-child(7),
    .table th:nth-child(9),
    .table td:nth-child(9) {
        display: none;
    }
}

@media (max-width: 576px) {
    h2 {
        font-size: 1.25rem;
    }
    .card-body h3 {
        font-size: 1rem;
    }
    .table {
        font-size: 0.75rem;
    }
    .table th, .table td {
        padding: 0.375rem 0.25rem;
    }
    .badge {
        font-size: 0.7rem;
    }
    .alert ul {
        padding-left: 1.25rem;
        font-size: 0.85rem;
    }
    /* Hide more columns on very small screens */
    .table th:nth-child(5),
    .table td:nth-child(5),
    .table th:nth-child(10),
    .table td:nth-child(10) {
        display: none;
    }
}
</style>

@push('scripts')
<script>
    // Handle form submission for upload payment proof
    document.addEventListener('DOMContentLoaded', function() {
        // Force modal to be interactive
        function makeModalClickable() {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.style.pointerEvents = 'auto';
                modal.style.zIndex = '9999';
                
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.style.pointerEvents = 'auto';
                    modalContent.style.zIndex = '10000';
                }
                
                const modalDialog = modal.querySelector('.modal-dialog');
                if (modalDialog) {
                    modalDialog.style.pointerEvents = 'auto';
                    modalDialog.style.zIndex = '10000';
                }
                
                // Make all children clickable
                const allElements = modal.querySelectorAll('*');
                allElements.forEach(function(el) {
                    el.style.pointerEvents = 'auto';
                    if (el.tagName === 'BUTTON' || el.tagName === 'INPUT' || el.tagName === 'LABEL' || el.tagName === 'A') {
                        el.style.cursor = 'pointer';
                    }
                });
            });
            
            // Fix backdrop
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(function(backdrop) {
                backdrop.style.zIndex = '9998';
                backdrop.style.pointerEvents = 'auto';
            });
        }
        
        // Remove backdrop completely
        function removeBackdrop() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(function(backdrop) {
                backdrop.remove();
            });
        }
        
        // Run immediately
        makeModalClickable();
        removeBackdrop();
        
        // Run when modal is shown
        document.addEventListener('shown.bs.modal', function(e) {
            makeModalClickable();
            removeBackdrop();
        });
        
        // Also run on any modal event
        document.addEventListener('show.bs.modal', function(e) {
            setTimeout(function() {
                makeModalClickable();
                removeBackdrop();
            }, 100);
        });
        
        // Watch for backdrop creation and remove it
        const observer = new MutationObserver(function(mutations) {
            removeBackdrop();
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Validate file size before submit
        const uploadForms = document.querySelectorAll('[id^="uploadForm"]');
        uploadForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const fileInput = form.querySelector('input[type="file"]');
                if (fileInput && fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                    
                    if (file.size > maxSize) {
                        e.preventDefault();
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        return false;
                    }
                    
                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengupload...';
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection