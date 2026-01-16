<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_place_order()
    {
        $menu = Menu::create([
            'nama_menu' => 'Test Tea',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'test.jpg',
            'stok' => 10,
            'terjual' => 0
        ]);

        $dataStok = json_encode([
            ['nama' => 'Test Tea', 'jumlah' => 2]
        ]);

        $response = $this->post(route('order.store'), [
            'detail' => 'Test Tea (2x)',
            'total' => 10000,
            'metode' => 'cod',
            'data_stok' => $dataStok
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('pesanan', [
            'total_harga' => 10000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru'
        ]);

        // Stock should decrease
        $this->assertDatabaseHas('menu', [
            'id' => $menu->id,
            'stok' => 8 // 10 - 2
        ]);
    }

    public function test_admin_can_update_order_status()
    {
        $this->withSession(['admin' => 'admin']);

        $order = Pesanan::create([
            'detail_pesanan' => 'Test Tea (1x)',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $response = $this->post(route('admin.pesanan.status'), [
            'order_id' => $order->id,
            'status_baru' => 'Selesai'
        ]);

        $this->assertDatabaseHas('pesanan', [
            'id' => $order->id,
            'status_pesanan' => 'Selesai'
        ]);

        $response->assertRedirect(route('admin.dashboard') . '#pesanan');
    }

    public function test_admin_can_delete_completed_order()
    {
        $this->withSession(['admin' => 'admin']);

        $order = Pesanan::create([
            'detail_pesanan' => 'Completed Order',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $response = $this->delete(route('admin.pesanan.destroy', $order->id));

        $this->assertDatabaseMissing('pesanan', ['id' => $order->id]);
        $response->assertRedirect(route('admin.dashboard') . '#pesanan');
    }
}
