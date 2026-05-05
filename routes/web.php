<?php

use App\Http\Controllers\Admin\DataMaster\KategoriAdministrasiController;
use App\Http\Controllers\Admin\DataMaster\KategoriMataPelajaranController;
use App\Http\Controllers\Admin\DataMaster\MataPelajaranController;
use App\Http\Controllers\Admin\DataMaster\PembelajaranController;
use App\Http\Controllers\Admin\DataMaster\TahunAjaranController;
use App\Http\Controllers\Admin\Informasi\AgendaController;
use App\Http\Controllers\Admin\Informasi\PengumumanController;
use App\Http\Controllers\Admin\DataPelengkap\BerkebutuhanKhususController;
use App\Http\Controllers\Admin\DataPelengkap\JenjangPendidikanController;
use App\Http\Controllers\Admin\DataPelengkap\PekerjaanController;
use App\Http\Controllers\Admin\DataPelengkap\PenghasilanController;
use App\Http\Controllers\Admin\DataPelengkap\TransportasiController;
use App\Http\Controllers\AdministrasiGuruController;
use App\Http\Controllers\AdministrasiKelasController;
use App\Http\Controllers\AdministrasiRapotController;
use App\Http\Controllers\AkumulasiPoinKedisiplinanController;
use App\Http\Controllers\AnggotaEkstrakurikulerController;
use App\Http\Controllers\AnggotaJemputanController;
use App\Http\Controllers\AnggotaKelasController;
use App\Http\Controllers\AnggotaT2QController;
use App\Http\Controllers\BulanSppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EkstrakurikulerController;
use App\Http\Controllers\ExportPdfController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JemputanController;
use App\Http\Controllers\KedisiplinanPoinController;
use App\Http\Controllers\KedisiplinanSiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\LaporanPresensiController;
use App\Http\Controllers\PembayaranJemputanController;
use App\Http\Controllers\PembayaranSppController;
use App\Http\Controllers\PembayaranTagihanTahunanController;
use App\Http\Controllers\PesanSaranController;
use App\Http\Controllers\PesertaDidik\KesehatanController as PesertaDidikKesehatanController;
use App\Http\Controllers\PesertaDidik\KeuanganController;
use App\Http\Controllers\PesertaDidik\KeuanganTahunanController;
use App\Http\Controllers\PesertaDidik\PresensiController;
use App\Http\Controllers\PresensiEkstrakurikulerController;
use App\Http\Controllers\PresensiKelasController;
use App\Http\Controllers\PrestasiSiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TagihanTahunanController;
use App\Http\Controllers\TarifSppController;
use App\Http\Controllers\TK\KesehatanController as TkKesehatanController;
use App\Http\Controllers\Puskesmas\KesehatanController as PuskesmasKesehatanController;
use App\Http\Controllers\SuratIzinController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
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
    return redirect()->route('login');
});

Route::get('/php-limits', function () {
    return [
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
});

Route::middleware(['auth','preventBackHistory'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/kategori-kebutuhan', BerkebutuhanKhususController::class)->except(['show']);
    Route::resource('/pekerjaan', PekerjaanController::class)->except(['show']);
    Route::resource('/transportasi', TransportasiController::class)->except(['show']);
    Route::resource('/penghasilan', PenghasilanController::class)->except(['show']);
    Route::resource('/tahun-ajaran', TahunAjaranController::class)->except(['show']);
    Route::resource('/guru', GuruController::class)->except(['show']);
    Route::post('/guru-import', [GuruController::class, 'import'])->name('guru.import');
    Route::get('/format-guru-import', [GuruController::class, 'format'])->name('format.guru.import');
    Route::resource('/siswa', SiswaController::class);
    Route::post('/siswa-import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/format-siswa-import', [SiswaController::class, 'format'])->name('format.siswa.import');
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('/jenjang-pendidikan', JenjangPendidikanController::class)->except(['show']);
    Route::resource('/bulan-spp', BulanSppController::class)->except(['show']);
    Route::resource('/presensi-kelas', PresensiKelasController::class)->except(['update','destroy']);
    Route::post('/presensi-kelas/destroy-massal', [PresensiKelasController::class, 'destroyMassal'])
    ->name('presensi-kelas.destroy-massal');
    Route::resource('/presensi-ekstrakurikuler', PresensiEkstrakurikulerController::class)->except(['edit','update','destroy'])->parameters([
        'presensi-ekstrakurikuler' => 'bulanSpp'
    ]);
    // Route Edit dan Update Harian
    Route::get('/presensi-ekstrakurikuler/edit-harian/{tanggal}', [App\Http\Controllers\PresensiEkstrakurikulerController::class, 'edit'])->name('presensi-ekstrakurikuler.edit-harian');
    Route::put('/presensi-ekstrakurikuler/update-harian/{tanggal}', [App\Http\Controllers\PresensiEkstrakurikulerController::class, 'update'])->name('presensi-ekstrakurikuler.update-harian');

// (Route::resource Anda yang sudah ada biarkan saja di bawahnya)
    Route::resource('/pembayaran-spp', PembayaranSppController::class)->only(['index','store','destroy']);
    Route::post('/pembayaran-spp/cari', [PembayaranSppController::class, 'cari'])->name('pembayaran-spp.cari');
    Route::resource('/ekstrakurikuler', EkstrakurikulerController::class);
    Route::resource('/anggota-kelas', AnggotaKelasController::class)->only(['index','store', 'destroy']);
    Route::resource('/anggota-ekstrakurikuler', AnggotaEkstrakurikulerController::class)->only(['store', 'destroy']);
    Route::resource('/anggota-t2q', AnggotaT2QController::class)->only(['index','store', 'destroy','show'])->parameters([
        'anggota-t2q' => 'anggotaT2Q'
    ]);
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
    Route::resource('/keuangan-spp', KeuanganController::class)->only(['index','show']);
    Route::get('/keuangan-spp/bayar/{id}', [KeuanganController::class,'bayarSpp'])->name('keuangan-spp.bayar');
    Route::get('/lanjut-pembayaran-spp/{id}', [KeuanganController::class, 'lanjut'])->name('keuangan-spp.lanjut');
    Route::get('/cetak-invoice-spp/{id}', [KeuanganController::class, 'cetakInvoice'])->name('keuangan-spp.invoice');
    Route::resource('/keuangan-tahunan', KeuanganTahunanController::class)->only(['index','show','store']);
    Route::get('/lanjut-pembayaran-tahunan/{id}', [KeuanganTahunanController::class, 'lanjut'])->name('keuangan-tahunan.lanjut');
    Route::get('/cetak-invoice-tahunan/{id}', [KeuanganTahunanController::class, 'cetakInvoice'])->name('keuangan-tahunan.invoice');
    Route::get('/QR-Code', [QRCodeController::class,'index'])->name('qr-code.index');
    Route::get('/profil-siswa', [UserController::class, 'profil'])->name('profil-siswa');
    Route::get('/update-password', [UserController::class, 'password'])->name('update-password');
    Route::put('/update-password/update', [UserController::class, 'update'])->name('update-password.update');
    Route::resource('/jemputan', JemputanController::class);
    Route::resource('/anggota-jemputan', AnggotaJemputanController::class)->only(['store', 'destroy']);
    Route::resource('/pembayaran-jemputan', PembayaranJemputanController::class);
    Route::post('/pembayaran-jemputan/cari', [PembayaranJemputanController::class, 'cari'])->name('pembayaran-jemputan.cari');
    Route::resource('tarif-spp', TarifSppController::class);
    Route::get('/laporan/presensi-hari-ini', [LaporanPresensiController::class, 'presensiHariIni'])->name('laporan.presensi.hari_ini');
    Route::get('/laporan/presensi', [LaporanPresensiController::class, 'index'])->name('laporan.presensi.index');
    Route::get('/laporan/ambil-data-harian', [LaporanPresensiController::class, 'ambilHariIni'])->name('laporan.presensi.ambil_harian');
    Route::get('/laporan/presensi-pekanan', [LaporanPresensiController::class, 'pekanan'])->name('laporan.presensi.pekanan');
    Route::post('/laporan/presensi-pekanan/cari', [LaporanPresensiController::class, 'pekananCari'])->name('laporan.presensi.pekanan.cari');
    Route::get('/laporan/presensi-bulanan/{bulanSpp?}', [LaporanPresensiController::class, 'bulanan'])->name('laporan.presensi.bulanan');
    Route::get('/pdf-laporan-bulanan/{id}', [ExportPdfController::class, 'laporanBulananPdf'])->name('export.laporan.bulanan.pdf');
    Route::get('/excel-laporan-bulanan/{id}', [ExportPdfController::class, 'laporanBulananExcel'])->name('export.laporan.bulanan.excel');
    Route::get('/pdf-laporan-bulanan-kelas/{kelas_id}/{bulan_id}', [ExportPdfController::class, 'laporanBulananKelasPdf'])->name('export.laporan.bulanan.kelas.pdf');
    Route::resource('/data-kesehatan', TkKesehatanController::class)->parameters([
        'data-kesehatan' => 'bulanSpp'
    ]);
    Route::get('/kelas-pg-tk', [PuskesmasKesehatanController::class, 'indexKelas'])
        ->name('kelas.pgtk.index.kelas');
    Route::get('/kelas-pg-tk/{bulanSpp}', [PuskesmasKesehatanController::class, 'indexKelas'])
        ->name('kelas.pgtk.show.kelas');
    Route::get('/kelas-pg-tk/detail/{bulanSpp}/{kelas}', [PuskesmasKesehatanController::class, 'detailKelas'])
        ->name('kelas.pgtk.detail.kelas');
    Route::get('/kelas-pg-tk/detail/{bulanSpp}/{kelas}/edit', [PuskesmasKesehatanController::class, 'editKelas'])
        ->name('kelas.pgtk.edit.kelas');

    Route::resource('/kesehatan-siswa', PesertaDidikKesehatanController::class)->only(['index','show']);

    Route::resource('/kategori-mata-pelajaran', KategoriMataPelajaranController::class)->except(['show']);
    Route::resource('/daftar-mata-pelajaran', MataPelajaranController::class);
    Route::resource('/pembelajaran', PembelajaranController::class);

    Route::resource('/administrasi-guru', AdministrasiGuruController::class);
    Route::resource('/administrasi-kelas', AdministrasiKelasController::class)->parameters([
        'administrasi-kelas' => 'administrasiKelas'
    ]);
    Route::resource('/administrasi-rapot', AdministrasiRapotController::class);
    Route::resource('/prestasi-siswa', PrestasiSiswaController::class);
    Route::resource('/surat-izin', SuratIzinController::class);
    Route::resource('/kategori-administrasi', KategoriAdministrasiController::class);
    Route::resource('/pesan-saran', PesanSaranController::class)->parameters([
        'pesan-saran' => 'pesanSaran'
    ]);
    Route::post('pesan-saran/{pesanSaran}/kirim', [PesanSaranController::class, 'kirim'])->name('pesan-saran.kirim');
    Route::get('pesan-saran/{pesanSaran}/fetch', [PesanSaranController::class, 'fetch'])->name('pesan-saran.fetch');
    Route::get('/pesan-saran-data', [PesanSaranController::class, 'data'])->name('pesan-saran.index.data');
    Route::resource('/kedisiplinan-poin', KedisiplinanPoinController::class)->except(['show']);
    Route::resource('/kedisiplinan-siswa', KedisiplinanSiswaController::class)->except(['show']);
    Route::get('/akumulasi-poin-kedisiplinan', [AkumulasiPoinKedisiplinanController::class, 'index'])->name('akumulasi-poin-kedisiplinan.index');
});
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Clear Config cleared</h1>';
});
require __DIR__.'/auth.php';