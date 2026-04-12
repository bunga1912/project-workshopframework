<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class MenuController extends Controller
{
    // ============================
    // HELPER — generate barcode (AMAN)
    // ============================
    private function generateBarcode($value): ?string
    {
        try {
            $generator = new BarcodeGeneratorPNG();

            return base64_encode(
                $generator->getBarcode(
                    (string) $value,
                    $generator::TYPE_CODE_128,
                    2,
                    80 // 🔥 lebih tinggi biar jelas
                )
            );

        } catch (\Exception $e) {
            // ❗ kalau GD / Imagick tidak ada → tidak error
            return null;
        }
    }

    // ============================
    // LIST MENU (VENDOR LOGIN)
    // ============================
    public function index()
    {
        $vendor = Vendor::where('user_id', auth()->id())->first();

        if (!$vendor) {
            abort(403, 'Vendor tidak ditemukan');
        }

        $menu = Menu::where('idvendor', $vendor->idvendor)->get();

        $menu->each(function ($m) {
            $m->barcodeBase64 = $this->generateBarcode($m->idmenu);
        });

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
    // SIMPAN MENU
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
    // FORM EDIT
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
    // UPDATE MENU
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
            $menu->path_gambar = $request->file('path_gambar')->store('menu', 'public');
        }

        $menu->save();

        return redirect()->route('menu.index')
            ->with('success', 'Menu berhasil diupdate');
    }

    // ============================
    // DELETE MENU
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

    public function show($id)
    {
        return redirect()->route('menu.index');
    }

    // ============================
    // PESANAN MASUK
    // ============================
    public function pesananMasuk()
    {
        $vendor = Vendor::where('user_id', auth()->id())->first();

        if (!$vendor) {
            abort(403);
        }

        $pesanan = DB::table('pesanan')
            ->join('detail_pesanan', 'pesanan.idpesanan', '=', 'detail_pesanan.idpesanan')
            ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
            ->where('menu.idvendor', $vendor->idvendor)
            ->get()
            ->groupBy('idpesanan');

        return view('menu.pesanan-masuk', compact('pesanan'));
    }

    // ============================
    // CETAK PDF
    // ============================
    public function cetakTagHarga($id)
    {
        $menu = Menu::findOrFail($id);

        $barcodeBase64 = $this->generateBarcode($menu->idmenu);

        $pdf = Pdf::loadView('menu.tag-harga', compact('menu', 'barcodeBase64'));

        return $pdf->stream();
    }

    public function cetakSemuaTagHarga()
    {
        $vendor = Vendor::where('user_id', auth()->id())->first();

        $menu = Menu::where('idvendor', $vendor->idvendor)->get();

        $menu->each(function ($m) {
            $m->barcodeBase64 = $this->generateBarcode($m->idmenu);
        });

        $pdf = Pdf::loadView('menu.tag-harga-semua', compact('menu'));

        return $pdf->stream();
    }
}