<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PesananController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

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
        // 🔥 Support JSON dan form-encoded
        $data = $request->json()->all() ?: $request->all();

        $nama       = $data['nama'] ?? null;
        $metodeBayar = $data['metode_bayar'] ?? null;
        $total      = $data['total'] ?? null;
        $menu       = $data['menu'] ?? [];

        if (!$nama || !$metodeBayar || !$total || empty($menu)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak lengkap'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $pesanan = Pesanan::create([
                'nama'         => $nama,
                'timestamp'    => now(),
                'total'        => $total,
                'metode_bayar' => $metodeBayar,
                'status_bayar' => 0
            ]);

            foreach ($menu as $item) {
                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu'    => $item['idmenu'],
                    'jumlah'    => $item['jumlah'],
                    'harga'     => $item['harga'],
                    'subtotal'  => $item['subtotal'],
                    'timestamp' => now(),
                    'catatan'   => $item['catatan'] ?? null
                ]);
            }

            DB::commit();

            return response()->json([
                'status'    => 'success',
                'idpesanan' => $pesanan->idpesanan
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan pesanan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ============================
    // CHECKOUT — BUAT SNAP TOKEN
    // ============================
    public function checkout($id)
    {
        $pesanan = Pesanan::where('idpesanan', $id)->firstOrFail();
        $details = DetailPesanan::where('idpesanan', $id)->get();

        $itemDetails = [];
        foreach ($details as $d) {
            $menu = Menu::find($d->idmenu);
            $itemDetails[] = [
                'id'       => $d->idmenu,
                'price'    => (int) $d->harga,
                'quantity' => (int) $d->jumlah,
                'name'     => $menu ? $menu->nama_menu : 'Menu'
            ];
        }

        // 🔥 Validasi total harus cocok dengan item_details
        $totalItem = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $itemDetails));

        $params = [
            'transaction_details' => [
                'order_id'    => 'ORDER-' . $pesanan->idpesanan . '-' . time(),
                'gross_amount' => $totalItem,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ============================
    // CALLBACK DARI MIDTRANS
    // ============================
    public function callback(Request $request)
    {
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId           = $notification->order_id;
        $fraudStatus       = $notification->fraud_status;

        // Format order_id: ORDER-{idpesanan}-{time}
        $parts     = explode('-', $orderId);
        $idpesanan = $parts[1] ?? null;

        $pesanan = Pesanan::where('idpesanan', $idpesanan)->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if ($transactionStatus == 'capture') {
            $pesanan->status_bayar = $fraudStatus == 'accept' ? 1 : 2;
        } elseif ($transactionStatus == 'settlement') {
            $pesanan->status_bayar = 1;
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pesanan->status_bayar = 2;
        } elseif ($transactionStatus == 'pending') {
            $pesanan->status_bayar = 0;
        }

        $pesanan->save();

        return response()->json(['message' => 'OK']);
    }
}