<?php

use App\Http\Controllers\Api\AbsenController;
use App\Http\Controllers\Api\IzinKeluarController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\RevisiController;
use App\Http\Controllers\Api\TodayPresention;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('izink', IzinKeluarController::class);
    Route::apiResource('absen', AbsenController::class);
    Route::apiResource('presen', PresensiController::class);
    Route::apiResource('revisi', RevisiController::class);
    Route::apiResource('user/data', UserController::class);
    Route::post('/daily', [TodayPresention::class, 'getTodayPresention']);
    Route::post('/dailycount', [TodayPresention::class, 'presentCount']);
    Route::post('/dailyreport', [Reporting::class, 'getDailyReport']);
    Route::post('/updateuser', [UserController::class, 'updateUser']);
    Route::post('/updateava', [UserController::class, 'updateAvatar']);
    Route::post('user/checkstatus', [UserController::class, 'loginStatus']);
});

Route::post('user/login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout']);
Route::post('user/register', [UserController::class, 'store']);

Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('public/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});