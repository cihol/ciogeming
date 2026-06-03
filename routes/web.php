<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamController;

Route::get('/', function () {
    return view('welcome');

});

Route::get('/login', [InventarisController::class, 'showLogin'])->name('login');
Route::post('/login', [InventarisController::class, 'login'])->name('login.auth');
Route::post('/logout', [InventarisController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/', [InventarisController::class, 'dashboard'])->name('dashboard');

    // Barang (Admin & Petugas bisa Lihat, Modifikasi data diatur di level Controller)
    Route::get('/barang', [InventarisController::class, 'indexBarang'])->name('barang.index');
    Route::post('/barang', [InventarisController::class, 'storeBarang'])->name('barang.store');
    Route::put('/barang/{id}', [InventarisController::class, 'updateBarang'])->name('barang.update');
    Route::delete('/barang/{id}', [InventarisController::class, 'destroyBarang'])->name('barang.destroy');

    // Peminjaman
    Route::get('/peminjaman', [InventarisController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::post('/peminjaman', [InventarisController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::put('/peminjaman/{id}', [InventarisController::class, 'updatePeminjaman'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [InventarisController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');

    // Peminjam
    Route::get('/peminjam', [InventarisController::class, 'indexPeminjam'])->name('peminjam.index');

    Route::resource('barang', BarangController::class);
    Route::resource('peminjam', PeminjamController::class);
});
