<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BarcodeController;

Auth::routes(['register' => false]);

// ============================================
// GOOGLE LOGIN
// ============================================
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// ============================================
// OTP
// ============================================
Route::get('/verifikasi-otp', function () {
    return view('auth.otp');
})->name('otp.form');

Route::post('/verifikasi-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// ============================================
// ROOT = PESANAN
// ============================================
Route::get('/', [PesananController::class, 'index'])->name('home');

// ============================================
// CUSTOMER PUBLIC (PESANAN)
// ============================================
Route::prefix('pesanan')->group(function () {
    Route::get('/', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/get-menu/{id}', [PesananController::class, 'getMenu'])->name('pesanan.getMenu');
    Route::post('/simpan', [PesananController::class, 'simpanPesanan'])->name('pesanan.simpan');
    Route::get('/checkout/{id}', [PesananController::class, 'checkout'])->name('pesanan.checkout');
});

// ============================================
// MIDTRANS CALLBACK
// ============================================
Route::post('/midtrans-callback', [PesananController::class, 'callback']);

Route::post('/payment/notification', [PesananController::class, 'handleNotification'])
    ->name('payment.notification');

Route::get('/payment/success/{id}', [PesananController::class, 'successPage'])
    ->name('payment.success');

// ============================================
// TESTING ONLY: Update status bayar manual
// (Hapus route ini sebelum production / dikumpulkan)
// ============================================
Route::get('/dev/bayar/{idpesanan}', function ($idpesanan) {
    $pesanan = \App\Models\Pesanan::findOrFail($idpesanan);
    $pesanan->status_bayar = 1;
    $pesanan->save();
    return response()->json([
        'message'    => "Pesanan #{$idpesanan} berhasil diupdate jadi Lunas ✅",
        'idpesanan'  => $pesanan->idpesanan,
        'status_bayar' => $pesanan->status_bayar,
    ]);
});

// ============================================
// FIX: ROUTE FOTO HARUS PUBLIC
// ============================================
Route::get('/customer/foto/{id}', [CustomerController::class, 'foto'])
    ->name('customer.foto');

// ============================================
// ADMIN (LOGIN)
// ============================================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang/label', [BarangController::class, 'label'])->name('barang.label');

    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');

    Route::get('/get-kota/{provinsi_id}', [WilayahController::class, 'getKota']);
    Route::get('/get-kecamatan/{kota_id}', [WilayahController::class, 'getKecamatan']);
    Route::get('/get-kelurahan/{kecamatan_id}', [WilayahController::class, 'getKelurahan']);
});

// ============================================
// VENDOR
// ============================================
Route::middleware(['auth', 'isVendor'])->group(function () {

    Route::resource('/menu', MenuController::class);

    Route::get('/pesanan-masuk', [MenuController::class, 'pesananMasuk'])
        ->name('pesanan.masuk');

    Route::get('/menu/{id}/tag-harga', [MenuController::class, 'cetakTagHarga'])
        ->name('menu.tag-harga');

    Route::get('/menu/tag-harga/semua', [MenuController::class, 'cetakSemuaTagHarga'])
        ->name('menu.tag-harga.semua');

    // PRAKTIKUM 2: Scan QR Code customer
    Route::get('/vendor/scan', [VendorController::class, 'scanQR'])
        ->name('vendor.scan');

    Route::get('/vendor/pesanan/{idpesanan}', [VendorController::class, 'getPesanan'])
        ->name('vendor.getPesanan');
});

// ============================================
// POS
// ============================================
Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
Route::get('/get-barang/{kode}', [PosController::class, 'getBarang']);
Route::post('/simpan-transaksi', [PosController::class, 'simpanTransaksi']);

// ============================================
// ADMIN ONLY
// ============================================
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('kategori', KategoriController::class)->except(['index', 'show']);
    Route::resource('buku', BukuController::class)->except(['index', 'show']);

    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::resource('vendor', VendorController::class);

    // CUSTOMER
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/tambah1', [CustomerController::class, 'create1'])->name('customer.create1');
    Route::post('/customer/tambah1', [CustomerController::class, 'store1'])->name('customer.store1');
    Route::get('/customer/tambah2', [CustomerController::class, 'create2'])->name('customer.create2');
    Route::post('/customer/tambah2', [CustomerController::class, 'store2'])->name('customer.store2');

    // PRAKTIKUM 1: Scan Barcode Barang (khusus admin)
    Route::get('/barcode/scan', [BarcodeController::class, 'index'])->name('barcode.scan');
    Route::get('/barcode/cari', [BarcodeController::class, 'cari'])->name('barcode.cari');
});

// ============================================
// FALLBACK
// ============================================
Route::fallback(function () {
    return redirect('/');
});