<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_users_can_view_menu_page()
    {
        $response = $this->get(route('menu.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.menu');
    }

    public function test_admin_can_create_menu()
    {
        Storage::fake('public');

        // Simulate Admin Login
        $this->withSession(['admin' => 'admin']);

        $file = UploadedFile::fake()->create('tea.jpg');

        $response = $this->post(route('admin.menu.store'), [
            'nama_menu' => 'Jasmine Tea',
            'harga' => 5000,
            'deskripsi' => 'Teh melati segar',
            'gambar' => $file,
            'stok' => 10,
        ]);

        $this->assertDatabaseHas('menu', [
            'nama_menu' => 'Jasmine Tea',
            'harga' => 5000,
        ]);

        // Expect redirect to dashboard hash #stok
        $response->assertRedirect(route('admin.dashboard') . '#stok');
    }

    public function test_admin_can_update_menu()
    {
        Storage::fake('public');
        $this->withSession(['admin' => 'admin']);

        $menu = Menu::create([
            'nama_menu' => 'Old Tea',
            'harga' => 3000,
            'deskripsi' => 'Old desc',
            'gambar' => 'old.jpg',
            'stok' => 5,
            'terjual' => 0
        ]);

        $response = $this->put(route('admin.menu.update', $menu->id), [
            'nama_menu' => 'New Tea',
            'harga' => 4000,
            'deskripsi' => 'New desc',
        ]);

        $this->assertDatabaseHas('menu', [
            'id' => $menu->id,
            'nama_menu' => 'New Tea',
            'harga' => 4000,
        ]);

        $response->assertRedirect(route('admin.dashboard') . '#stok');
    }

    public function test_admin_can_delete_menu()
    {
        $this->withSession(['admin' => 'admin']);

        $menu = Menu::create([
            'nama_menu' => 'Delete Me',
            'harga' => 3000,
            'deskripsi' => 'desc',
            'gambar' => 'del.jpg',
            'stok' => 5,
            'terjual' => 0
        ]);

        $response = $this->delete(route('admin.stok.destroy', $menu->id));

        $this->assertDatabaseMissing('menu', ['id' => $menu->id]);
        $response->assertRedirect(route('admin.dashboard') . '#stok');
    }
}
