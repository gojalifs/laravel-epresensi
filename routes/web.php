<?php

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
Route::get('/users-list', [App\Http\Controllers\web\WebUserController::class, 'index'])->name('users-list');
Route::post('/users/search', [App\Http\Controllers\web\WebUserController::class, 'search'])->name('users.search');
Route::post('/users/add', [App\Http\Controllers\Api\UserController::class, 'store'])->name('users.add');
Route::post('/users/update', [App\Http\Controllers\web\WebUserController::class, 'updateUser'])->name('users.update');
Route::delete('/users/delete', [App\Http\Controllers\web\WebUserController::class, 'delete'])->name('users.delete');
Route::get('/cuti', [App\Http\Controllers\web\CutiController::class, 'index']);
Route::get('/presensi', [App\Http\Controllers\web\PresensiController::class, 'index']);
Route::get('/presensi-act', [App\Http\Controllers\web\PresensiController::class, 'action']);

Route::get('/revisi', [App\Http\Controllers\web\RevisiController::class, 'index']);
Route::post('/revisi/{id}', [App\Http\Controllers\web\RevisiController::class, 'update'])->name('revisi.update');
Route::delete('/revisi/{id}', [App\Http\Controllers\web\RevisiController::class, 'destroy']);

Route::get('/keluar', [App\Http\Controllers\web\IzinKeluarController::class, 'index']);
Route::post('/izinkeluar/{id}', [App\Http\Controllers\web\IzinKeluarController::class, 'update'])->name('revisi.update');
Route::delete('/izinkeluar/{id}', [App\Http\Controllers\web\IzinKeluarController::class, 'destroy']);