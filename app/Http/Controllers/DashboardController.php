<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter period from request
        $period = $request->get('period', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        // Base query for completed orders
        $ordersQuery = Pesanan::where('status_pesanan', 'Selesai');
        
        // Apply period filter
        $filterLabel = 'Semua Waktu';
        $today = \Carbon\Carbon::today();
        
        switch ($period) {
            case 'today':
                $ordersQuery->whereDate('waktu_pesan', $today);
                $filterLabel = 'Hari Ini (' . $today->format('d/m/Y') . ')';
                break;
            case 'yesterday':
                $ordersQuery->whereDate('waktu_pesan', $today->copy()->subDay());
                $filterLabel = 'Kemarin';
                break;
            case 'week':
                $ordersQuery->whereBetween('waktu_pesan', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
                $filterLabel = 'Minggu Ini';
                break;
            case 'month':
                $ordersQuery->whereMonth('waktu_pesan', $today->month)->whereYear('waktu_pesan', $today->year);
                $filterLabel = 'Bulan Ini (' . $today->format('F Y') . ')';
                break;
            case 'year':
                $ordersQuery->whereYear('waktu_pesan', $today->year);
                $filterLabel = 'Tahun Ini (' . $today->year . ')';
                break;
            case 'custom':
                if ($dateFrom && $dateTo) {
                    $ordersQuery->whereBetween('waktu_pesan', [
                        \Carbon\Carbon::parse($dateFrom)->startOfDay(),
                        \Carbon\Carbon::parse($dateTo)->endOfDay()
                    ]);
                    $filterLabel = \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($dateTo)->format('d/m/Y');
                }
                break;
        }
        
        $filteredOrders = $ordersQuery->get();
        
        // Card Statistics (filtered)
        $totalMenu = Menu::count();
        $totalOrder = Pesanan::count();
        $stokMenipis = Menu::where('stok', '<', 10)->count();
        $omsetFiltered = $filteredOrders->sum('total_harga');
        $omset = Pesanan::where('status_pesanan', 'Selesai')->sum('total_harga') ?? 0;
        $orderCountFiltered = $filteredOrders->count();

        // Payment Methods Chart Data (filtered)
        $metodeData = $filteredOrders->groupBy('metode_pembayaran')->map->count();
        $labelMetode = $metodeData->keys()->map(fn($m) => strtoupper($m))->toArray();
        $dataMetode = $metodeData->values()->toArray();
        
        // Handle empty data
        if (empty($labelMetode)) {
            $labelMetode = ['Tidak Ada Data'];
            $dataMetode = [0];
        }

        // ========================================
        // DAILY REVENUE - Last 7 days
        // ========================================
        $dailyRevenueLabels = [];
        $dailyRevenueData = [];
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dayName = $dayNames[$date->dayOfWeek];
            $dailyRevenueLabels[] = $dayName . ' ' . $date->format('d/m');
            
            $revenue = Pesanan::where('status_pesanan', 'Selesai')
                ->whereDate('waktu_pesan', $date)
                ->sum('total_harga');
            $dailyRevenueData[] = (int) $revenue;
        }

        // ========================================
        // HOURLY BUSY CHART - Orders per hour
        // ========================================
        $hourlyLabels = [];
        $hourlyData = [];
        
        for ($h = 0; $h < 24; $h++) {
            $hourlyLabels[] = sprintf('%02d:00', $h);
            $hourlyData[$h] = 0;
        }
        
        // Get all completed orders and count by hour
        $allCompletedOrders = Pesanan::where('status_pesanan', 'Selesai')->get();
        foreach ($allCompletedOrders as $order) {
            if ($order->waktu_pesan) {
                $hour = (int) $order->waktu_pesan->format('H');
                $hourlyData[$hour]++;
            }
        }
        $hourlyData = array_values($hourlyData);
        
        // Find peak hour
        $peakHour = array_search(max($hourlyData), $hourlyData);
        $peakHourLabel = sprintf('%02d:00 - %02d:00', $peakHour, ($peakHour + 1) % 24);

        // ========================================
        // WEEKLY TREND - Orders per day of week
        // ========================================
        $weeklyTrendLabels = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $weeklyTrendData = array_fill(0, 7, 0);
        
        foreach ($allCompletedOrders as $order) {
            if ($order->waktu_pesan) {
                $dayOfWeek = $order->waktu_pesan->dayOfWeek;
                $weeklyTrendData[$dayOfWeek]++;
            }
        }
        
        // Find busiest day
        $busiestDayIndex = array_search(max($weeklyTrendData), $weeklyTrendData);
        $busiestDay = $weeklyTrendLabels[$busiestDayIndex];

        // Monthly Revenue Chart Data
        $tahunIni = date('Y');
        $pendapatanBulanan = array_fill(0, 12, 0);
        
        $bulananData = Pesanan::whereYear('waktu_pesan', $tahunIni)
            ->where('status_pesanan', 'Selesai')
            ->get()
            ->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->waktu_pesan)->format('m');
            });

        foreach ($bulananData as $month => $orders) {
            $pendapatanBulanan[(int)$month - 1] = $orders->sum('total_harga');
        }

        // Top 5 Best Sellers Chart Data
        $trendData = Menu::orderBy('terjual', 'desc')->take(5)->get();
        $labelTrend = $trendData->pluck('nama_menu')->toArray();
        $dataTrend = $trendData->pluck('terjual')->toArray();

        // Last 5 Orders
        $lastOrders = Pesanan::orderBy('waktu_pesan', 'desc')->take(5)->get();

        // Data for Tabs
        $allMenus = Menu::orderBy('stok', 'asc')->get();
        $allPesanan = Pesanan::orderBy('waktu_pesan', 'desc')->get();
        
        // Additional stats
        $avgOrderValue = $filteredOrders->count() > 0 
            ? round($filteredOrders->sum('total_harga') / $filteredOrders->count()) 
            : 0;
        
        $todayOrders = Pesanan::whereDate('waktu_pesan', $today)->count();
        $todayRevenue = Pesanan::where('status_pesanan', 'Selesai')
            ->whereDate('waktu_pesan', $today)
            ->sum('total_harga');

        return view('admin.dashboard', compact(
            'totalMenu',
            'totalOrder',
            'stokMenipis',
            'omset',
            'omsetFiltered',
            'orderCountFiltered',
            'labelMetode',
            'dataMetode',
            'pendapatanBulanan',
            'labelTrend',
            'dataTrend',
            'lastOrders',
            'allMenus',
            'allPesanan',
            'dailyRevenueLabels',
            'dailyRevenueData',
            'hourlyLabels',
            'hourlyData',
            'peakHourLabel',
            'weeklyTrendLabels',
            'weeklyTrendData',
            'busiestDay',
            'filterLabel',
            'period',
            'avgOrderValue',
            'todayOrders',
            'todayRevenue'
        ));
    }
}
