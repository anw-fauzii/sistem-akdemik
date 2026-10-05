<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\AnggotaT2Q;
use App\Models\TahunAjaran;
use App\Http\Requests\StoreAnggotaT2QRequest;
use App\Services\AnggotaT2QService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggotaT2QController extends Controller
{
    public function __construct(
        protected AnggotaT2QService $service
    ) {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->first();

        if (!$tahunAjaran) {
            return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi terlebih dahulu tahun ajaran!');
        }

        $data_guru = Guru::withCount('anggotaT2q')
            ->where('jabatan', 'Guru T2Q')
            ->where('status', true)
            ->get();

        return view('data_master.t2q.index', compact('data_guru', 'tahunAjaran'));
    }

    public function store(StoreAnggotaT2QRequest $request): RedirectResponse
    {
        try {
            $this->service->assignBulk(
                $request->validated('anggota_kelas_ids'),
                $request->validated('guru_nipy'),
                $request->validated('tingkat')
            );

            return back()->with('success', 'Anggota T2Q berhasil ditambahkan');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal tambah T2Q: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function show($nipy): View
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $guru = Guru::where('nipy', $nipy)->firstOrFail();
        
        $anggota_t2q = AnggotaT2Q::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('guru_nipy', $guru->nipy)
            ->whereHas('anggotaKelas.kelas', fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaran->id))
            ->get();

        $siswa_belum_masuk_t2q = $this->service->getSiswaBelumMasuk($tahunAjaran->id);
        
        // TAMBAHAN: Ambil daftar guru lain untuk pilihan pemindahan
        $list_guru = Guru::where('jabatan', 'Guru T2Q')
            ->where('status', true)
            ->where('nipy', '!=', $guru->nipy) // Jangan tampilkan guru yang sedang dibuka
            ->get();

        return view('data_master.t2q.show', compact('guru', 'anggota_t2q', 'siswa_belum_masuk_t2q', 'list_guru'));
    }

    public function update(Request $request, AnggotaT2Q $anggotaT2Q): RedirectResponse
    {
        $request->validate([
            'guru_nipy' => 'required|exists:guru,nipy'
        ], [
            'guru_nipy.required' => 'Pilih guru tujuan terlebih dahulu.'
        ]);

        try {
            $this->service->changeGuru($anggotaT2Q, $request->guru_nipy);
            return back()->with('success', 'Siswa berhasil dipindahkan ke guru lain.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal pindah guru T2Q: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memindahkan siswa.');
        }
    }

    public function destroy(AnggotaT2Q $anggotaT2Q): RedirectResponse
    {
        try {
            $this->service->remove($anggotaT2Q);
            return back()->with('success', 'Anggota T2Q berhasil dihapus');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal hapus T2Q: ' . $e->getMessage());
            return back()->with('error', 'Anggota T2Q tidak dapat dihapus');
        }
    }
}