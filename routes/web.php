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

Route::get('/', function () {
    return view('welcome');
});


Route::resource('anggota', AnggotaController::class);
Route::get('/helloworld', [HelloWorldController::class, 'index']);
Route::get('/ambilfile', [HelloWorldController::class, 'ambilFile']);
Route::get('/getlorem', [HtmlController::class, 'getLorem']);
Route::get('/table', [LatihanController::class, 'getTable']);
Route::get('/form', [LatihanController::class, 'getForm']);


Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda');
Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.login');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout');
