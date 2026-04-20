<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Http\Requests\KelasRequest;
use App\Services\KelasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function __construct(
        protected KelasService $service
    ) {}

    public function index(): View|RedirectResponse
    {
        $tahun = $this->service->getActiveTahunAjaran();
        
        if (!$tahun) {
            return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi tahun ajaran!');
        }

        $data_kelas = Kelas::with(['guru', 'pendamping'])
            ->withCount('anggotaKelas')
            ->where('tahun_ajaran_id', $tahun->id)
            ->orderBy('nama_kelas', 'ASC')
            ->get();

        return view('data_master.kelas.index', compact('data_kelas', 'tahun'));
    }

    public function show(Kelas $kelas): View
    {
        $anggota_kelas = $kelas->anggotaKelas()->with('siswa')->get();
        $siswa_belum_masuk_kelas = $this->service->getSiswaTanpaKelas();

        return view('data_master.kelas.show', [
            'kelas' => $kelas,
            'anggota_kelas' => $anggota_kelas,
            'siswa_belum_masuk_kelas' => $siswa_belum_masuk_kelas
        ]);
    }

    public function store(KelasRequest $request): RedirectResponse
    {
        try {
            $this->service->storeKelas($request->validated());
            return redirect()->route('kelas.index')->with('success', 'Kelas berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(KelasRequest $request, Kelas $kelas): RedirectResponse
    {
        $kelas->update($request->validated());
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
    
    // Create & Edit tetap sederhana
    public function create(): View {
        return view('data_master.kelas.create', [
            'guru' => Guru::whereStatus(true)->select('nipy','nama_lengkap','gelar')->get()
        ]);
    }
}