<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_dashboard()
    {
        $this->withSession(['admin' => 'admin']);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_dashboard_displays_statistics()
    {
        $this->withSession(['admin' => 'admin']);

        // Create test data
        Menu::create([
            'nama_menu' => 'Test Menu',
            'harga' => 5000,
            'deskripsi' => 'Test',
            'gambar' => 'test.jpg',
            'stok' => 10,
            'terjual' => 5
        ]);

        Pesanan::create([
            'detail_pesanan' => 'Test Menu (1x)',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalMenu', 1);
        $response->assertViewHas('totalOrder', 1);
        $response->assertViewHas('omset', 5000);
    }

    public function test_dashboard_shows_low_stock_count()
    {
        $this->withSession(['admin' => 'admin']);

        // Create low stock menu
        Menu::create([
            'nama_menu' => 'Low Stock Menu',
            'harga' => 5000,
            'deskripsi' => 'Test',
            'gambar' => 'test.jpg',
            'stok' => 5, // Below 10
            'terjual' => 0
        ]);

        // Create normal stock menu
        Menu::create([
            'nama_menu' => 'Normal Stock Menu',
            'harga' => 5000,
            'deskripsi' => 'Test',
            'gambar' => 'test.jpg',
            'stok' => 50, // Above 10
            'terjual' => 0
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stokMenipis', 1);
    }

    public function test_dashboard_shows_payment_method_data()
    {
        $this->withSession(['admin' => 'admin']);

        Pesanan::create([
            'detail_pesanan' => 'Test 1',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        Pesanan::create([
            'detail_pesanan' => 'Test 2',
            'total_harga' => 10000,
            'metode_pembayaran' => 'transfer',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('labelMetode');
        $response->assertViewHas('dataMetode');
    }

    public function test_dashboard_shows_monthly_revenue()
    {
        $this->withSession(['admin' => 'admin']);

        // Create order for current month
        Pesanan::create([
            'detail_pesanan' => 'Monthly Test',
            'total_harga' => 15000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('pendapatanBulanan');
        
        $pendapatan = $response->viewData('pendapatanBulanan');
        $currentMonth = (int) date('m') - 1;
        $this->assertEquals(15000, $pendapatan[$currentMonth]);
    }

    public function test_dashboard_shows_top_sellers()
    {
        $this->withSession(['admin' => 'admin']);

        Menu::create([
            'nama_menu' => 'Best Seller',
            'harga' => 5000,
            'deskripsi' => 'Test',
            'gambar' => 'best.jpg',
            'stok' => 10,
            'terjual' => 100
        ]);

        Menu::create([
            'nama_menu' => 'Second Best',
            'harga' => 5000,
            'deskripsi' => 'Test',
            'gambar' => 'second.jpg',
            'stok' => 10,
            'terjual' => 50
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('labelTrend');
        $response->assertViewHas('dataTrend');
        
        $labels = $response->viewData('labelTrend');
        $this->assertEquals('Best Seller', $labels[0]);
    }

    public function test_dashboard_shows_last_orders()
    {
        $this->withSession(['admin' => 'admin']);

        Pesanan::create([
            'detail_pesanan' => 'Order 1',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()->subHours(2)
        ]);

        Pesanan::create([
            'detail_pesanan' => 'Order 2',
            'total_harga' => 10000,
            'metode_pembayaran' => 'transfer',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('lastOrders');
    }

    public function test_dashboard_shows_all_menus()
    {
        $this->withSession(['admin' => 'admin']);

        Menu::create([
            'nama_menu' => 'Menu 1',
            'harga' => 5000,
            'deskripsi' => 'Test',
            'gambar' => 'test.jpg',
            'stok' => 10,
            'terjual' => 0
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('allMenus');
    }

    public function test_dashboard_shows_all_orders()
    {
        $this->withSession(['admin' => 'admin']);

        Pesanan::create([
            'detail_pesanan' => 'All Order Test',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('allPesanan');
    }
}
