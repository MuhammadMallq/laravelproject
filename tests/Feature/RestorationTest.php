<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestorationTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_deduction_logic()
    {
        $user = User::factory()->create();
        $menu = Menu::factory()->create(['stok' => 10, 'terjual' => 0, 'nama_menu' => 'Es Teh']);

        // 1. Create Order (Status: Baru) -> Stock should NOT change
        $response = $this->postJson(route('pesanan.store'), [
            'nama_pembeli' => 'Budi',
            'no_telepon' => '08123456789',
            'detail' => 'Es Teh (2x)',
            'total' => 10000,
            'metode' => 'cod',
            'data_stok' => json_encode([['nama' => 'Es Teh', 'jumlah' => 2]])
        ]);

        if ($response->status() !== 200) {
            dd($response->json());
        }
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('pesanan', ['nama_pembeli' => 'Budi', 'status_pesanan' => 'Baru']);
        
        $menu->refresh();
        $this->assertEquals(10, $menu->stok, 'Stock should not decrease on initial order (Baru)');

        $order = Pesanan::where('nama_pembeli', 'Budi')->first();

        // 2. Update Status to Selesai -> Stock should DECREASE
        $response = $this->actingAs($user)->post(route('admin.pesanan.status'), [
            'order_id' => $order->id,
            'status_baru' => 'Selesai'
        ]);

        $menu->refresh();
        if ($menu->stok !== 8) {
            dd("Stock failed to decrease. Current: {$menu->stok}, Expected: 8");
        }
        $this->assertEquals(8, $menu->stok, 'Stock should decrease when status becomes Selesai');
        $this->assertEquals(2, $menu->terjual, 'Terjual count should increase');

        // 3. Update Status to Batal -> Stock should RESTORE
        $response = $this->actingAs($user)->post(route('admin.pesanan.status'), [
            'order_id' => $order->id,
            'status_baru' => 'Batal'
        ]);

        $menu->refresh();
        $this->assertEquals(10, $menu->stok, 'Stock should restore when status becomes Batal');
        $this->assertEquals(0, $menu->terjual, 'Terjual count should decrease');
    }

    public function test_dashboard_analytics_load()
    {
        $user = User::factory()->create();
        
        // Create dummy data
        Pesanan::create([
            'nama_pembeli' => 'Test',
            'detail_pesanan' => 'Es Teh (1x)',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        if ($response->status() !== 200) {
           // Debug info if needed
        }

        $response->assertStatus(200);
        
        $viewData = $response->original->getData();
        \Illuminate\Support\Facades\Log::info('Dashboard View Keys:', array_keys($viewData));

        $keys = [
            'omset',
            'dailyRevenueLabels',
            'dailyRevenueData',
            'hourlyLabels',
            'hourlyData',
            'weeklyTrendLabels',
            'weeklyTrendData',
            'filterLabel'
        ];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $viewData)) {
                 \Illuminate\Support\Facades\Log::error("Missing Key: $key");
            }
            $response->assertViewHas($key);
        }
    }
}
