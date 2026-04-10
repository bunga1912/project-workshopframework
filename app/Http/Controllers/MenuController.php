<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

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
    // SIMPAN MENU + FOTO
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

        $harga = str_replace('.', '', $request->harga);

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
    // UPDATE MENU + FOTO
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

        $harga = str_replace('.', '', $request->harga);

        $menu->nama_menu = $request->nama_menu;
        $menu->harga     = $harga;

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

    // ============================
    // PESANAN MASUK (VENDOR)
    // ============================
    public function pesananMasuk()
    {
        $vendor = Vendor::where('user_id', auth()->id())->first();

        if (!$vendor) {
            abort(403, 'Vendor tidak ditemukan');
        }

        $pesanan = DB::table('pesanan')
            ->join('detail_pesanan', 'pesanan.idpesanan', '=', 'detail_pesanan.idpesanan')
            ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
            ->where('menu.idvendor', $vendor->idvendor)
            ->select(
                'pesanan.idpesanan',
                'pesanan.nama',
                'pesanan.timestamp',
                'pesanan.total',
                'pesanan.metode_bayar',
                'pesanan.status_bayar',
                'menu.nama_menu',
                'detail_pesanan.jumlah',
                'detail_pesanan.harga',
                'detail_pesanan.subtotal',
                'detail_pesanan.catatan'
            )
            ->orderBy('pesanan.timestamp', 'desc')
            ->get()
            ->groupBy('idpesanan');

        return view('menu.pesanan-masuk', compact('pesanan'));
    }
}