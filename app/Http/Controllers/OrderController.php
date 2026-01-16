<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $totalPesanan = Pesanan::count();
        $totalPendapatan = Pesanan::where('status_pesanan', 'Selesai')->sum('total_harga') ?? 0;
        $pesanan = Pesanan::orderBy('waktu_pesan', 'desc')->get();

        return view('admin.pesanan', compact('totalPesanan', 'totalPendapatan', 'pesanan'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:pesanan,id',
            'status_baru' => 'required|in:Baru,Selesai,Batal',
        ]);

        $pesanan = Pesanan::findOrFail($request->order_id);
        $statusLama = $pesanan->status_pesanan;
        $statusBaru = $request->status_baru;
        $detailPesanan = $pesanan->detail_pesanan;

        // Update status
        $pesanan->update(['status_pesanan' => $statusBaru]);

        // Process stock and sold count corrections
        $items = explode(', ', $detailPesanan);
        
        foreach ($items as $item) {
            $item = rtrim(trim($item), ',');
            if (preg_match('/^(.*?) \((\d+)x\)$/', $item, $matches)) {
                $namaMenu = trim($matches[1]);
                $qty = (int) $matches[2];
                $menu = Menu::where('nama_menu', $namaMenu)->first();
                
                if ($menu) {
                    // Case A: Cancelled or reset (Selesai -> Batal/Baru)
                    if ($statusLama == 'Selesai' && $statusBaru != 'Selesai') {
                        $menu->decrement('terjual', $qty);
                    }
                    
                    // Case B: Completed (Baru/Batal -> Selesai)
                    elseif ($statusBaru == 'Selesai' && $statusLama != 'Selesai') {
                        $menu->increment('terjual', $qty);
                    }

                    // Case C: Restock (Baru/Selesai -> Batal)
                    if ($statusBaru == 'Batal' && $statusLama != 'Batal') {
                        $menu->increment('stok', $qty);
                    }
                    // Case D: Cancel reverted (Batal -> Baru/Selesai)
                    elseif ($statusLama == 'Batal' && $statusBaru != 'Batal') {
                        $menu->decrement('stok', $qty);
                    }
                }
            }
        }

        return redirect()->to(route('admin.dashboard') . '#pesanan')->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'detail' => 'required',
                'total' => 'required',
                'metode' => 'required',
                'data_stok' => 'required|json'
            ]);

            $detail = $request->input('detail');
            $total = $request->input('total');
            $metode = $request->input('metode');
            $dataStok = json_decode($request->input('data_stok'), true);

            // Update stock for each item
            if (is_array($dataStok)) {
                foreach ($dataStok as $item) {
                    $menu = Menu::where('nama_menu', $item['nama'])->first();
                    if ($menu) {
                        $menu->decrement('stok', $item['jumlah']);
                    }
                }
            }

            // Create new order
            Pesanan::create([
                'detail_pesanan' => $detail,
                'total_harga' => $total,
                'metode_pembayaran' => $metode,
                'status_pesanan' => 'Baru',
                'waktu_pesan' => now(),
            ]);

            return response()->json(['message' => 'Berhasil'], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Gagal: ' . $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        $order = Pesanan::findOrFail($id);

        // Decrement 'terjual' if order was completed
        if ($order->status_pesanan == 'Selesai') {
            $items = explode(', ', $order->detail_pesanan);
            foreach ($items as $item) {
                $item = rtrim(trim($item), ',');
                if (preg_match('/^(.*?) \((\d+)x\)$/', $item, $matches)) {
                    $namaMenu = trim($matches[1]);
                    $qty = (int) $matches[2];
                    Menu::where('nama_menu', $namaMenu)->decrement('terjual', $qty);
                }
            }
        }

        $order->delete();

        return redirect()->to(route('admin.dashboard') . '#pesanan')->with('success', 'Pesanan berhasil dihapus!');
    }
}
