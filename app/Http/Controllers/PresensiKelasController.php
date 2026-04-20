<?php

namespace App\Http\Controllers;

use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\AnggotaKelas;
use App\Models\TahunAjaran;
use App\Http\Requests\StorePresensiRequest;
use App\Services\PresensiKelasService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiKelasController extends Controller
{
    public function __construct(
        protected PresensiKelasService $service
    ) {
        $this->middleware('auth')->except('handle'); // Handle diakses mesin
    }

    public function index()
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = $this->service->getKelasContext(Auth::user(), $tahunAjaran);

        if (!$kelas) return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');

        $bulan = BulanSpp::latest()->firstOrFail();
        $tanggalAwal = Carbon::parse($bulan->bulan_angka);
        
        $statistik = $this->service->hitungStatistik($kelas->id, $tanggalAwal, $tanggalAwal->copy()->endOfMonth());

        return view('presensi_kelas.index', [
            'bulan'       => $bulan,
            'bulan_spp'   => BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get(),
            'kelas'       => $kelas,
            ...$statistik
        ]);
    }

    public function edit(string $tanggal)
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // 1. Re-use Service: Mengambil kelas yang diajar guru dengan sangat bersih
        $kelas = $this->service->getKelasContext(Auth::user(), $tahunAjaran);

        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang diampu.');
        }

        // 2. Eager Loading: Ambil daftar siswa agar tidak N+1 di view
        $siswaList = AnggotaKelas::with('siswa')->where('kelas_id', $kelas->id)->get();

        // 3. Ambil data presensi khusus di tanggal tersebut
        $presensi = Presensi::whereIn('anggota_kelas_id', $siswaList->pluck('id'))
            ->whereDate('tanggal', \Carbon\Carbon::parse($tanggal)->toDateString())
            ->get()
            ->keyBy('anggota_kelas_id'); // <--- OPTIMASI SUPER: Ubah menjadi Dictionary/Lookup Table!

        return view('presensi_kelas.edit', compact('siswaList', 'kelas', 'presensi', 'tanggal'));
    }

    public function create()
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = $this->service->getKelasContext(Auth::user(), $tahunAjaran);

        if (!$kelas) return redirect()->back()->with('error', 'Anda tidak memiliki kelas.');

        $siswaList = AnggotaKelas::with('siswa')->where('kelas_id', $kelas->id)->get();
        return view('presensi_kelas.create', compact('siswaList', 'kelas'));
    }

    public function store(StorePresensiRequest $request)
    {
        $this->service->simpanPresensiMassal($request->validated());
        return redirect()->route('presensi-kelas.index')->with('success', 'Presensi berhasil disimpan.');
    }

    public function show($id)
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        
        // Cek role untuk menentukan dari mana bulan diambil
        $bulan = user()?->hasRole('guru_sd') ? BulanSpp::findOrFail($id) : BulanSpp::latest()->firstOrFail();
        $kelasId = user()?->hasRole('guru_sd') ? null : $id; // Jika admin, parameter id adalah kelasId

        $kelas = $this->service->getKelasContext(user(), $tahunAjaran, $kelasId);

        if (!$kelas) return redirect()->back()->with('error', 'Kelas tidak ditemukan.');

        $tanggalAwal = Carbon::parse($bulan->bulan_angka);
        $statistik = $this->service->hitungStatistik($kelas->id, $tanggalAwal, $tanggalAwal->copy()->endOfMonth());

        return view('presensi_kelas.index', [
            'bulan'       => $bulan,
            'bulan_spp'   => BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get(),
            'data_kelas'  => Kelas::whereTahunAjaranId($tahunAjaran->id)->whereJenjang('SD')->get(),
            'kelas'       => $kelas,
            ...$statistik
        ]);
    }

    /**
     * API Endpoint untuk Mesin Scanner (IoT)
     */
    public function handle(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['type'], $data['cloud_id'], $data['data'])) {
            return response()->json(['status' => 'invalid data'], 400);
        }

        $scanTime = Carbon::parse($data['data']['scan']);
        $jam_masuk = $scanTime->copy()->setTime(7, 30, 0);

        // Cari anggota kelas aktif (Lebih aman dari cara lama)
        $tahunAjaran = TahunAjaran::latest()->first();
        $anggota = AnggotaKelas::whereHas('siswa', fn($q) => $q->where('nis', $data['data']['pin']))
                    ->whereHas('kelas', fn($q) => $q->where('tahun_ajaran_id', $tahunAjaran->id))
                    ->first();

        if (!$anggota) {
            \Illuminate\Support\Facades\Log::warning("PIN {$data['data']['pin']} tidak memiliki kelas aktif.");
            return response()->json(['status' => 'pin not found'], 404);
        }

        Presensi::firstOrCreate(
            [
                'anggota_kelas_id' => $anggota->id,
                'tanggal'          => $scanTime->format('Y-m-d H:i:s')
            ],
            [
                'status'          => 'Hadir',
                'terlambat'       => $scanTime->gt($jam_masuk),
                'menit_terlambat' => $scanTime->gt($jam_masuk) ? $jam_masuk->diffInMinutes($scanTime) : 0
            ]
        );

        return response()->json(['status' => 'success'], 200);
    }
}