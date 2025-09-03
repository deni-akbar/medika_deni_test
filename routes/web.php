<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AntrianController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/antrian/next', [App\Http\Controllers\AntrianController::class, 'next'])->name('antrian.next');
    Route::post('/admin/antrian/previous', [App\Http\Controllers\AntrianController::class, 'previous'])->name('antrian.previous');

    Route::post('/admin/antrian/store', [App\Http\Controllers\AntrianController::class, 'store'])->name('antrian.store');
});

Route::get('/', [App\Http\Controllers\AntrianController::class, 'indexUser'])->name('user');
Route::get('/antrian/json', [App\Http\Controllers\AntrianController::class, 'json'])->name('antrian.json');
