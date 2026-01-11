@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="color: #ffffff !important;"><i class="bi bi-bell" style="color: #ffffff !important;"></i> Pengingat Pembayaran</h2>
            <p class="mb-0" style="color: #ffffff !important;">Daftar pengingat pembayaran yang perlu ditindaklanjuti</p>
        </div>
        <a href="{{ route('supplier-shipments.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p class="text-muted">Klik <strong>"Kirim WhatsApp"</strong> untuk membuka WhatsApp Web dengan pesan pra-isian. Gunakan <strong>"Kirim & Tandai"</strong> untuk membuka WhatsApp dan langsung menandai pengingat sebagai terkirim.</p>

            @if($shipments->count() > 0)
            <div class="mb-3 d-flex gap-2">
                @php
                    $adminNumber = config('services.whatsapp.admin_number');
                    $lines = [];
                    foreach($shipments as $s) {
                        $lines[] = "- {$s->supplier_name} | {$s->product_name} | Jatuh tempo: " . ($s->due_date ? $s->due_date->format('d/m/Y') : '-') . " | Total: Rp " . number_format($s->invoice_total ?? $s->total_cost, 0, ',', '.');
                    }
                    $message = urlencode("Halo Admin,%0A%0AMohon diingatkan untuk membayar invoice berikut:%0A" . implode('%0A', $lines) . "%0A%0ATerima kasih.");
                    $waAllUrl = $adminNumber ? "https://wa.me/{$adminNumber}?text={$message}" : '#';
                @endphp

                <a href="{{ $waAllUrl }}" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-whatsapp"></i> Kirim Semua</a>
                <button type="button" class="btn btn-sm btn-success" onclick="openAndMarkAll('{{ $waAllUrl }}')"><i class="bi bi-send"></i> Kirim Semua & Tandai</button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Supplier</th>
                            <th>Produk</th>
                            <th>Jatuh Tempo</th>
                            <th>Total</th>
                            <th>Terakhir Dikirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $s)
                        <tr>
                            <td><strong>{{ $s->supplier_name }}</strong></td>
                            <td>{{ $s->product_name }}</td>
                            <td>{{ $s->due_date ? $s->due_date->format('d/m/Y') : '-' }}</td>
                            <td>Rp {{ number_format($s->invoice_total ?? $s->total_cost, 0, ',', '.') }}</td>
                            <td>
                                @if($s->last_reminder_sent_at)
                                    <small class="text-muted">{{ $s->last_reminder_sent_at->format('d/m/Y H:i') }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @php
                                        $adminNumber = config('services.whatsapp.admin_number');
                                        $message = urlencode("Halo Admin,%0A%0AMohon diingatkan untuk membayar invoice untuk {$s->product_name} dari {$s->supplier_name} (Total: Rp " . number_format($s->invoice_total ?? $s->total_cost, 0, ',', '.') . ") yang jatuh tempo pada " . ($s->due_date ? $s->due_date->format('d/m/Y') : '-') . ".%0A%0ATerima kasih.");
                                        $waUrl = $adminNumber ? "https://wa.me/{$adminNumber}?text={$message}" : '#';
                                    @endphp

                                    <a href="{{ $waUrl }}" target="_blank" class="btn btn-sm btn-outline-success" title="Buka WhatsApp"><i class="bi bi-whatsapp"></i> Kirim WhatsApp</a>

                                    <button type="button" class="btn btn-sm btn-success" onclick="openAndMark('{{ $waUrl }}', {{ $s->id }})"><i class="bi bi-send"></i> Kirim & Tandai</button>

                                    <form action="{{ route('supplier-shipments.mark-reminder-sent', $s->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-check2-circle"></i> Tandai Dikirim</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2">Tidak ada pengingat saat ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function openAndMark(url, id) {
    if (!url || url === '#') {
        alert('Nomor WhatsApp admin belum dikonfigurasi. Silakan atur WHATSAPP_ADMIN_NUMBER di .env');
        return;
    }
    window.open(url, '_blank');

    fetch(`/supplier-shipments/${id}/mark-reminder-sent`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(() => {
        // reload to show updated timestamp
        setTimeout(() => location.reload(), 700);
    }).catch(() => location.reload());
}

function openAndMarkAll(url) {
    if (!url || url === '#') {
        alert('Nomor WhatsApp admin belum dikonfigurasi. Silakan atur WHATSAPP_ADMIN_NUMBER di .env');
        return;
    }
    window.open(url, '_blank');

    fetch(`/supplier-shipments/mark-reminders-sent`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(() => {
        setTimeout(() => location.reload(), 700);
    }).catch(() => location.reload());
}
</script>
@endsection
