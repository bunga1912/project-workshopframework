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

        $id_penjualan = DB::table('penjualan')->insertGetId([
        'total' => (int)$request->total,
        'timestamp' => now()
],      'id_penjualan'); // 🔥 TAMBAHKAN INI

        foreach ($request->items as $item) {

            DB::table('penjualan_detail')->insert([
                'id_penjualan' => $id_penjualan,
                'id_barang' => $item['id_barang'],
                'jumlah' => (int)$item['jumlah'],
                'subtotal' => (int)$item['subtotal']
            ]);
        }

        DB::commit();

        return response()->json(['status' => true]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
}