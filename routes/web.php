<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AnggotaEkstrakurikulerController;
use App\Http\Controllers\AnggotaKelasController;
use App\Http\Controllers\BerkebutuhanKhususController;
use App\Http\Controllers\BulanSppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JenjangPendidikanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PembayaranSppController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PresensiEkstrakurikulerController;
use App\Http\Controllers\PresensiKelasController;
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
    Route::resource('/bulan-spp', BulanSppController::class);
    Route::resource('/presensi-kelas', PresensiKelasController::class);
    Route::resource('/presensi-ekstrakurikuler', PresensiEkstrakurikulerController::class);
    Route::resource('/pembayaran-spp', PembayaranSppController::class);
    Route::post('/pembayaran-spp/cari', [PembayaranSppController::class, 'cari'])->name('pembayaran-spp.cari');
    Route::resource('/ekstrakurikuler', EkstrakurikulerController::class);
    Route::resource('/anggota-kelas', AnggotaKelasController::class);
    Route::resource('/anggota-ekstrakurikuler', AnggotaEkstrakurikulerController::class);
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard.index');
    Route::resource('/agenda', AgendaController::class);
    Route::resource('/pengumuman', PengumumanController::class);
});

require __DIR__.'/auth.php';
