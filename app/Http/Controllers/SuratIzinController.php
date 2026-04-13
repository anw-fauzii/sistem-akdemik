<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\SuratIzin;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratIzinController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('siswa_sd')) {
            $tahunAjaran = TahunAjaran::latest()->first();
            $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                        ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                            $query->where('tahun_ajaran_id', $tahunAjaran->id);
                        })
                        ->first();
            if (!$anggotaKelas) {
                return redirect()->back()->with('error', 'Anda belum masuk kelas mana pun.');
            }
            $suratIzin = SuratIzin::where('anggota_kelas_id', $anggotaKelas->id)
                            ->latest()
                            ->get();
            return view('surat_izin.siswa_index', compact('suratIzin'));
        } else {
            $suratIzin = SuratIzin::with('anggotaKelas.siswa', 'anggotaKelas.kelas')
                            ->latest()
                            ->get();
            return view('surat_izin.admin_index', compact('suratIzin'));
        }

    }

    public function create()
    {
        return view('surat_izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:sakit,izin,lainnya',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $tahunAjaran = TahunAjaran::latest()->first();
        $anggotaKelas = AnggotaKelas::whereSiswaNis(Auth::user()->email)
                    ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                        $query->where('tahun_ajaran_id', $tahunAjaran->id);
                    })
                    ->first();
        if (!$anggotaKelas) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan di tahun ajaran aktif');
        }

        $data['anggota_kelas_id'] = $anggotaKelas->id;

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('surat_izin', 'public');
        }

        SuratIzin::create($data);

        return redirect()->route('surat-izin.index')->with('success', 'Surat izin berhasil dikirim');
    }

    public function show($id)
    {
        $surat = SuratIzin::with('anggotaKelas.siswa', 'anggotaKelas.kelas')
            ->findOrFail($id);

        // 🔒 Kalau ortu, hanya boleh lihat data sendiri
        if (auth()->user()->role == 'ortu' && $surat->anggota_kelas_id != auth()->user()->anggota_kelas_id) {
            abort(403);
        }

        return view('surat_izin.show', compact('surat'));
    }

    public function edit($id)
    {
        $surat = SuratIzin::findOrFail($id);

        if ($surat->tanggal < now()->toDateString()) {
            return redirect()->back()->with('error', 'Data lama tidak bisa diedit');
        }

        return view('surat_izin.edit', compact('surat'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratIzin::findOrFail($id);

        if ($surat->tanggal < now()->toDateString()) {
            return redirect()->back()->with('error', 'Data lama tidak bisa diubah');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:sakit,izin,lainnya',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->all();

        // Update file
        if ($request->hasFile('file')) {

            if ($surat->file) {
                Storage::disk('public')->delete($surat->file);
            }

            $data['file'] = $request->file('file')->store('surat_izin', 'public');
        }

        $surat->update($data);

        return redirect()->route('surat-izin.index')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $surat = SuratIzin::findOrFail($id);

        if ($surat->tanggal < now()->toDateString()) {
            return redirect()->back()->with('error', 'Data lama tidak bisa dihapus');
        }

        if ($surat->file) {
            Storage::disk('public')->delete($surat->file);
        }

        $surat->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
