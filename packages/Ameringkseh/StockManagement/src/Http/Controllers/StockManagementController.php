<?php

namespace Ameringkseh\StockManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class StockManagementController extends Controller
{
    public function index()
    {
        // Using existing App\Models\Menu since this is a feature extraction
        $menus = Menu::orderBy('stok', 'asc')->get();
        return view('stock-management::stok', compact('menus'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_menu' => 'required|exists:menu,id',
            'stok' => 'required|integer|min:0',
        ]);

        $menu = Menu::findOrFail($request->id_menu);
        $menu->update(['stok' => $request->stok]);

        // Redirect logic might need adjustment if routes change, but using named route 'admin.dashboard' assumes main app integration
        // For standalone, we can redirect back or to a package route. 
        // Keeping it consistent with original for now, assuming main app context.
        return redirect()->back()->with('success', 'Stok berhasil diupdate!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        
        if (file_exists(public_path('img/' . $menu->gambar))) {
            unlink(public_path('img/' . $menu->gambar));
        }
        
        $menu->delete();

        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }
}
