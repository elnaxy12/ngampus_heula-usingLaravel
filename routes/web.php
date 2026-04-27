<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\HtmlController;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('beranda'));

Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');
Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');
Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all');

/*
|--------------------------------------------------------------------------
| Auth (Google OAuth & Logout)
|--------------------------------------------------------------------------
*/

Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback');
Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('is.customer')->group(function () {
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun');
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])->name('customer.updateakun');
});

/*
|--------------------------------------------------------------------------
| Backend Auth
|--------------------------------------------------------------------------
*/

Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.post');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

/*
|--------------------------------------------------------------------------
| Backend Routes (auth protected)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda');

    Route::resource('backend/user', UserController::class, ['as' => 'backend']);
    Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend']);
    Route::resource('backend/produk', ProdukController::class, ['as' => 'backend']);
    Route::resource('backend/customer', CustomerController::class, ['as' => 'backend']);

    Route::post('foto_produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store');
    Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy');

    Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser');
    Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser');
    Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk');
    Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk');
});

/*
|--------------------------------------------------------------------------
| Latihan / Dev Routes
|--------------------------------------------------------------------------
*/

Route::resource('anggota', AnggotaController::class);
Route::get('/helloworld', [HelloWorldController::class, 'index']);
Route::get('/ambilfile', [HelloWorldController::class, 'ambilFile']);
Route::get('/getlorem', [HtmlController::class, 'getLorem']);
Route::get('/table', [LatihanController::class, 'getTable']);
Route::get('/form', [LatihanController::class, 'getForm']);
