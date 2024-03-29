<?php

use App\Models\Presensi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/cona', function () {
    return view('auth.login');
});
Route::get('/mlogin', function () {
    return view('layouts.mlogin');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\web\DashboardController::class, 'index']);
Route::get('/about', [App\Http\Controllers\web\AboutController::class, 'index']);

Route::get('/users-list', [App\Http\Controllers\web\WebUserController::class, 'index'])->name('users-list');
Route::post('/users/search', [App\Http\Controllers\web\WebUserController::class, 'search'])->name('users.search');
Route::post('/users/add', [App\Http\Controllers\Api\UserController::class, 'store'])->name('users.add');
Route::post('/users/update', [App\Http\Controllers\web\WebUserController::class, 'updateUser'])->name('users.update');
Route::post('/users/update/role', [App\Http\Controllers\web\WebUserController::class, 'setRole'])->name('users.setRole');
Route::delete('/users/delete', [App\Http\Controllers\web\WebUserController::class, 'delete'])->name('users.delete');

Route::get('/cuti', [App\Http\Controllers\web\CutiController::class, 'index']);
Route::get('/presensi', [App\Http\Controllers\web\PresensiController::class, 'index']);
Route::get('/presensi-act', [App\Http\Controllers\web\PresensiController::class, 'action']);
Route::get('/laporan', [App\Http\Controllers\web\PresensiController::class, 'index']);
Route::get('/presensi/report', [App\Http\Controllers\web\PresensiController::class, 'laporanPresensi']);

Route::get('/maps/{id}', function ($id) {
    $presensi = Presensi::findOrFail($id);

    return response()->json([
        'latitude' => $presensi->latitude,
        'longitude' => $presensi->longitude
    ]);
});


Route::get('/revisi', [App\Http\Controllers\web\RevisiController::class, 'index']);
Route::post('/revisi/{id}', [App\Http\Controllers\web\RevisiController::class, 'update'])->name('revisi.update');
Route::delete('/revisi/{id}', [App\Http\Controllers\web\RevisiController::class, 'destroy']);

Route::get('/keluar', [App\Http\Controllers\web\IzinKeluarController::class, 'index']);
Route::post('/izinkeluar/{id}', [App\Http\Controllers\web\IzinKeluarController::class, 'update'])->name('revisi.update');
Route::delete('/izinkeluar/{id}', [App\Http\Controllers\web\IzinKeluarController::class, 'destroy']);

Route::get('/ketidakhadiran', [App\Http\Controllers\web\KetidakhadiranController::class, 'index']);
Route::post('/ketidakhadiran/{id}', [App\Http\Controllers\web\KetidakhadiranController::class, 'update'])->name('revisi.update');
Route::delete('/ketidakhadiran/{id}', [App\Http\Controllers\web\KetidakhadiranController::class, 'destroy']);