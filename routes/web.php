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
    return view('welcome');
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
