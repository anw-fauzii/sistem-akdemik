<?php

namespace App\Http\Controllers\TK;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKelas;
use App\Models\BulanSpp;
use App\Models\Kelas;
use App\Models\Kesehatan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KesehatanController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('guru_nipy', Auth::user()->email)
                    ->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }
        $anggotaKelasList = AnggotaKelas::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->get();
        $bulanTerbaru = BulanSpp::latest()->first();
        $bulan_spp = BulanSpp::whereTahunAjaranId($tahunAjaran->id)->get();
        $dataKesehatan = Kesehatan::where('bulan_spp_id', $bulanTerbaru->id ?? null)
            ->whereIn('anggota_kelas_id', $anggotaKelasList->pluck('id'))
            ->get()
            ->keyBy('anggota_kelas_id');
        return view('tk.kesehatan.index', compact('bulan_spp','anggotaKelasList','dataKesehatan','bulanTerbaru'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_kelas_id' => 'required|array',
            'bulan_spp_id' => 'required|exists:bulan_spp,id',
        ]);

        $bulanId = $request->bulan_spp_id;

        foreach ($request->anggota_kelas_id as $anggotaId) {
            $data = [
                'tb' => $request->tb[$anggotaId] ?? null,
                'bb' => $request->bb[$anggotaId] ?? null,
                'lila' => $request->lila[$anggotaId] ?? null,
                'lika' => $request->lika[$anggotaId] ?? null,
                'lp' => $request->lp[$anggotaId] ?? null,
                'mata' => $request->mata[$anggotaId] ?? null,
                'telinga' => $request->telinga[$anggotaId] ?? null,
                'gigi' => $request->gigi[$anggotaId] ?? null,
                'hasil' => $request->hasil[$anggotaId] ?? null,
                'tensi' => $request->tensi[$anggotaId] ?? null,
            ];
        if (collect($data)->filter()->isEmpty()) {
            continue;
        }
        Kesehatan::updateOrCreate(
            [
                'anggota_kelas_id' => $anggotaId,
                'bulan_spp_id' => $bulanId,
                ],
                $data
            );
        }
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('guru_nipy', Auth::user()->email)
                    ->first();
        if ($kelas) {
            return redirect()->route('data-kesehatan.index')->with('success', 'Data kesehatan berhasil disimpan.');
        }else{
            return redirect()->route('kelas.pgtk.show.kelas', $bulanId)->with('success', 'Data kesehatan berhasil disimpan.');
        }
        
    }


    public function show($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('guru_nipy', Auth::user()->email)
                    ->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }
        $anggotaKelasList = AnggotaKelas::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->get();
        $bulanTerbaru = BulanSpp::findOrFail($id);
        $bulan_spp = BulanSpp::whereTahunAjaranId($tahunAjaran->id)->get();
        $dataKesehatan = Kesehatan::where('bulan_spp_id', $bulanTerbaru->id ?? null)
            ->whereIn('anggota_kelas_id', $anggotaKelasList->pluck('id'))
            ->get()
            ->keyBy('anggota_kelas_id');
        return view('tk.kesehatan.index', compact('bulan_spp','anggotaKelasList','dataKesehatan','bulanTerbaru'));
    }

    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('guru_nipy', Auth::user()->email)
                    ->first();
        if (!$kelas) {
            return redirect()->back()->with('error', 'Anda tidak mengajar kelas mana pun.');
        }
        $bulanTerbaru = BulanSpp::findOrFail($id);
        $anggotaKelasList = AnggotaKelas::with(['siswa', 'dataKesehatan' => function ($query) use ($bulanTerbaru) {
            $query->where('bulan_spp_id', $bulanTerbaru->id);
        }])->where('kelas_id', $kelas->id)->get();
        $semuaKosong = $anggotaKelasList->every(function ($anggota) {
            return $anggota->dataKesehatan === null;
        });
        return view('tk.kesehatan.create', compact('anggotaKelasList','bulanTerbaru','semuaKosong'));
    }

    public function update(Request $request, Kesehatan $kesehatan)
    {
        //
    }

    public function destroy(Kesehatan $kesehatan)
    {
        //
    }

}
