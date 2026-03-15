<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function index()
    {
        $provinsi = DB::table('reg_provinces')->get();
        return view('select-wilayah', compact('provinsi'));
    }

    public function getKota($provinsi_id)
    {
        $kota = DB::table('reg_regencies')
            ->where('province_id', $provinsi_id)
            ->get();

        return response()->json($kota);
    }

    public function getKecamatan($kota_id)
    {
        $kecamatan = DB::table('reg_districts')
            ->where('regency_id', $kota_id)
            ->get();

        return response()->json($kecamatan);
    }

    public function getKelurahan($kecamatan_id)
    {
        $kelurahan = DB::table('reg_villages')
            ->where('district_id', $kecamatan_id)
            ->get();

        return response()->json($kelurahan);
    }
}