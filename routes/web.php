<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// AUTH
// =========================
Auth::routes([
    'register' => false,
]);

// =========================
// ROOT
// =========================
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// =========================
// AUTHENTICATED ROUTES
// =========================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');

    // VIEW DATA (USER + ADMIN)
    Route::get('/kategori', [KategoriController::class, 'index'])
        ->name('kategori.index');

    Route::get('/buku', [BukuController::class, 'index'])
        ->name('buku.index');

    // OPTIONAL PROFILE
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');
});

// =========================
// ADMIN ONLY (CRUD)
// =========================
Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('kategori', KategoriController::class)
        ->except(['index', 'show']);

    Route::resource('buku', BukuController::class)
        ->except(['index', 'show']);
});

// =========================
// FALLBACK
// =========================
Route::fallback(function () {
    return redirect()->route('dashboard');
});