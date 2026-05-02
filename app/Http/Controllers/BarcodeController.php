<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Hanya admin yang boleh akses
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Akses ditolak.');
            }
            return $next($request);
        });
    }

    // Tampilkan halaman scanner barcode
    public function index()
    {
        return view('barcode.scan');
    }

    // API: cari barang berdasarkan id_barang (hasil scan barcode)
    public function cari(Request $request)
    {
        $idBarang = $request->query('id_barang');

        if (!$idBarang) {
            return response()->json(['success' => false, 'message' => 'ID barang kosong'], 400);
        }

        $barang = Barang::where('id_barang', $idBarang)->first();

        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'barang'  => [
                'id_barang' => $barang->id_barang,
                'nama'      => $barang->nama,
                'harga'     => $barang->harga,
            ]
        ]);
    }
}