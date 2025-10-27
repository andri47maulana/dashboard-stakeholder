<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Wilayah API routes
Route::get('/provinsi', [WilayahController::class, 'getProvinsi']);
Route::get('/kabupaten/{provinsi_id}', [WilayahController::class, 'getKabupaten']);
Route::get('/kecamatan/{kabupaten_id}', [WilayahController::class, 'getKecamatan']);
Route::get('/desa/{kecamatan_id}', [WilayahController::class, 'getDesa']);
