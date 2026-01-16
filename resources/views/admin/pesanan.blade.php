@extends('layouts.admin')

@section('title', 'Riwayat Pesanan | Indo Ice Tea')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-left: 5px solid var(--primary);
    }

    .stat-card small { font-size: 12px; color: #888; text-transform: uppercase; }
    .stat-card h3 { margin: 5px 0 0 0; font-size: 20px; }

    .table-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-header { padding: 20px 25px; border-bottom: 1px solid #eee; }

    table { width: 100%; border-collapse: collapse; }

    th {
        background: #fafafa;
        color: #888;
        padding: 15px 25px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    td {
        padding: 20px 25px;
        border-bottom: 1px solid #f8f8f8;
        font-size: 14px;
    }

    tr:hover { background-color: #fffaf9; }

    .badge {
        padding: 6px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-baru { background: #fff4e5; color: #ff9800; }
    .status-selesai { background: #e7f7ed; color: #2ecc71; }
    .status-batal { background: #ffeaea; color: #e74c3c; }

    select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-family: inherit;
        background: #f9f9f9;
    }

    .btn-update {
        background: var(--dark);
        color: white;
        border: none;
        padding: 8px 15px;
        cursor: pointer;
        border-radius: 8px;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-update:hover { background: var(--primary); }

    .order-items {
        font-size: 13px;
        color: #555;
        background: #fdfdfd;
        padding: 10px;
        border-radius: 8px;
        border-left: 3px solid #eee;
    }
</style>
@endpush

@section('content')
<h1 style="font-size:1.6rem; margin-bottom:10px;">📦 Manajemen Pesanan</h1>
<p style="color:#888; margin-bottom:30px;">Kelola status pesanan dan lihat riwayat transaksi.</p>

<div class="stats-grid">
    <div class="stat-card">
        <small>Total Pesanan</small>
        <h3>{{ $totalPesanan }} Order</h3>
    </div>
    <div class="stat-card" style="border-left-color: #27ae60;">
        <small>Pendapatan Selesai</small>
        <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
    </div>
</div>

<div class="table-card">
    <div class="table-header">
        <h3 style="margin:0; font-size:18px;">📦 Daftar Pesanan Masuk</h3>
    </div>
    
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Waktu & ID</th>
                    <th>Detail Item</th>
                    <th>Total Bayar</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan as $order)
                @php $classStatus = strtolower($order->status_pesanan); @endphp
                <tr>
                    <td>
                        <div style="font-weight:600; color:var(--primary)">#ORD-{{ $order->id }}</div>
                        <div style="font-size:11px; color:#aaa; margin-top:4px;">
                            {{ \Carbon\Carbon::parse($order->waktu_pesan)->format('d M Y, H:i') }}
                        </div>
                    </td>
                    <td>
                        <div class="order-items">{!! nl2br(e($order->detail_pesanan)) !!}</div>
                    </td>
                    <td>
                        <span style="font-weight:700;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span style="font-size:11px; padding:3px 8px; background:#eee; border-radius:4px; font-weight:600;">
                            {{ strtoupper($order->metode_pembayaran) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge status-{{ $classStatus }}">{{ $order->status_pesanan }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.pesanan.status') }}" style="display:flex; gap:5px;">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <select name="status_baru">
                                <option value="Baru" {{ $order->status_pesanan == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Selesai" {{ $order->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Batal" {{ $order->status_pesanan == 'Batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <button type="submit" class="btn-update" title="Simpan Perubahan">✓</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
