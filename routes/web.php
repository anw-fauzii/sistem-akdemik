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
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PembayaranSppController;
use App\Http\Controllers\PembayaranTagihanTahunanController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PesertaDidik\KeuanganController;
use App\Http\Controllers\PesertaDidik\PresensiController;
use App\Http\Controllers\PresensiEkstrakurikulerController;
use App\Http\Controllers\PresensiKelasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanTahunanController;
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

Route::middleware(['auth','preventBackHistory'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/kategori-kebutuhan',BerkebutuhanKhususController::class)->except(['show']);
    Route::resource('/pekerjaan',PekerjaanController::class)->except(['show']);
    Route::resource('/transportasi',TransportasiController::class)->except(['show']);
    Route::resource('/penghasilan',PenghasilanController::class)->except(['show']);
    Route::resource('/tahun-ajaran', TahunAjaranController::class)->except(['show']);
    Route::resource('/guru', GuruController::class)->except(['show']);
    Route::post('/guru-import', [GuruController::class, 'import'])->name('guru.import');
    Route::get('/format-guru-import', [GuruController::class, 'format'])->name('format.guru.import');
    Route::resource('/siswa', SiswaController::class);
    Route::post('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/format-siswa-import', [SiswaController::class, 'format'])->name('format.siswa.import');
    Route::resource('/kelas', KelasController::class);
    Route::resource('/jenjang-pendidikan', JenjangPendidikanController::class)->except(['show']);
    Route::resource('/bulan-spp', BulanSppController::class)->except(['show']);
    Route::resource('/presensi-kelas', PresensiKelasController::class)->except(['edit','update','destroy']);
    Route::resource('/presensi-ekstrakurikuler', PresensiEkstrakurikulerController::class)->except(['edit','update','destroy']);
    Route::resource('/pembayaran-spp', PembayaranSppController::class)->only(['index','store','destroy']);
    Route::post('/pembayaran-spp/cari', [PembayaranSppController::class, 'cari'])->name('pembayaran-spp.cari');
    Route::resource('/ekstrakurikuler', EkstrakurikulerController::class);
    Route::resource('/anggota-kelas', AnggotaKelasController::class)->only(['store', 'destroy']);
    Route::resource('/anggota-ekstrakurikuler', AnggotaEkstrakurikulerController::class)->only(['store', 'destroy']);
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard.index');
    Route::resource('/agenda', AgendaController::class)->except(['show']);
    Route::resource('/pengumuman', PengumumanController::class)->except(['show']);
    Route::resource('/tagihan-tahunan', TagihanTahunanController::class)->except(['show']);
    Route::resource('/pembayaran-tagihan-tahunan', PembayaranTagihanTahunanController::class)->only(['index','store']);
    Route::post('/pembayaran-tagihan-tahunan/cari', [PembayaranTagihanTahunanController::class, 'cari'])->name('pembayaran-tagihan-tahunan.cari');
    Route::get('/laporan-tagihan-tahunan',[LaporanKeuanganController::class,'indexTagihanTahunan'])->name('laporan-tagihan-tahunan.index');
    Route::get('/laporan-tagihan-tahunan/{kelas_id}',[LaporanKeuanganController::class,'showTagihanTahunan'])->name('laporan-tagihan-tahunan.show');
    Route::get('/laporan-tagihan-spp',[LaporanKeuanganController::class,'indexTagihanSpp'])->name('laporan-tagihan-spp.index');
    Route::get('/laporan-tagihan-spp/{kelas_id}',[LaporanKeuanganController::class,'showTagihanSpp'])->name('laporan-tagihan-spp.show');
    Route::resource('/presensi', PresensiController::class)->only(['index','show']);
    Route::resource('/keuangan', KeuanganController::class)->only(['index','show']);
    Route::get('/keuangan-spp/{id}', [KeuanganController::class,'bayarSpp'])->name('keuangan-spp.bayar');
    Route::post('/payment', [PaymentController::class, 'getSnapToken']);
    Route::post('/payment/notification', [PaymentController::class, 'paymentNotification']);
});

require __DIR__.'/auth.php';
