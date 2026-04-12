<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    // =========================
    // LIST CUSTOMER
    // =========================
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer.index', compact('customers'));
    }

    // =========================
    // FORM BLOB
    // =========================
    public function create1()
    {
        return view('customer.tambah1');
    }

    // =========================
    // SIMPAN BLOB (FIX BYTEA)
    // =========================
    public function store1(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        try {
            $blobData = null;

            if ($request->foto_data) {
                // ambil base64 dari kamera
                $image = $request->foto_data;

                // hapus prefix data:image/jpeg;base64,
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

                // decode ke binary
                $binary = base64_decode($image);

                // simpan ke PostgreSQL sebagai BYTEA
                $blobData = DB::raw("decode('" . base64_encode($binary) . "', 'base64')");
            }

            DB::table('customers')->insert([
                'nama'       => $request->nama,
                'email'      => $request->email,
                'no_hp'      => $request->no_hp,
                'foto_blob'  => $blobData,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('customer.index')
                ->with('success', 'Customer (BLOB) berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    // =========================
    // FORM FILE
    // =========================
    public function create2()
    {
        return view('customer.tambah2');
    }

    // =========================
    // SIMPAN FILE
    // =========================
    public function store2(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        try {
            $path = null;

            if ($request->foto_data) {
                $image = $request->foto_data;
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);

                $binary = base64_decode($image);

                $fileName = 'customers/' . time() . '.jpg';

                // simpan ke storage
                \Storage::disk('public')->put($fileName, $binary);

                $path = $fileName;
            }

            Customer::create([
                'nama'       => $request->nama,
                'email'      => $request->email,
                'no_hp'      => $request->no_hp,
                'foto_path'  => $path,
            ]);

            return redirect()->route('customer.index')
                ->with('success', 'Customer (FILE) berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    // =========================
    // AMBIL FOTO BLOB
    // =========================
    public function fotoBlob($id)
    {
        $cust = Customer::findOrFail($id);

        if (!$cust->foto_blob) {
            abort(404);
        }

        $data = $cust->foto_blob;

        // handle kalau resource
        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }

        return response($data)->header('Content-Type', 'image/jpeg');
    }
}