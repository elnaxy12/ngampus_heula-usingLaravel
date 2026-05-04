<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RajaOngkirController;

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
| RajaOngkir Routes (public)
|--------------------------------------------------------------------------
*/

Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']);
Route::get('/cities', [RajaOngkirController::class, 'getCities']);
Route::post('/cost', [RajaOngkirController::class, 'getCost']);

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

    // Cart
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.addToCart');
    Route::get('cart', [OrderController::class, 'viewCart'])->name('order.cart');
    Route::post('cart/update/{id}', [OrderController::class, 'updateCart'])->name('order.updateCart');
    Route::post('remove/{id}', [OrderController::class, 'removeFromCart'])->name('order.remove');

    // Shipping & Payment
    Route::post('select-shipping', [OrderController::class, 'selectShipping'])->name('order.selectShipping');
    Route::post('update-ongkir', [OrderController::class, 'updateOngkir'])->name('order.update-ongkir');
    Route::get('select-payment', [OrderController::class, 'selectPayment'])->name('order.selectpayment');
    Route::get('order/complete', [OrderController::class, 'complete'])->name('order.complete');
    Route::get('order/history', [OrderController::class, 'orderHistory'])->name('order.history');
    Route::get('order/invoice/{id}', [OrderController::class, 'invoiceFrontend'])->name('order.invoice');

    // Midtrans Callback
    Route::post('midtrans/callback', [OrderController::class, 'callback'])->name('order.callback');
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
| Backend Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda');

    // Resources
    Route::resource('backend/user', UserController::class, ['as' => 'backend']);
    Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend']);
    Route::resource('backend/produk', ProdukController::class, ['as' => 'backend']);
    Route::resource('backend/customer', CustomerController::class, ['as' => 'backend']);

    // Foto Produk
    Route::post('foto_produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store');
    Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy');

    // Pesanan
    Route::get('backend/pesanan/proses', [OrderController::class, 'statusProses'])->name('pesanan.proses');
    Route::get('backend/pesanan/selesai', [OrderController::class, 'statusSelesai'])->name('pesanan.selesai');
    Route::get('backend/pesanan/detail/{id}', [OrderController::class, 'statusDetail'])->name('pesanan.detail');
    Route::put('backend/pesanan/update/{id}', [OrderController::class, 'statusUpdate'])->name('pesanan.update');
    Route::get('backend/pesanan/invoice/{id}', [OrderController::class, 'invoiceBackend'])->name('pesanan.invoice');

    // Laporan User & Produk
    Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser');
    Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser');
    Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk');
    Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk');

    // Laporan Pesanan
    Route::get('backend/laporan/formpesananproses', [OrderController::class, 'formOrderProses'])->name('backend.laporan.formpesananproses');
    Route::post('backend/laporan/cetakpesananproses', [OrderController::class, 'cetakOrderProses'])->name('backend.laporan.cetakpesananproses');
    Route::get('backend/laporan/formpesananselesai', [OrderController::class, 'formOrderSelesai'])->name('backend.laporan.formpesananselesai');
    Route::post('backend/laporan/cetakpesananselesai', [OrderController::class, 'cetakOrderSelesai'])->name('backend.laporan.cetakpesananselesai');
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
Route::get('/cek-ongkir', fn () => view('ongkir'));
Route::get('/list-ongkir', function () {
    $response = Http::withHeaders([
        'key' => env('RAJAONGKIR_API_KEY')
    ])->get('https://rajaongkir.komerce.id/api/v1/destination/province');

    return $response->json();
});
