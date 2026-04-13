<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\HtmlController;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Anggota;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Symfony\Component\Routing\Route as RoutingRoute;

Route::get('/', function () {
    // return view('welcome');
    // return redirect()->route('backend.login');
    return redirect()->route('beranda');
});


Route::resource('anggota', AnggotaController::class);
Route::get('/helloworld', [HelloWorldController::class, 'index']);
Route::get('/ambilfile', [HelloWorldController::class, 'ambilFile']);
Route::get('/getlorem', [HtmlController::class, 'getLorem']);
Route::get('/table', [LatihanController::class, 'getTable']);
Route::get('/form', [LatihanController::class, 'getForm']);



Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
    ->name('backend.beranda')
    ->middleware('auth');


Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');

Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.login.post');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout');


Route::resource('backend/user', UserController::class, ['as' => 'backend'])
->middleware('auth');

Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');

Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');

Route::post('foto_produk/store', [
    ProdukController::class, 'storeFoto'
])->name('backend.foto_produk.store')->middleware('auth');

Route::delete('foto-produk/{id}', [
    ProdukController::class, 'destroyFoto'
])->name('backend.foto_produk.destroy')->middleware('auth');

Route::get('backend/laporan/formuser', [
    UserController::class, 'formUser'
])->name('backend.laporan.formuser')->middleware('auth');

Route::post('backend/laporan/cetakuser', [
    UserController::class, 'cetakUser'
])->name('backend.laporan.cetakuser')->middleware('auth');

Route::get('backend/laporan/formproduk', [
    ProdukController::class, 'formProduk'
])->name('backend.laporan.formproduk')->middleware('auth');

Route::post('backend/laporan/cetakproduk', [
    ProdukController::class, 'cetakProduk'
])->name('backend.laporan.cetakproduk')->middleware('auth');

Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');
Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');
Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all'); 