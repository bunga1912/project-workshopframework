<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    // ========================
    // INDEX
    // ========================
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    // ========================
    // CREATE
    // ========================
    public function create()
    {
        return view('barang.create');
    }

    // ========================
    // STORE
    // ========================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required'
        ]);

        Barang::create([
            'nama' => $request->nama,
            'harga' => preg_replace('/\D/', '', $request->harga)
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    // ========================
    // EDIT
    // ========================
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.update', compact('barang'));
    }

    // ========================
    // UPDATE
    // ========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required'
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama' => $request->nama,
            'harga' => preg_replace('/\D/', '', $request->harga)
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // ========================
    // DELETE
    // ========================
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil dihapus');

    }
    // ========================
    // CETAK LABEL
    // ========================
    public function label(Request $request)
{
    $request->validate([
        'barang' => 'required|array',
        'start_x' => 'required|integer|min:1|max:5',
        'start_y' => 'required|integer|min:1|max:8',
    ]);

    $barang = Barang::whereIn('id_barang', $request->barang)->get();

    $kolom   = 5; // 5 kolom per baris
    $startX  = (int) $request->start_x; // kolom mulai (1-5)
    $startY  = (int) $request->start_y; // baris mulai (1-8)

    // Hitung total sel di grid
    $totalBaris = 8;
    $totalSel   = $kolom * $totalBaris;

    // Buat array flat semua sel, isi null dulu
    $flat = array_fill(0, $totalSel, null);

    // Posisi mulai (0-based)
    $offset = (($startY - 1) * $kolom) + ($startX - 1);

    // Isi barang mulai dari offset
    foreach ($barang as $i => $b) {
        $pos = $offset + $i;
        if ($pos < $totalSel) {
            $flat[$pos] = $b;
        }
    }

    // Ubah flat ke grid 2D (baris x kolom)
    $grid = array_chunk($flat, $kolom);

    return view('barang.label', compact('grid'));
  }
}