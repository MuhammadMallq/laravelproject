<?php

namespace Ameringkseh\StockManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockManagementController extends Controller
{
    public function index()
    {
        $modelClass = config('stock-management.model');
        $menus = $modelClass::orderBy('stok', 'asc')->get();
        return view('stock-management::stok', compact('menus'));
    }

    public function update(Request $request)
    {
        $modelClass = config('stock-management.model');
        // Get table name for validation if possible, or assume 'menu' if not easily available without instantiation
        // Ideally: (new $modelClass)->getTable()
        $tableName = (new $modelClass)->getTable();

        $request->validate([
            'id_menu' => "required|exists:{$tableName},id",
            'stok' => 'required|integer|min:0',
        ]);

        $menu = $modelClass::findOrFail($request->id_menu);
        $menu->update(['stok' => $request->stok]);

        return redirect()->back()->with('success', 'Stok berhasil diupdate!');
    }

    public function destroy($id)
    {
        $modelClass = config('stock-management.model');
        $menu = $modelClass::findOrFail($id);
        
        // Check if 'gambar' exists on the model instance (dynamic access)
        if (isset($menu->gambar) && file_exists(public_path('img/' . $menu->gambar))) {
            unlink(public_path('img/' . $menu->gambar));
        }
        
        $menu->delete();

        return redirect()->back()->with('success', 'Menu berhasil dihapus!');
    }
}
