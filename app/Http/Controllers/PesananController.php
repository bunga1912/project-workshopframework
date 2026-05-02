<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class PesananController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function index()
    {
        $vendors = Vendor::all();
        return view('pesanan', compact('vendors'));
    }

    public function getMenu($id)
    {
        $menu = Menu::where('idvendor', $id)->get();

        if ($menu->isEmpty()) {
            return response()->json([]);
        }

        try {
            $generator = new BarcodeGeneratorPNG();

            $menu->transform(function ($m) use ($generator) {
                try {
                    $barcode = $generator->getBarcode(
                        (string) $m->idmenu,
                        $generator::TYPE_CODE_128,
                        2,
                        80
                    );
                    $m->barcodeBase64 = base64_encode($barcode);
                } catch (\Exception $e) {
                    $m->barcodeBase64 = null;
                }
                return $m;
            });

        } catch (\Exception $e) {
            $menu->transform(function ($m) {
                $m->barcodeBase64 = null;
                return $m;
            });
        }

        return response()->json($menu);
    }

    public function simpanPesanan(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();

        try {
            $pesanan = Pesanan::create([
                'nama'         => $data['nama'],
                'timestamp'    => now(),
                'total'        => $data['total'],
                'metode_bayar' => $data['metode_bayar'],
                'status_bayar' => 0
            ]);

            foreach ($data['menu'] as $item) {
                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu'    => $item['idmenu'],
                    'jumlah'    => $item['jumlah'],
                    'harga'     => $item['harga'],
                    'subtotal'  => $item['subtotal'],
                    'timestamp' => now()
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
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function checkout($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $details = DetailPesanan::where('idpesanan', $id)->get();

        $items = [];

        foreach ($details as $d) {
            $menu = Menu::find($d->idmenu);

            $items[] = [
                'id'       => $d->idmenu,
                'price'    => (int) $d->harga,
                'quantity' => (int) $d->jumlah,
                'name'     => $menu->nama_menu ?? 'Menu'
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $id . '-' . time(),
                'gross_amount' => collect($items)->sum(fn($i) => $i['price'] * $i['quantity'])
            ],
            'item_details' => $items
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snap_token' => $snapToken]);
    }

    // =====================================================
    // Menerima notifikasi dari Midtrans
    // =====================================================
    public function handleNotification(Request $request)
    {
        // Set ulang config di sini agar pasti terbaca
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $notif = new \Midtrans\Notification();

        $transactionStatus = $notif->transaction_status;
        $orderId           = $notif->order_id; // format: ORDER-{idpesanan}-{time}
        $fraudStatus       = $notif->fraud_status;

        // Ambil idpesanan dari order_id
        $parts     = explode('-', $orderId);
        $idpesanan = $parts[1] ?? null;

        if (!$idpesanan) {
            return response()->json(['message' => 'order id tidak valid'], 400);
        }

        $pesanan = Pesanan::find($idpesanan);

        if (!$pesanan) {
            return response()->json(['message' => 'pesanan tidak ditemukan'], 404);
        }

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $pesanan->status_bayar = 1;
            }
        } elseif ($transactionStatus == 'settlement') {
            $pesanan->status_bayar = 1;
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pesanan->status_bayar = 2;
        } elseif ($transactionStatus == 'pending') {
            $pesanan->status_bayar = 0;
        }

        $pesanan->save();

        return response()->json(['message' => 'notifikasi diterima']);
    }

    // =====================================================
    // Halaman sukses + tampil QR Code
    // =====================================================
    public function successPage($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $qrCode = new QrCode((string) $pesanan->idpesanan);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $qrDataUri = $result->getDataUri();

        return view('payment.success', compact('pesanan', 'qrDataUri'));
    }
}