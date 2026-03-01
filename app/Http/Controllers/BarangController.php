<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    // ==============================
    // TAMPIL DATA
    // ==============================
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    // ==============================
    // FORM TAMBAH
    // ==============================
    public function create()
    {
        return view('barang.create');
    }

    // ==============================
    // SIMPAN DATA
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required'
        ]);

        // ==========================
        // AUTO ID BARANG (BRG001)
        // ==========================
        $last = Barang::orderBy('id_barang', 'desc')->first();

        if ($last) {
            $number = (int) substr($last->id_barang, 3) + 1;
        } else {
            $number = 1;
        }

        $id = 'BRG' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // ==========================
        // SIMPAN DATA
        // ==========================
        Barang::create([
            'id_barang' => $id,
            'nama' => $request->nama,
            'harga' => str_replace('.', '', $request->harga)
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    // ==============================
    // FORM EDIT
    // ==============================
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    // ==============================
    // UPDATE DATA
    // ==============================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required'
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama' => $request->nama,
            'harga' => str_replace('.', '', $request->harga)
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil diupdate');
    }

    // ==============================
    // HAPUS DATA
    // ==============================
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil dihapus');
    }

    // ==============================
    // CETAK LABEL PDF
    // ==============================
    public function cetakLabel(Request $request)
    {
        $request->validate([
            'barang' => 'required|array|min:1',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8'
        ]);

        // ambil data berdasarkan id_barang
        $barang = Barang::whereIn('id_barang', $request->barang)->get();

        $startX = $request->start_x - 1;
        $startY = $request->start_y - 1;

        $rows = 8;
        $cols = 5;

        $grid = [];

        // isi grid kosong
        for ($y = 0; $y < $rows; $y++) {
            for ($x = 0; $x < $cols; $x++) {
                $grid[$y][$x] = null;
            }
        }

        $index = 0;

        for ($y = $startY; $y < $rows; $y++) {

            // zig-zag
            if ($y % 2 == 0) {
                $range = range(0, $cols - 1);
            } else {
                $range = array_reverse(range(0, $cols - 1));
            }

            foreach ($range as $x) {

                if ($y == $startY && $x < $startX) continue;

                if ($index >= count($barang)) break 2;

                $grid[$y][$x] = $barang[$index];
                $index++;
            }
        }

        $pdf = Pdf::loadView('barang.label', compact('grid'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('label.pdf');
    }
}