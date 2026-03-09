```php
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;

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
// ROOT
// ============================================
Route::get('/', function () {
    return redirect()->route('dashboard');
});


// ══════════════════════════════════════════
// SEMUA USER LOGIN
// ══════════════════════════════════════════
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


    // ========================================
    // PDF
    // ========================================
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])
        ->name('pdf.sertifikat');

    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])
        ->name('pdf.undangan');


    // ========================================
    // BARANG (VIEW + CETAK)
    // ========================================
    Route::get('/barang', [BarangController::class, 'index'])
        ->name('barang.index');

    Route::post('/barang/label', [BarangController::class, 'label'])
        ->name('barang.label');


    // ========================================
    // SOAL NOMOR 2 (HTML TABLE)
    // ========================================
    Route::get('/table-html', function () {
        return view('barang.table-html');
    })->name('table.html');


    // ========================================
    // SOAL NOMOR 2 (DATATABLES)
    // ========================================
    Route::get('/table-datatables', function () {
        return view('barang.table-datatables');
    })->name('table.datatables');


    // ========================================
    // SOAL NOMOR 4 (SELECT KOTA)
    // ========================================
    Route::get('/select-kota', function () {
        return view('select-kota');
    })->name('select.kota');

});


// ══════════════════════════════════════════
// ADMIN ONLY
// ══════════════════════════════════════════
Route::middleware(['auth', 'admin'])->group(function () {

    // ========================================
    // KATEGORI
    // ========================================
    Route::resource('kategori', KategoriController::class)
        ->except(['index', 'show']);

    // ========================================
    // BUKU
    // ========================================
    Route::resource('buku', BukuController::class)
        ->except(['index', 'show']);

    // ========================================
    // BARANG (CRUD)
    // ========================================
    Route::get('/barang/create', [BarangController::class, 'create'])
        ->name('barang.create');

    Route::post('/barang', [BarangController::class, 'store'])
        ->name('barang.store');

    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])
        ->name('barang.edit');

    Route::put('/barang/{id}', [BarangController::class, 'update'])
        ->name('barang.update');

    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])
        ->name('barang.destroy');

});


// ============================================
// FALLBACK
// ============================================
Route::fallback(function () {
    return redirect()->route('dashboard');
});