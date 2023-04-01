<?php

use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\IzinKeluarController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\RevisiController;
use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::apiResource('izink', IzinKeluarController::class);
Route::apiResource('absen', AbsenController::class);
Route::apiResource('presen', PresensiController::class);
Route::apiResource('revisi', RevisiController::class);

