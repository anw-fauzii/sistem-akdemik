<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresensiKelasController extends Controller
{
    public function index(Request $request)
    {
        if (user()?->hasRole('guru')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();
            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
            }
            $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
            $bulan = $request->bulan ?? now()->format('Y-m'); 
            $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
                                ->whereMonth('tanggal', date('m', strtotime($bulan)))
                                ->whereYear('tanggal', date('Y', strtotime($bulan)))
                                ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')
                ->map(fn($item) => \Carbon\Carbon::parse($item)->toDateString()) // Ambil hanya tanggal
                ->unique()
                ->sort()
                ->values();
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            return view('presensi_kelas.index', compact('anggotaKelas', 'presensi', 'tanggal_tercatat', 'bulan_spp', 'bulan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('guru')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $kelas = Kelas::where('tahun_ajaran_id', $tahun_ajaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang diampu.');
            }
            $siswaList = AnggotaKelas::where('kelas_id', $kelas->id)->with('siswa')->get();
            return view('presensi_kelas.create', compact('siswaList', 'kelas'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('guru')) {
            $request->validate([
                'tanggal' => 'required|date',
                'presensi' => 'array',
                'waktu' => 'array',
            ]);

            $tanggal = Carbon::parse($request->tanggal)->toDateString();
            $jamMasukStandar = Carbon::parse($tanggal . ' 07:30:00');

            foreach ($request->presensi as $anggota_kelas_id => $status) {
                if (empty($status)) {
                    continue;
                }
                $waktuInput = $request->waktu[$anggota_kelas_id] ?? '07:30';
                $waktuPresensi = Carbon::parse($tanggal . ' ' . $waktuInput);

                $isLate = false;
                $lateMinutes = null;

                if ($status === 'Hadir') {
                    $isLate = $waktuPresensi->gt($jamMasukStandar);
                    $lateMinutes = $isLate ? $jamMasukStandar->diffInMinutes($waktuPresensi) : 0;
                }

                $existing = Presensi::whereDate('tanggal', $tanggal)
                    ->where('anggota_kelas_id', $anggota_kelas_id)
                    ->first();

                if (!$existing) {
                    Presensi::create([
                        'anggota_kelas_id' => $anggota_kelas_id,
                        'tanggal' => $waktuPresensi,
                        'status' => $status,
                        'terlambat' => $isLate,
                        'menit_terlambat' => $lateMinutes,
                    ]);
                }
            }

            return redirect()->route('presensi-kelas.index')->with('success', 'Presensi berhasil disimpan.');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function edit($id)
    {
        if (user()?->hasRole('guru')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $kelas = Kelas::where('tahun_ajaran_id', $tahun_ajaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang diampu.');
            }
            $siswaList = AnggotaKelas::where('kelas_id', $kelas->id)->get();
            $presensi = Presensi::whereIn('anggota_kelas_id', $siswaList->pluck('id'))
                                ->whereDate('tanggal', $id)
                                ->get();
            $tanggal = $id;
            return view('presensi_kelas.edit', compact('siswaList', 'kelas', 'presensi','tanggal'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        if (user()?->hasRole('guru')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $bulan_spp = BulanSpp::where('tahun_ajaran_id', $tahunAjaran->id)->get();
            $bulan = BulanSpp::findOrFail($id);
            $bulanFilter = Carbon::parse($bulan->bulan_angka)->format('Y-m');
            $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                        ->where('guru_nipy', Auth::user()->email)
                        ->first();

            if (!$kelas) {
                return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
            }
            $anggotaKelas = AnggotaKelas::where('kelas_id', $kelas->id)->get();
            $presensi = Presensi::whereIn('anggota_kelas_id', $anggotaKelas->pluck('id'))
                                ->where('tanggal', 'like', "$bulanFilter%")
                                ->get();
            $tanggal_tercatat = $presensi->pluck('tanggal')->unique()->sort();
            return view('presensi_kelas.show', compact('bulan', 'anggotaKelas', 'presensi', 'tanggal_tercatat','bulan_spp'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function handle(Request $request)
    {
        $originalData = $request->getContent();
        $decoded = json_decode($originalData, true);

        if (!isset($decoded['type'], $decoded['cloud_id'], $decoded['data'])) {
            return response()->json(['status' => 'invalid data'], 400);
        }

        $data = $decoded['data'];
        $pin = $data['pin'];
        $scanTime = Carbon::parse($data['scan']);
        $tanggal = $scanTime->format('Y-m-d');

        $jam_masuk = Carbon::parse($tanggal . ' 07:30:00');
        $isLate = $scanTime->gt($jam_masuk);
        $lateMinutes = $isLate ? $jam_masuk->diffInMinutes($scanTime) : 0;

        $siswa = Siswa::where('nis', $pin)->first();

        $anggota = $siswa->anggotaKelasAktif; 

        if (!$anggota) {
            Log::warning("PIN $pin tidak ditemukan di anggota_kelas");
            return response()->json(['status' => 'pin not found'], 404);
        }

        $presensi = Presensi::firstOrCreate(
            [
                'anggota_kelas_id' => $anggota->id,
                'tanggal' => $scanTime
            ],
            [
                'status' => 'Hadir',
                'terlambat' => $isLate,
                'menit_terlambat' => $lateMinutes
            ]
        );

        return response()->json(['status' => 'success'], 200);
    }
}
