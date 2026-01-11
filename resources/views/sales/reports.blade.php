@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-file-text"></i> Laporan Penjualan</h2>
            <p class="mb-0 text-muted">Lihat ringkasan penjualan per staff dan keseluruhan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('sales.reports') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Staff</label>
                    <select name="user_id" class="form-select">
                        <option value="">-- Semua Staff --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-secondary w-100">Terapkan</button>
                        <a href="{{ route('sales.reports') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overview -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Penjualan (Filter)</h6>
                    <h3 class="mb-0">Rp {{ number_format($overallTotal ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Terjual (Filter)</h6>
                    <h3 class="mb-0">{{ number_format($overallQuantity ?? 0, 0, ',', '.') }} pcs</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Per-staff table -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0">Ringkasan Per Staff</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Staff</th>
                            <th>Total Terjual (pcs)</th>
                            <th>Total Penjualan (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perStaff as $row)
                            <tr>
                                <td>{{ optional($row->user)->name ?? 'Tidak diketahui' }}</td>
                                <td>{{ number_format($row->total_quantity ?? 0, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row->total_sales ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
