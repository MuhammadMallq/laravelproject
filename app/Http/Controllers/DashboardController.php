<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Card Statistics
        $totalMenu = Menu::count();
        $totalOrder = Pesanan::count();
        $stokMenipis = Menu::where('stok', '<', 10)->count();
        
        $omset = Pesanan::where('status_pesanan', 'Selesai')->sum('total_harga') ?? 0;

        // Payment Methods Chart Data
        $metodeData = Pesanan::selectRaw('metode_pembayaran, COUNT(*) as jumlah')
            ->groupBy('metode_pembayaran')
            ->get();
        
        $labelMetode = $metodeData->pluck('metode_pembayaran')->map(fn($m) => strtoupper($m))->toArray();
        $dataMetode = $metodeData->pluck('jumlah')->toArray();

        // Monthly Revenue Chart Data
        $tahunIni = date('Y');
        $pendapatanBulanan = array_fill(0, 12, 0);
        
        $bulananData = Pesanan::selectRaw('MONTH(waktu_pesan) as bulan, SUM(total_harga) as total')
            ->whereYear('waktu_pesan', $tahunIni)
            ->where('status_pesanan', 'Selesai')
            ->groupBy(DB::raw('MONTH(waktu_pesan)'))
            ->get();
        
        foreach ($bulananData as $row) {
            $pendapatanBulanan[$row->bulan - 1] = (int) $row->total;
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

        return view('admin.dashboard', compact(
            'totalMenu',
            'totalOrder',
            'stokMenipis',
            'omset',
            'labelMetode',
            'dataMetode',
            'pendapatanBulanan',
            'labelTrend',
            'dataTrend',
            'lastOrders',
            'allMenus',
            'allPesanan'
        ));
    }
}
