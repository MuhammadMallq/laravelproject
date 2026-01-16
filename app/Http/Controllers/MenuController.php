<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        $isAdmin = session('admin') ? true : false;
        return view('public.menu', compact('menus', 'isAdmin'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
        $request->file('gambar')->move(public_path('img'), $filename);

        Menu::create([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'gambar' => $filename,
            'stok' => $request->stok ?? 0,
            'terjual' => 0,
        ]);

        return redirect()->to(route('admin.dashboard') . '#stok')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('gambar')) {
            // Delete old image
            if (file_exists(public_path('img/' . $menu->gambar))) {
                unlink(public_path('img/' . $menu->gambar));
            }
            
            $filename = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('img'), $filename);
            $data['gambar'] = $filename;
        }

        $menu->update($data);

        return redirect()->to(route('admin.dashboard') . '#stok')->with('success', 'Menu berhasil diupdate!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Delete image file
        if (file_exists(public_path('img/' . $menu->gambar))) {
            unlink(public_path('img/' . $menu->gambar));
        }
        
        $menu->delete();

        return redirect()->to(route('admin.dashboard') . '#stok')->with('success', 'Menu berhasil dihapus!');
    }
}
