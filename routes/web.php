<?php

use App\Http\Controllers\BerkebutuhanKhususController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JenjangPendidikanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\TransportasiController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/kategori-kebutuhan',BerkebutuhanKhususController::class);
    Route::resource('/pekerjaan',PekerjaanController::class);
    Route::resource('/transportasi',TransportasiController::class);
    Route::resource('/penghasilan',PenghasilanController::class);
    Route::resource('/tahun-ajaran', TahunAjaranController::class);
    Route::resource('/guru', GuruController::class);
    Route::resource('/siswa', SiswaController::class);
    Route::resource('/kelas', KelasController::class);
    Route::resource('/jenjang-pendidikan', JenjangPendidikanController::class);
    
});

require __DIR__.'/auth.php';
