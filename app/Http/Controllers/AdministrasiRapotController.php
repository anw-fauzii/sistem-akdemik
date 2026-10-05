<?php

namespace App\Http\Controllers;

use App\Models\AdministrasiRapot;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\AnggotaKelas;
use App\Services\AdministrasiKelasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministrasiRapotController extends Controller
{
    public function __construct(
        protected AdministrasiKelasService $service
    ) {
    }

public function index()
    {
        $tahunAjaranAktif = TahunAjaran::latest()->firstOrFail();
        
        // Ambil data kelas aktif untuk mendeteksi tingkatan kelas saat ini
        $kelas = $this->service->getKelasAktif(Auth::user()->email, $tahunAjaranAktif->id);
        $kelasSekarang = $kelas->tingkatan_kelas ?? null; 

        // Terjemahkan huruf ke angka jangkauan tahun ke belakang
        $limitTahun = match ($kelasSekarang) {
            'PG'    => 1,
            'A'     => 2,
            'B'     => 3,
            default => is_numeric($kelasSekarang) ? (int)$kelasSekarang : 1,
        };

        // Ambil data tahun ajaran dan batasi sesuai jangkauan mengajar guru
        $tahunAjaran = TahunAjaran::orderBy('nama_tahun_ajaran', 'desc')
                                    ->take($limitTahun) 
                                    ->get();
        
        return view('administrasi.rapot.index', compact('tahunAjaran'));
    }

    /**
     * 2. Endpoint AJAX: Memuat Langsung Daftar Siswa Mengajar Guru Terkait (Lazy Load per Accordion)
     */
    public function getSiswaByTahun($tahunAjaranId)
    {
        $user = Auth::user();
        $tahunAjaranAktif = \App\Models\TahunAjaran::latest()->firstOrFail();
        $tahunAjaran=$tahunAjaranAktif->id;
        $daftarNisSiswaSaya = AnggotaKelas::whereHas('kelas', function($query) use ($tahunAjaran) {
                $query->where('guru_nipy', Auth::user()->email) // Kunci email guru Anda
                      ->where('tahun_ajaran_id', $tahunAjaran); // Kunci ID Tahun Ajaran di tabel kelas
            })
            ->pluck('siswa_nis'); // Mengambil array kumpulan NIS siswa Anda

        $riwayatAnggota = AnggotaKelas::whereIn('siswa_nis', $daftarNisSiswaSaya)
        ->whereHas('kelas', function($query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId); // Kunci ID Tahun Ajaran di tabel kelas
            })// Ambil data mereka di tahun ajaran masa lalu tersebut
        ->with(['siswa', 'kelas'])
        ->orderBy('id', 'ASC
        2433')->get();

        return view('administrasi.rapot._partials_siswa', compact('riwayatAnggota'))->render();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AdministrasiRapot $administrasiRapot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdministrasiRapot $administrasiRapot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdministrasiRapot $administrasiRapot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdministrasiRapot $administrasiRapot)
    {
        //
    }
}
