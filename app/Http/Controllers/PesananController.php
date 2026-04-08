<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class PesananController extends Controller
{
    // ============================
    // HALAMAN PESANAN (CUSTOMER)
    // ============================
    public function index()
    {
        $vendors = Vendor::all();
        return view('pesanan', compact('vendors'));
    }

    // ============================
    // AJAX AMBIL MENU
    // ============================
    public function getMenu($id)
    {
        $menu = Menu::where('idvendor', $id)->get();
        return response()->json($menu);
    }

    // ============================
    // SIMPAN PESANAN
    // ============================
    public function simpanPesanan(Request $request)
    {
        // VALIDASI
        $request->validate([
            'nama' => 'required|string|max:255',
            'metode_bayar' => 'required',
            'total' => 'required|numeric|min:1',
            'menu' => 'required|array|min:1'
        ]);

        DB::beginTransaction();

        try {

            // ============================
            // BUAT USER GUEST
            // ============================
            $guest = User::create([
                'name' => 'Guest_' . str_pad(User::count() + 1, 7, '0', STR_PAD_LEFT),
                'email' => 'guest_' . time() . '@mail.com',
                'password' => bcrypt('guest'),
                'role' => 'customer'
            ]);

            // ============================
            // SIMPAN PESANAN
            // ============================
            $pesanan = Pesanan::create([
                'user_id' => $guest->id,
                'nama' => $request->nama,
                'timestamp' => now(),
                'total' => $request->total,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => 'pending'
            ]);

            // ============================
            // SIMPAN DETAIL PESANAN
            // ============================
            foreach ($request->menu as $item) {

                DetailPesanan::create([
                    'idpesanan' => $pesanan->id, // pastikan PK = id
                    'idmenu' => $item['idmenu'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'timestamp' => now(),
                    'catatan' => $item['catatan'] ?? null
                ]);
            }

            DB::commit();

            // ============================
            // RESPONSE SUCCESS
            // ============================
            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil disimpan',
                'idpesanan' => $pesanan->id
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            // ============================
            // RESPONSE ERROR
            // ============================
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan pesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}