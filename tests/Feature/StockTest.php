<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_stock()
    {
        $this->withSession(['admin' => 'admin']);

        $menu = Menu::create([
            'nama_menu' => 'Stock Tea',
            'harga' => 5000,
            'deskripsi' => 'Desc',
            'gambar' => 'stock.jpg',
            'stok' => 10,
            'terjual' => 0
        ]);

        $response = $this->post(route('admin.stok.update'), [
            'id_menu' => $menu->id,
            'stok' => 50
        ]);

        $this->assertDatabaseHas('menu', [
            'id' => $menu->id,
            'stok' => 50
        ]);

        $response->assertRedirect(route('admin.dashboard') . '#stok');
    }
}
