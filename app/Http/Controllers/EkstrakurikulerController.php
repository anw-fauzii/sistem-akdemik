<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\AnggotaEkstrakurikuler;
use App\Http\Requests\EkstrakurikulerRequest;
use App\Services\EkstrakurikulerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EkstrakurikulerController extends Controller
{
    public function __construct(
        protected EkstrakurikulerService $service
    ) {}

    public function index(): View|RedirectResponse
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        
        if (!$tahun_ajaran) {
            return redirect()->route('tahun-ajaran.index')->with('warning', 'Isi terlebih dahulu tahun ajaran!');
        }

        $ekstrakurikuler = Ekstrakurikuler::withCount('anggotaEkstrakurikuler')
            ->where('tahun_ajaran_id', $tahun_ajaran->id)
            ->orderBy('id', 'ASC')
            ->get();

        return view('data_master.ekstrakurikuler.index', compact('ekstrakurikuler', 'tahun_ajaran'));
    }

    public function create(): View
    {
        $guru = Guru::select('nipy', 'nama_lengkap', 'gelar')->where('status', true)->get();
        return view('data_master.ekstrakurikuler.create', compact('guru'));
    }

    public function store(EkstrakurikulerRequest $request): RedirectResponse
    {
        $tahun = TahunAjaran::latest()->firstOrFail();
        
        $data = $request->validated();
        $data['tahun_ajaran_id'] = $tahun->id;

        Ekstrakurikuler::create($data);   
        
        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil disimpan');
    }

    public function show(Ekstrakurikuler $ekstrakurikuler): View
    {
        $tahun_ajaran = TahunAjaran::latest()->firstOrFail();
        
        // Eager load untuk mencegah N+1 di halaman Show
        $anggota_ekstrakurikuler = AnggotaEkstrakurikuler::with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->get();

        $siswa_belum_masuk_ekstrakurikuler = $this->service->getSiswaBelumMasuk($tahun_ajaran->id);

        return view('data_master.ekstrakurikuler.show', compact(
            'ekstrakurikuler', 
            'anggota_ekstrakurikuler', 
            'siswa_belum_masuk_ekstrakurikuler'
        ));
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler): View
    {
        $guru = Guru::select('nipy', 'nama_lengkap', 'gelar')->where('status', true)->get();
        return view('data_master.ekstrakurikuler.edit', compact('ekstrakurikuler', 'guru'));
    }

    public function update(EkstrakurikulerRequest $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $ekstrakurikuler->update($request->validated());
        
        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil diupdate');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $ekstrakurikuler->delete();
        
        return redirect()->route('ekstrakurikuler.index')->with('success', 'Ekstrakurikuler berhasil dihapus');
    }
}