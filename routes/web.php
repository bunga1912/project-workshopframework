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

Auth::routes(['register' => false]);

// ============================================
// GOOGLE LOGIN
// ============================================
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])
    ->name('google.login');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');

// ============================================
// OTP
// ============================================
Route::get('/verifikasi-otp', function () {
    return view('auth.otp');
})->name('otp.form');

Route::post('/verifikasi-otp', [AuthController::class, 'verifyOtp'])
    ->name('otp.verify');

// ============================================
// ROOT = HALAMAN PERTAMA (PESANAN)
// ============================================
Route::get('/', [PesananController::class, 'index'])->name('home');

// ============================================
// CUSTOMER (TANPA LOGIN)
// ============================================
Route::prefix('pesanan')->group(function () {

    Route::get('/', [PesananController::class, 'index'])
        ->name('pesanan.index');

    Route::get('/get-menu/{id}', [PesananController::class, 'getMenu'])
        ->name('pesanan.getMenu');

    Route::post('/simpan', [PesananController::class, 'simpanPesanan'])
        ->name('pesanan.simpan');

    Route::get('/checkout/{id}', [PesananController::class, 'checkout'])
        ->name('pesanan.checkout');
});

// ============================================
// CALLBACK MIDTRANS
// ============================================
Route::post('/midtrans-callback', [PesananController::class, 'callback']);

// ============================================
// ADMIN
// ============================================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');

    Route::get('/kategori', [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::get('/buku', [BukuController::class, 'index'])
        ->name('buku.index');

    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');

    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])
        ->name('pdf.sertifikat');

    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])
        ->name('pdf.undangan');

    Route::get('/barang', [BarangController::class, 'index'])
        ->name('barang.index');

    Route::post('/barang/label', [BarangController::class, 'label'])
        ->name('barang.label');

    Route::get('/wilayah', [WilayahController::class, 'index'])
        ->name('wilayah.index');

    Route::get('/get-kota/{provinsi_id}', [WilayahController::class, 'getKota']);
    Route::get('/get-kecamatan/{kota_id}', [WilayahController::class, 'getKecamatan']);
    Route::get('/get-kelurahan/{kecamatan_id}', [WilayahController::class, 'getKelurahan']);
});

// ============================================
// VENDOR ONLY (CRUD MENU + PESANAN MASUK)
// ============================================
Route::middleware(['auth', 'isVendor'])->group(function () {

    Route::resource('/menu', MenuController::class);

    Route::get('/pesanan-masuk', [MenuController::class, 'pesananMasuk'])
        ->name('pesanan.masuk');
});

// ============================================
// POS (PUBLIC)
// ============================================
Route::get('/pos', [PosController::class, 'index'])
    ->name('pos.index');

Route::get('/get-barang/{kode}', [PosController::class, 'getBarang']);
Route::post('/simpan-transaksi', [PosController::class, 'simpanTransaksi']);

// ============================================
// ADMIN ONLY
// ============================================
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('kategori', KategoriController::class)
        ->except(['index', 'show']);

    Route::resource('buku', BukuController::class)
        ->except(['index', 'show']);

    Route::get('/barang/create', [BarangController::class, 'create']);
    Route::post('/barang', [BarangController::class, 'store']);
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit']);
    Route::put('/barang/{id}', [BarangController::class, 'update']);
    Route::delete('/barang/{id}', [BarangController::class, 'destroy']);

    Route::resource('vendor', VendorController::class);
});

// ============================================
// FALLBACK → BALIK KE PESANAN
// ============================================
Route::fallback(function () {
    return redirect('/');
});