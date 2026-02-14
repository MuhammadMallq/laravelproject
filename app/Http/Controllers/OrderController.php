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

        // Jika status tidak berubah, kembalikan saja
        if ($statusLama == $statusBaru) {
            return redirect()->back()->with('success', 'Status pesanan tidak berubah.');
        }

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
                    // 1. Jika status berubah menjadi 'Selesai' (dari Baru/Batal)
                    //    -> Kurangi Stok, Tambah Terjual
                    if ($statusBaru == 'Selesai') {
                        $menu->decrement('stok', $qty);
                        $menu->increment('terjual', $qty);
                    }
                    
                    // 2. Jika status berubah DARI 'Selesai' (ke Baru/Batal)
                    //    -> Kembalikan Stok, Kurangi Terjual
                    elseif ($statusLama == 'Selesai') {
                        $menu->increment('stok', $qty);
                        $menu->decrement('terjual', $qty);
                    }
                    
                    // Catatan: Perubahan antara 'Baru' <-> 'Batal' tidak mempengaruhi stok
                    // karena stok hanya berkurang saat 'Selesai'
                }
            }
        }

        return redirect()->to(route('admin.dashboard') . '#pesanan')->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nama_pembeli' => 'required|string|max:255',
                'no_telepon'   => 'required|string|max:20',
                'detail'       => 'required',
                'total'        => 'required',
                'metode'       => 'required',
                'data_stok'    => 'required|json'
            ]);

            $detail = $request->input('detail');
            $total = $request->input('total');
            $metode = $request->input('metode');
            $namaPembeli = $request->input('nama_pembeli');
            $noTelepon = $request->input('no_telepon');
            $dataStok = json_decode($request->input('data_stok'), true);

            // Note: Stok tidak dikurangi saat pemesanan status 'Baru'.
            // Stok hanya berkurang saat status diupdate menjadi 'Selesai' oleh admin.
            
            /* 
            // OLD LOGIC: Deduct stock immediately
            if (is_array($dataStok)) {
                foreach ($dataStok as $item) {
                    $menu = Menu::where('nama_menu', $item['nama'])->first();
                    if ($menu) {
                        $menu->decrement('stok', $item['jumlah']);
                    }
                }
            } 
            */

            // Create new order
            Pesanan::create([
                'nama_pembeli' => $namaPembeli,
                'no_telepon' => $noTelepon,
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

        if ($order->status_pesanan == 'Selesai') {
            $items = explode(', ', $order->detail_pesanan);
            foreach ($items as $item) {
                $item = rtrim(trim($item), ',');
                if (preg_match('/^(.*?) \((\d+)x\)$/', $item, $matches)) {
                    $namaMenu = trim($matches[1]);
                    $qty = (int) $matches[2];
                    $menu = Menu::where('nama_menu', $namaMenu)->first();
                    if ($menu) {
                        $menu->decrement('terjual', $qty);
                        $menu->increment('stok', $qty); // Restore stock
                    }
                }
            }
        }

        $order->delete();

        return redirect()->to(route('admin.dashboard') . '#pesanan')->with('success', 'Pesanan berhasil dihapus!');
    }
}
