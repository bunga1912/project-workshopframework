<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{

    // ======================
    // HALAMAN POS
    // ======================
    public function index()
    {
        return view('pos');
    }


    // ======================
    // GET DATA BARANG
    // ======================
    public function getBarang($id)
    {
        $barang = \App\Models\Barang::find($id);

        if ($barang) {
            return response()->json([
                'status' => true,
                'data' => $barang
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }


    // ======================
    // SIMPAN TRANSAKSI
    // ======================
    public function simpanTransaksi(Request $request)
{
    DB::beginTransaction();

    try {

        // simpan penjualan (TANPA created_at)
        $id_penjualan = DB::table('penjualan')->insertGetId([
            'total' => $request->total,
            'timestamp' => now() // pakai kolom yang ADA
        ]);

        // simpan detail
        foreach ($request->items as $item) {

            DB::table('penjualan_detail')->insert([
                'id_penjualan' => $id_penjualan,
                'id_barang' => $item['id_barang'],
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['subtotal']
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil disimpan'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => $e->getMessage() // biar keliatan error asli
        ], 500);
    }
}
}