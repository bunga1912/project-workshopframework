<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Vendor;

class MenuController extends Controller
{
    // ============================
    // LIST MENU (MILIK VENDOR LOGIN)
    // ============================
    public function index()
    {
        $vendor = Vendor::where('user_id', auth()->id())->first();

        if (!$vendor) {
            abort(403, 'Vendor tidak ditemukan');
        }

        $menu = Menu::where('idvendor', $vendor->idvendor)->get();

        return view('menu.index', compact('menu'));
    }

    // ============================
    // FORM TAMBAH MENU
    // ============================
    public function create()
    {
        return view('menu.create');
    }

    // ============================
    // SIMPAN MENU + FOTO 🔥
    // ============================
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'   => 'required',
            'harga'       => 'required',
            'path_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $vendor = Vendor::where('user_id', auth()->id())->first();

        if (!$vendor) {
            abort(403, 'Vendor tidak ditemukan');
        }

        // 🔥 Bersihkan format harga (10.000 → 10000)
        $harga = str_replace('.', '', $request->harga);

        // 🔥 Upload gambar
        $path = null;
        if ($request->hasFile('path_gambar')) {
            $path = $request->file('path_gambar')->store('menu', 'public');
        }

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $harga,
            'path_gambar' => $path,
            'idvendor'    => $vendor->idvendor
        ]);

        return redirect()->route('menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    // ============================
    // FORM EDIT MENU
    // ============================
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);

        $vendor = Vendor::where('user_id', auth()->id())->first();

        if ($menu->idvendor != $vendor->idvendor) {
            abort(403);
        }

        return view('menu.update', compact('menu'));
    }

    // ============================
    // UPDATE MENU + FOTO 🔥
    // ============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu'   => 'required',
            'harga'       => 'required',
            'path_gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $menu = Menu::findOrFail($id);

        $vendor = Vendor::where('user_id', auth()->id())->first();

        if ($menu->idvendor != $vendor->idvendor) {
            abort(403);
        }

        // 🔥 Bersihkan harga
        $harga = str_replace('.', '', $request->harga);

        // 🔥 Update data dasar
        $menu->nama_menu = $request->nama_menu;
        $menu->harga = $harga;

        // 🔥 Update gambar kalau ada
        if ($request->hasFile('path_gambar')) {
            $path = $request->file('path_gambar')->store('menu', 'public');
            $menu->path_gambar = $path;
        }

        $menu->save();

        return redirect()->route('menu.index')
            ->with('success', 'Menu berhasil diupdate');
    }

    // ============================
    // HAPUS MENU
    // ============================
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        $vendor = Vendor::where('user_id', auth()->id())->first();

        if ($menu->idvendor != $vendor->idvendor) {
            abort(403);
        }

        $menu->delete();

        return redirect()->route('menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }

    // ============================
    // NONAKTIFKAN SHOW
    // ============================
    public function show($id)
    {
        return redirect()->route('menu.index');
    }
}