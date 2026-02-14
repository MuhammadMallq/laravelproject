@extends('layouts.admin')

@section('title', 'Dashboard Unified | Indo Ice Tea')

@push('styles')
<style>
    /* Tabs Navigation */
    .tabs-nav {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        border-bottom: 2px solid #eee;
        padding-bottom: 0;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 600;
        color: #888;
        cursor: pointer;
        position: relative;
        transition: 0.3s;
    }

    .tab-btn:hover { color: var(--primary); }

    .tab-btn.active {
        color: var(--primary);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary);
        border-radius: 3px 3px 0 0;
    }

    .tab-pane { display: none; animation: fadeIn 0.3s ease-out; }
    .tab-pane.active { display: block; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Existing Dashboard Styles */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: #fff0eb;
        color: var(--primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .card-info h3 { margin: 0; font-size: 1.5rem; color: #333; }
    .card-info p { margin: 0; font-size: 0.85rem; color: #888; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    .chart-box {
        background: white;
        padding: 25px;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .chart-header h3 { margin: 0; font-size: 1.1rem; color: #333; }

    /* Table Styles */
    .table-container { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    table { width: 100%; border-collapse: collapse; }
    th { background: #fafafa; color: #888; padding: 15px; text-align: left; font-size: 0.85rem; text-transform: uppercase; }
    td { padding: 15px; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
    
    .input-stok { width: 70px; padding: 8px; border-radius: 8px; border: 1px solid #ddd; text-align: center; }
    .btn-icon { border: none; background: transparent; font-size: 1.2rem; cursor: pointer; padding: 8px; transition: 0.2s; }
    .btn-save { color: #27ae60; } .btn-save:hover { transform: scale(1.1); }
    .btn-delete { color: #e74c3c; } .btn-delete:hover { transform: scale(1.1); }
    
    .badge { padding: 6px 15px; border-radius: 8px; font-size: 12px; font-weight: 600; display: inline-block; }
    .status-baru { background: #fff4e5; color: #ff9800; }
    .status-selesai { background: #e7f7ed; color: #2ecc71; }
    .status-batal { background: #ffeaea; color: #e74c3c; }
    
    .btn-update { background: var(--dark); color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 8px; }
    .btn-update:hover { background: var(--primary); }

    /* Period Filters */
    .period-filter {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
    
    .period-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .period-btn {
        padding: 8px 15px;
        border-radius: 20px;
        border: 1px solid #eee;
        background: #fafafa;
        color: #666;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .period-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    
    .period-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .filter-info {
        font-weight: 600;
        color: #555;
        background: #f0f0f0;
        padding: 5px 15px;
        border-radius: 15px;
        font-size: 0.85rem;
    }

    /* Mini Stats */
    .mini-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .mini-stat {
        background: white;
        padding: 15px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.02);
        border: 1px solid #f5f5f5;
    }
    
    .mini-stat-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }
    
    .mini-stat-label {
        font-size: 0.8rem;
        color: #888;
    }

    /* 2x2 Charts Grid */
    .charts-grid-2x2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 25px;
    }

    /* Responsive */
    @media (max-width: 1000px) { 
        .dashboard-grid, .charts-grid-2x2 { grid-template-columns: 1fr; } 
        .period-filter { flex-direction: column; gap: 15px; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<h1 style="font-size: 1.6rem; margin-bottom: 10px;">👑 Admin Dashboard</h1>
<p style="color:#888; margin-bottom: 20px;">Halo Admin, kelola semua aktivitas toko di sini.</p>

<div class="tabs-nav" style="display:none;">
    <button class="tab-btn active" onclick="openTab('overview')">📊 Overview</button>
    <button class="tab-btn" onclick="openTab('stok')">📦 Manajemen Stok</button>
    <button class="tab-btn" onclick="openTab('pesanan')">📝 Riwayat Pesanan</button>
</div>

<!-- TAB 1: OVERVIEW -->
<div id="overview" class="tab-pane active">
    
    <!-- Period Filter -->
    <div class="period-filter">
        <div class="period-buttons">
            <a href="{{ route('admin.dashboard', ['period' => 'all']) }}" class="period-btn {{ $period == 'all' ? 'active' : '' }}">📊 Semua</a>
            <a href="{{ route('admin.dashboard', ['period' => 'today']) }}" class="period-btn {{ $period == 'today' ? 'active' : '' }}">📅 Hari Ini</a>
            <a href="{{ route('admin.dashboard', ['period' => 'yesterday']) }}" class="period-btn {{ $period == 'yesterday' ? 'active' : '' }}">📆 Kemarin</a>
            <a href="{{ route('admin.dashboard', ['period' => 'week']) }}" class="period-btn {{ $period == 'week' ? 'active' : '' }}">📅 Minggu Ini</a>
            <a href="{{ route('admin.dashboard', ['period' => 'month']) }}" class="period-btn {{ $period == 'month' ? 'active' : '' }}">🗓️ Bulan Ini</a>
            <a href="{{ route('admin.dashboard', ['period' => 'year']) }}" class="period-btn {{ $period == 'year' ? 'active' : '' }}">📈 Tahun Ini</a>
        </div>
        <div class="filter-info">📌 {{ $filterLabel }}</div>
    </div>

    <!-- Main Cards Stats -->
    <div class="cards-grid">
        <div class="card">
            <div class="card-icon"><i class="fas fa-wallet"></i></div>
            <div class="card-info">
                <p>Total Omset</p>
                <h3>Rp {{ number_format($omset, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-icon" style="background:#eaf6fc; color:#3498db;"><i class="fas fa-receipt"></i></div>
            <div class="card-info">
                <p>Total Pesanan</p>
                <h3>{{ $totalOrder }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-icon" style="background:#fff5f5; color:#e74c3c;"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="card-info">
                <p>Stok Menipis</p>
                <h3 style="color:#e74c3c;">{{ $stokMenipis }} Item</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-icon" style="background:#eafaf1; color:#27ae60;"><i class="fas fa-mug-hot"></i></div>
            <div class="card-info">
                <p>Varian Menu</p>
                <h3>{{ $totalMenu }}</h3>
            </div>
        </div>
    </div>
    
    <!-- Mini Stats for Filtered Period -->
    <div class="mini-stats">
        <div class="mini-stat">
            <div class="mini-stat-value">Rp {{ number_format($omsetFiltered, 0, ',', '.') }}</div>
            <div class="mini-stat-label">Omset ({{ $filterLabel }})</div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-value">{{ $orderCountFiltered }}</div>
            <div class="mini-stat-label">Pesanan ({{ $filterLabel }})</div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
            <div class="mini-stat-label">Rata-rata Transaksi</div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-value" style="color:#27ae60;">{{ $todayOrders }}</div>
            <div class="mini-stat-label">Pesanan Hari Ini</div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-value" style="color:#3498db;">{{ $peakHourLabel }}</div>
            <div class="mini-stat-label">⏰ Jam Tersibuk</div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-value" style="color:#9b59b6;">{{ $busiestDay }}</div>
            <div class="mini-stat-label">📅 Hari Tersibuk</div>
        </div>
    </div>

    <!-- Charts Row 1: Daily Revenue & Hourly Traffic -->
    <div class="charts-grid-2x2">
        <div class="chart-box">
            <div class="chart-header"><h3>📊 Pendapatan 7 Hari Terakhir</h3></div>
            <canvas id="dailyRevenueChart" height="150"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-header"><h3>⏰ Jam Sibuk (Distribusi Pesanan)</h3></div>
            <canvas id="hourlyChart" height="150"></canvas>
        </div>
    </div>
    
    <!-- Charts Row 2: Weekly Trend & Monthly Revenue -->
    <div class="charts-grid-2x2">
        <div class="chart-box">
            <div class="chart-header"><h3>📆 Tren Pesanan per Hari</h3></div>
            <canvas id="weeklyTrendChart" height="150"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-header"><h3>📈 Pendapatan Bulanan ({{ date('Y') }})</h3></div>
            <canvas id="incomeChart" height="150"></canvas>
        </div>
    </div>

    <!-- Charts Row 3: Top Menu & Payment Methods -->
    <div class="dashboard-grid">
        <div class="chart-box">
            <div class="chart-header"><h3>🏆 Top 5 Menu Terlaris</h3></div>
            <canvas id="trendChart" height="200"></canvas>
        </div>
        <div class="chart-box">
            <div class="chart-header"><h3>💳 Metode Pembayaran</h3></div>
            <div style="height: 200px; display:flex; justify-content:center;">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- TAB 2: MANAJEMEN STOK -->
<div id="stok" class="tab-pane">
    <div class="table-container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div>
                <h3>📦 Atur Stok Menu</h3>
                <p style="color:#888; margin:0; font-size:0.9rem;">Pastikan stok selalu update agar pelanggan tidak kecewa.</p>
            </div>
            <a href="{{ route('admin.menu.create') }}" class="btn-update" style="background:var(--primary); text-decoration:none;">+ Tambah Menu Baru</a>
        </div>
        
        <table style="width:100%">
            <thead>
                <tr>
                    <th width="80">Gambar</th>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Sisa Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allMenus as $menu)
                @php $stokAlert = $menu->stok < 10 ? 'background:#fff5f5; color:#e74c3c; border-color:#e74c3c;' : ''; @endphp
                <tr>
                    <td><img src="{{ asset('img/' . $menu->gambar) }}" style="width:50px; height:50px; border-radius:8px; object-fit:cover;"></td>
                    <td style="font-weight:600;">
                        {{ $menu->nama_menu }}
                        <div style="font-size:0.7rem; color:#aaa; font-weight:normal;">{{ Str::limit($menu->deskripsi, 30) }}</div>
                    </td>
                    <td>Rp {{ number_format($menu->harga) }}</td>
                    <td>
                        <input type="number" name="stok" form="update-form-{{ $menu->id }}" class="input-stok" value="{{ $menu->stok }}" min="0" style="{{ $stokAlert }}">
                    </td>
                    <td style="white-space:nowrap;">
                        <form id="update-form-{{ $menu->id }}" method="POST" action="{{ route('admin.stok.update') }}" style="display:inline-block">
                            @csrf
                            <input type="hidden" name="id_menu" value="{{ $menu->id }}">
                            <button type="submit" class="btn-icon btn-save" title="Simpan Stok"><i class="fas fa-check"></i></button>
                        </form>
                        <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn-icon" title="Edit Menu" style="color:#f39c12; display:inline-block;"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.stok.destroy', $menu->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- TAB 3: RIWAYAT PESANAN -->
<div id="pesanan" class="tab-pane">
    <div class="table-container">
        <h3>📝 Daftar Pesanan Masuk</h3>
        <p style="color:#888; margin-bottom:20px; font-size:0.9rem;">Pantau dan update status pesanan pelanggan.</p>
        
        <!-- Search & Filter Controls -->
        <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
            <input type="text" id="searchInput" placeholder="Cari Nama / No. Telp / Menu..." style="padding:10px; border:1px solid #ddd; border-radius:8px; flex:1;">
            
            <select id="dateFilter" style="padding:10px; border:1px solid #ddd; border-radius:8px;">
                <option value="all">📅 Semua Tanggal</option>
                <option value="today">Hari Ini</option>
                <option value="yesterday">Kemarin</option>
                <option value="week">Minggu Ini</option>
                <option value="month">Bulan Ini</option>
            </select>

            <select id="statusFilter" style="padding:10px; border:1px solid #ddd; border-radius:8px;">
                <option value="all">📌 Semua Status</option>
                <option value="Baru">Baru</option>
                <option value="Selesai">Selesai</option>
                <option value="Batal">Batal</option>
            </select>

            <button onclick="filterOrders()" class="btn-update" style="background:var(--primary);">🔍 Filter</button>
            <button onclick="resetFilters()" class="btn-update" style="background:#888;">Reset</button>
        </div>

        <table style="width:100%" id="orderTable">
            <thead>
                <tr>
                    <th>No & Waktu</th>
                    <th>Pembeli</th>
                    <th>Detail Pesanan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPesanan as $order)
                @php 
                    $classStatus = strtolower($order->status_pesanan); 
                    $waktu = \Carbon\Carbon::parse($order->waktu_pesan);
                    $hari = $waktu->translatedFormat('l'); // Monday -> Senin
                @endphp
                <tr class="order-row" 
                    data-date="{{ $waktu->format('Y-m-d') }}" 
                    data-status="{{ $order->status_pesanan }}"
                    data-search="{{ strtolower($order->nama_pembeli . ' ' . $order->no_telepon . ' ' . $order->detail_pesanan) }}">
                    <td>
                        <div style="font-weight:700; color:var(--primary)">#{{ $loop->iteration }}</div>
                        <div style="font-size:0.8rem; font-weight:bold; color:#555;">{{ $hari }}</div>
                        <div style="font-size:0.8rem; color:#aaa;">{{ $waktu->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $order->nama_pembeli ?: 'Anonim' }}</div>
                        <div style="font-size:0.8rem; color:#888;">{{ $order->no_telepon ?: '-' }}</div>
                    </td>
                    <td><div style="font-size:0.85rem; color:#555;">{!! nl2br(e($order->detail_pesanan)) !!}</div></td>
                    <td style="font-weight:bold;">Rp {{ number_format($order->total_harga) }}</td>
                    <td><div style="font-size:0.8rem; background:#eee; padding:3px 8px; border-radius:4px; text-align:center;">{{ strtoupper($order->metode_pembayaran) }}</div></td>
                    <td><span class="badge status-{{ $classStatus }}">{{ $order->status_pesanan }}</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.pesanan.status') }}" style="display:flex; gap:5px;">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <select name="status_baru" style="padding:6px; border-radius:6px; border:1px solid #ddd;">
                                <option value="Baru" {{ $order->status_pesanan == 'Baru' ? 'selected' : '' }}>Baru</option>
                                <option value="Selesai" {{ $order->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Batal" {{ $order->status_pesanan == 'Batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <button type="submit" class="btn-update" title="Simpan Status"><i class="fas fa-check"></i></button>
                        </form>
                        @if($order->status_pesanan == 'Selesai' || $order->status_pesanan == 'Batal')
                        <form action="{{ route('admin.pesanan.destroy', $order->id) }}" method="POST" style="display:inline-block; margin-left:5px;" onsubmit="return confirm('Hapus riwayat pesanan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Hapus Pesanan"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tab Switching Logic
    function openTab(tabName) {
        document.querySelectorAll('.tab-pane').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        document.getElementById(tabName).classList.add('active');
        // Find button that calls this function (rough approximation)
        const buttons = document.querySelectorAll('.tab-btn');
        if(tabName === 'overview') buttons[0].classList.add('active');
        if(tabName === 'stok') buttons[1].classList.add('active');
        if(tabName === 'pesanan') buttons[2].classList.add('active');

        // Update URL hash without scroll
        history.pushState(null, null, '#' + tabName);
    }

    // Check URL Hash on Load
    document.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash.replace('#', '');
        if (hash && (hash === 'stok' || hash === 'pesanan' || hash === 'overview')) {
            openTab(hash);
        }
    });

    // Charts Initialization
    
    // 1. Daily Revenue Chart (Last 7 Days)
    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'bar',
        data: {
            labels: @json($dailyRevenueLabels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($dailyRevenueData),
                backgroundColor: 'rgba(255, 126, 95, 0.8)',
                borderColor: '#ff7e5f',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // 2. Hourly Traffic Chart (Busy Hours)
    new Chart(document.getElementById('hourlyChart'), {
        type: 'line',
        data: {
            labels: @json($hourlyLabels),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: @json($hourlyData),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#3498db',
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { 
                y: { beginAtZero: true },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45,
                        callback: function(val, index) {
                            return index % 3 === 0 ? this.getLabelForValue(val) : '';
                        }
                    }
                }
            }
        }
    });

    // 3. Weekly Trend Chart
    new Chart(document.getElementById('weeklyTrendChart'), {
        type: 'bar',
        data: {
            labels: @json($weeklyTrendLabels),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: @json($weeklyTrendData),
                backgroundColor: [
                    'rgba(231, 76, 60, 0.7)', 'rgba(52, 152, 219, 0.7)', 'rgba(46, 204, 113, 0.7)', 
                    'rgba(155, 89, 182, 0.7)', 'rgba(241, 196, 15, 0.7)', 'rgba(26, 188, 156, 0.7)', 
                    'rgba(230, 126, 34, 0.7)'
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // 4. Monthly Revenue
    new Chart(document.getElementById('incomeChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($pendapatanBulanan),
                borderColor: '#ff7e5f',
                backgroundColor: 'rgba(255, 126, 95, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // 5. Best Seller
    new Chart(document.getElementById('trendChart'), {
        type: 'bar',
        data: {
            labels: @json($labelTrend),
            datasets: [{
                label: 'Terjual (Pcs)',
                data: @json($dataTrend),
                backgroundColor: ['#ff7e5f', '#feb47b', '#3498db', '#2ecc71', '#9b59b6'],
                borderRadius: 5
            }]
        },
        options: { indexAxis: 'y', plugins: { legend: { display: false } } }
    });

    // 6. Payment Method
    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: @json($labelMetode),
            datasets: [{
                data: @json($dataMetode),
                backgroundColor: ['#3498db', '#e74c3c', '#f1c40f', '#2ecc71'],
                borderWidth: 0
            }]
        },
        options: { maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
    });

    // Filter Orders Function
    function filterOrders() {
        const searchText = document.getElementById('searchInput').value.toLowerCase();
        const dateFilter = document.getElementById('dateFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        const rows = document.querySelectorAll('.order-row');
        
        rows.forEach(row => {
            const searchData = row.dataset.search;
            const dateData = row.dataset.date;
            const statusData = row.dataset.status;
            
            let show = true;
            
            // Search Filter
            if (searchText && !searchData.includes(searchText)) {
                show = false;
            }
            
            // Status Filter
            if (statusFilter !== 'all' && statusData !== statusFilter) {
                show = false;
            }
            
            // Date Filter
            if (dateFilter !== 'all') {
                const today = new Date().toISOString().split('T')[0];
                const rowDate = dateData;
                
                if (dateFilter === 'today' && rowDate !== today) show = false;
                if (dateFilter === 'yesterday') {
                    let yesterday = new Date();
                    yesterday.setDate(yesterday.getDate() - 1);
                    if (rowDate !== yesterday.toISOString().split('T')[0]) show = false;
                }
            }
            
            row.style.display = show ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('dateFilter').value = 'all';
        document.getElementById('statusFilter').value = 'all';
        filterOrders();
    }
    
    // Add event listeners for real-time search
    document.getElementById('searchInput').addEventListener('keyup', filterOrders);
    document.getElementById('dateFilter').addEventListener('change', filterOrders);
    document.getElementById('statusFilter').addEventListener('change', filterOrders);

</script>
@endpush
