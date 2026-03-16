<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{

    public function index()
    {
        return view('pos');
    }


    public function getBarang($id)
    {
    $barang = \App\Models\Barang::find($id);

    if($barang){
        return response()->json([
            'status' => true,
            'data' => $barang
        ]);
    }

    return response()->json([
        'status' => false
    ]);
}



    public function simpanTransaksi(Request $request)
    {

        DB::beginTransaction();

        try{

            // simpan transaksi utama
            $id_penjualan = DB::table('penjualan')->insertGetId([
                'total' => $request->total,
                'created_at' => now(),
                'updated_at' => now()
            ]);



            // simpan detail transaksi
            foreach($request->items as $item){

                DB::table('penjualan_detail')->insert([

                    'id_penjualan' => $id_penjualan,
                    'id_barang' => $item['kode'],
                    'nama_barang' => $item['nama'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()

                ]);

            }


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan'
            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan transaksi'
            ]);

        }

    }

}