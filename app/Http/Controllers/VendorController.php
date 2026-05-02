<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendors = Vendor::with('user')->get();
        return view('vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_user'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->nama_user,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'vendor'
        ]);

        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'user_id'     => $user->id,
        ]);

        return redirect()->route('vendor.index')
                         ->with('success', 'Vendor berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);
        return view('vendor.update', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_user'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $vendor->user_id,
        ]);

        $vendor->user->update([
            'name'  => $request->nama_user,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $vendor->user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
        ]);

        return redirect()->route('vendor.index')
                         ->with('success', 'Vendor berhasil diupdate!');
    }

    public function destroy($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);

        if ($vendor->user) {
            $vendor->user->delete();
        }

        $vendor->delete();

        return redirect()->route('vendor.index')
                         ->with('success', 'Vendor berhasil dihapus!');
    }

    // -------------------------------------------------------
    // PRAKTIKUM 2: Halaman scan QR Code (untuk vendor login)
    // -------------------------------------------------------

    public function scanQR()
    {
        return view('vendor.scan');
    }

    public function getPesanan($idpesanan)
{
    $pesanan = Pesanan::find($idpesanan);

    if (!$pesanan) {
        return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
    }

    // Ambil menu yang dipesan lewat detail_pesanan, filter by vendor yang login
    $idVendor = auth()->user()->vendor->idvendor ?? null;

    $menu = DB::table('detail_pesanan')
        ->join('menu', 'detail_pesanan.idmenu', '=', 'menu.idmenu')
        ->where('detail_pesanan.idpesanan', $idpesanan)
        ->where('menu.idvendor', $idVendor)
        ->select('menu.nama_menu', 'menu.harga', 'detail_pesanan.jumlah', 'detail_pesanan.subtotal')
        ->get();

    return response()->json([
        'success' => true,
        'pesanan' => [
            'idpesanan'    => $pesanan->idpesanan,
            'nama'         => $pesanan->nama,
            'total'        => $pesanan->total,
            'status_bayar' => $pesanan->status_bayar,
        ],
        'menu' => $menu
    ]);
}
}