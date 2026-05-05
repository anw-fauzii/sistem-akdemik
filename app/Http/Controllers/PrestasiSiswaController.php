<?php

namespace App\Http\Controllers;

use App\Models\PrestasiSiswa;
use App\Http\Requests\PrestasiSiswaRequest;
use App\Services\PrestasiService;
use App\Services\PosterGeneratorService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PrestasiSiswaController extends Controller
{
    public function __construct(
        protected PrestasiService $service,
        protected PosterGeneratorService $posterService
    ) {}

    public function index(): View
    {
        $prestasi = $this->service->getPrestasiForUser(auth()->user());
        $view = user()?->hasRole('admin') ? 'admin_index' : 'siswa_index';
        
        return view("prestasi_siswa.$view", compact('prestasi'));
    }

    /**
     * Menampilkan form Tambah Prestasi
     */
    public function create(): View
    {
        $anggotaKelas = $this->service->getAnggotaKelasForSelection();
        
        return view('prestasi_siswa.create', compact('anggotaKelas'));
    }

    public function store(PrestasiSiswaRequest $request): RedirectResponse
    {
        $this->service->store($request->validated(), $request->file('file_sertifikat'));
        
        return redirect()->route('prestasi-siswa.index')
            ->with('success', 'Data prestasi berhasil ditambahkan');
    }

    /**
     * Menampilkan form Edit Prestasi
     */
    public function edit(PrestasiSiswa $prestasiSiswa): View
    {
        $prestasi = $prestasiSiswa->load('anggotaKelas'); 
        $anggotaKelas = $this->service->getAnggotaKelasForSelection();

        return view('prestasi_siswa.edit', compact('prestasi', 'anggotaKelas'));
    }

    public function update(PrestasiSiswaRequest $request, PrestasiSiswa $prestasiSiswa): RedirectResponse
    {
        $this->service->update($prestasiSiswa, $request->validated(), $request->file('file_sertifikat'));
        
        return redirect()->route('prestasi-siswa.index')
            ->with('success', 'Data prestasi berhasil diperbarui');
    }

    public function show(PrestasiSiswa $prestasiSiswa)
    {
        $canvas = $this->posterService->generate($prestasiSiswa);

        return response()->streamDownload(function () use ($canvas) {
            echo $canvas->toPng();
        }, "poster-prestasi-{$prestasiSiswa->id}.png");
    }

    public function destroy(PrestasiSiswa $prestasiSiswa): RedirectResponse
    {
        $this->service->delete($prestasiSiswa);
        
        return redirect()->route('prestasi-siswa.index')
            ->with('success', 'Data prestasi berhasil dihapus');
    }
}