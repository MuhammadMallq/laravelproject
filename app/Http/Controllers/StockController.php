<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('stok', 'asc')->get();
        return view('admin.stok', compact('menus'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_menu' => 'required|exists:menu,id',
            'stok' => 'required|integer|min:0',
        ]);

        $menu = Menu::findOrFail($request->id_menu);
        $menu->update(['stok' => $request->stok]);

        return redirect()->to(route('admin.dashboard') . '#stok')->with('success', 'Stok berhasil diupdate!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        if (file_exists(public_path('img/' . $menu->gambar))) {
            unlink(public_path('img/' . $menu->gambar));
        }
        
        $menu->delete();

        return redirect()->to(route('admin.dashboard') . '#stok')->with('success', 'Menu berhasil dihapus!');
    }
}
