<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisTransaksiController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\TabunganController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//auth
Route::get('/user/all',[AuthController::class, 'index']);
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/changerole',[AuthController::class, 'changeRole']);

//Barang
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::post('/barang/delete/{id}', [BarangController::class, 'destroy']);
Route::post('/barang/update/{id}', [BarangController::class, 'update']);
Route::post('/barang', [BarangController::class, 'store']);


//pertanyaan
Route::get('/pertanyaan', [BantuanController::class, 'index']);
Route::get('/pertanyaan/{id}', [BantuanController::class, 'show']);
Route::post('/pertanyaan/delete/{id}', [BantuanController::class, 'destroy']);
Route::post('/pertanyaan/update/{id}', [BantuanController::class, 'update']);
Route::post('/pertanyaan', [BantuanController::class, 'store']);

//Jenis Transaksi
Route::get('/jenis-transaksi', [JenisTransaksiController::class, 'index']);
Route::get('/jenis-transaksi/{id}', [JenisTransaksiController::class, 'show']);
Route::post('/jenis-transaksi/delete/{id}', [JenisTransaksiController::class, 'destroy']);
Route::post('/jenis-transaksi/update/{id}', [JenisTransaksiController::class, 'update']);
Route::post('/jenis-transaksi', [JenisTransaksiController::class, 'store']);



//transaksi
Route::get('/transaksi', [TransaksiController::class, 'index']);
Route::get('/transaksi/{id}', [TransaksiController::class, 'show']);
Route::get('/transaksi/user/{id}', [TransaksiController::class, 'indexuser']);
Route::post('/transaksi/delete/{id}', [TransaksiController::class, 'destroy']);
Route::post('/transaksi/update/{id}', [TransaksiController::class, 'update']);
Route::post('/transaksi', [TransaksiController::class, 'store']);
Route::post('/transaksi/selesai/{id}', [TransaksiController::class, 'selesai']);


//pesan
Route::get('/pesan', [PesanController::class, 'index']);
Route::get('/pesan/{id}', [PesanController::class, 'show']);
Route::post('/pesan/delete/{id}', [PesanController::class, 'destroy']);
Route::post('/pesan/update/{id}', [PesanController::class, 'update']);
Route::post('/pesan', [PesanController::class, 'store']);


Route::get('/tabungan', [TabunganController::class, 'index']);
Route::post('/tabungan', [TabunganController::class, 'store']);
Route::get('/tabungan/user/{id}', [TabunganController::class, 'indexuser']);
Route::get('/tabungan/total/{id}', [TabunganController::class, 'total']);
Route::post('/tabungan/delete/{id}', [TabunganController::class, 'destroy']);