<?php

namespace Tests\Unit;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    // ========== MENU MODEL TESTS ==========
    
    public function test_menu_can_be_created()
    {
        $menu = Menu::create([
            'nama_menu' => 'Ice Tea Original',
            'harga' => 5000,
            'deskripsi' => 'Teh manis segar',
            'gambar' => 'original.jpg',
            'stok' => 10,
            'terjual' => 0
        ]);

        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals('Ice Tea Original', $menu->nama_menu);
        $this->assertEquals(5000, $menu->harga);
    }

    public function test_menu_has_correct_fillable_attributes()
    {
        $menu = new Menu();
        
        $this->assertEquals([
            'nama_menu',
            'harga',
            'deskripsi',
            'gambar',
            'stok',
            'terjual',
        ], $menu->getFillable());
    }

    public function test_menu_uses_correct_table()
    {
        $menu = new Menu();
        $this->assertEquals('menu', $menu->getTable());
    }

    public function test_menu_has_no_timestamps()
    {
        $menu = new Menu();
        $this->assertFalse($menu->timestamps);
    }

    public function test_menu_can_be_updated()
    {
        $menu = Menu::create([
            'nama_menu' => 'Old Menu',
            'harga' => 3000,
            'deskripsi' => 'Old desc',
            'gambar' => 'old.jpg',
            'stok' => 5,
            'terjual' => 0
        ]);

        $menu->update(['nama_menu' => 'New Menu', 'harga' => 4000]);

        $this->assertEquals('New Menu', $menu->fresh()->nama_menu);
        $this->assertEquals(4000, $menu->fresh()->harga);
    }

    public function test_menu_can_be_deleted()
    {
        $menu = Menu::create([
            'nama_menu' => 'Delete Menu',
            'harga' => 3000,
            'deskripsi' => 'Desc',
            'gambar' => 'del.jpg',
            'stok' => 5,
            'terjual' => 0
        ]);

        $menuId = $menu->id;
        $menu->delete();

        $this->assertNull(Menu::find($menuId));
    }

    // ========== PESANAN MODEL TESTS ==========
    
    public function test_pesanan_can_be_created()
    {
        $pesanan = Pesanan::create([
            'detail_pesanan' => 'Ice Tea Original (2x)',
            'total_harga' => 10000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $this->assertInstanceOf(Pesanan::class, $pesanan);
        $this->assertEquals('Ice Tea Original (2x)', $pesanan->detail_pesanan);
        $this->assertEquals(10000, $pesanan->total_harga);
    }

    public function test_pesanan_has_correct_fillable_attributes()
    {
        $pesanan = new Pesanan();
        
        $this->assertEquals([
            'detail_pesanan',
            'total_harga',
            'metode_pembayaran',
            'status_pesanan',
            'waktu_pesan',
        ], $pesanan->getFillable());
    }

    public function test_pesanan_uses_correct_table()
    {
        $pesanan = new Pesanan();
        $this->assertEquals('pesanan', $pesanan->getTable());
    }

    public function test_pesanan_casts_waktu_pesan_to_datetime()
    {
        $pesanan = Pesanan::create([
            'detail_pesanan' => 'Test',
            'total_harga' => 5000,
            'metode_pembayaran' => 'transfer',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => '2026-01-27 10:00:00'
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $pesanan->waktu_pesan);
    }

    public function test_pesanan_can_be_updated()
    {
        $pesanan = Pesanan::create([
            'detail_pesanan' => 'Test',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $pesanan->update(['status_pesanan' => 'Selesai']);

        $this->assertEquals('Selesai', $pesanan->fresh()->status_pesanan);
    }

    // ========== ADMIN MODEL TESTS ==========
    
    public function test_admin_can_be_created()
    {
        $admin = Admin::create([
            'username' => 'testadmin',
            'password' => 'password123'
        ]);

        $this->assertInstanceOf(Admin::class, $admin);
        $this->assertEquals('testadmin', $admin->username);
    }

    public function test_admin_has_correct_fillable_attributes()
    {
        $admin = new Admin();
        
        $this->assertEquals([
            'username',
            'password',
        ], $admin->getFillable());
    }

    public function test_admin_uses_correct_table()
    {
        $admin = new Admin();
        $this->assertEquals('admin', $admin->getTable());
    }

    public function test_admin_has_no_timestamps()
    {
        $admin = new Admin();
        $this->assertFalse($admin->timestamps);
    }

    // ========== USER MODEL TESTS ==========
    
    public function test_user_can_be_created()
    {
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $this->assertInstanceOf(\App\Models\User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    public function test_user_has_correct_fillable_attributes()
    {
        $user = new \App\Models\User();
        
        $this->assertEquals([
            'name',
            'email',
            'password',
        ], $user->getFillable());
    }

    public function test_user_has_correct_hidden_attributes()
    {
        $user = new \App\Models\User();
        
        $this->assertEquals([
            'password',
            'remember_token',
        ], $user->getHidden());
    }

    public function test_user_casts_are_defined()
    {
        $user = new \App\Models\User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);
    }

    public function test_user_password_is_hashed()
    {
        $user = \App\Models\User::create([
            'name' => 'Hash Test',
            'email' => 'hash@example.com',
            'password' => 'plainpassword'
        ]);

        // Password should be hashed, not plain
        $this->assertNotEquals('plainpassword', $user->password);
    }

    // ========== MENU MODEL NEW METHODS TESTS ==========

    public function test_menu_formatted_harga_attribute()
    {
        $menu = Menu::create([
            'nama_menu' => 'Test Formatted',
            'harga' => 15000,
            'deskripsi' => 'Desc',
            'gambar' => 'test.jpg',
            'stok' => 10,
            'terjual' => 0
        ]);

        $this->assertEquals('Rp 15.000', $menu->formatted_harga);
    }

    public function test_menu_scope_stok_rendah()
    {
        Menu::create([
            'nama_menu' => 'Low Stock',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'low.jpg',
            'stok' => 5,
            'terjual' => 0
        ]);

        Menu::create([
            'nama_menu' => 'High Stock',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'high.jpg',
            'stok' => 50,
            'terjual' => 0
        ]);

        $lowStock = Menu::stokRendah(10)->get();
        $this->assertEquals(1, $lowStock->count());
        $this->assertEquals('Low Stock', $lowStock->first()->nama_menu);
    }

    public function test_menu_is_available()
    {
        $available = Menu::create([
            'nama_menu' => 'Available',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'avail.jpg',
            'stok' => 10,
            'terjual' => 0
        ]);

        $notAvailable = Menu::create([
            'nama_menu' => 'Not Available',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'not.jpg',
            'stok' => 0,
            'terjual' => 5
        ]);

        $this->assertTrue($available->isAvailable());
        $this->assertFalse($notAvailable->isAvailable());
    }

    public function test_menu_get_total_pendapatan()
    {
        $menu = Menu::create([
            'nama_menu' => 'Revenue Test',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'rev.jpg',
            'stok' => 10,
            'terjual' => 10
        ]);

        $this->assertEquals(50000, $menu->getTotalPendapatan());
    }

    // ========== PESANAN MODEL NEW METHODS TESTS ==========

    public function test_pesanan_is_selesai()
    {
        $selesai = Pesanan::create([
            'detail_pesanan' => 'Test',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $baru = Pesanan::create([
            'detail_pesanan' => 'Test 2',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $this->assertTrue($selesai->isSelesai());
        $this->assertFalse($baru->isSelesai());
    }

    public function test_pesanan_is_baru()
    {
        $baru = Pesanan::create([
            'detail_pesanan' => 'Test',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $selesai = Pesanan::create([
            'detail_pesanan' => 'Test 2',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $this->assertTrue($baru->isBaru());
        $this->assertFalse($selesai->isBaru());
    }

    public function test_pesanan_formatted_total_attribute()
    {
        $pesanan = Pesanan::create([
            'detail_pesanan' => 'Test',
            'total_harga' => 25000,
            'metode_pembayaran' => 'transfer',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        $this->assertEquals('Rp 25.000', $pesanan->formatted_total);
    }

    public function test_pesanan_scope_by_status()
    {
        Pesanan::create([
            'detail_pesanan' => 'Baru 1',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Baru',
            'waktu_pesan' => now()
        ]);

        Pesanan::create([
            'detail_pesanan' => 'Selesai 1',
            'total_harga' => 5000,
            'metode_pembayaran' => 'cod',
            'status_pesanan' => 'Selesai',
            'waktu_pesan' => now()
        ]);

        $baruOrders = Pesanan::byStatus('Baru')->get();
        $selesaiOrders = Pesanan::byStatus('Selesai')->get();

        $this->assertEquals(1, $baruOrders->count());
        $this->assertEquals(1, $selesaiOrders->count());
    }

    // ========== ADMIN MODEL NEW METHODS TESTS ==========

    public function test_admin_check_password()
    {
        $admin = Admin::create([
            'username' => 'testadmin',
            'password' => 'secret123'
        ]);

        $this->assertTrue($admin->checkPassword('secret123'));
        $this->assertFalse($admin->checkPassword('wrongpassword'));
    }

    public function test_admin_display_name_attribute()
    {
        $admin = Admin::create([
            'username' => 'johndoe',
            'password' => 'password'
        ]);

        $this->assertEquals('Johndoe', $admin->display_name);
    }
}

