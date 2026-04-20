<?php

namespace App\Http\Controllers;

use App\Models\Jemputan;
use App\Services\JemputanService;
use App\Http\Requests\JemputanRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JemputanController extends Controller
{
    public function __construct(
        protected JemputanService $service
    ){}
    
    public function index(): View
    {
        $tahun = $this->service->getActiveTahunAjaran();
        $jemputan = $this->service->getAllJemputan($tahun->id);

        return view('data_master.jemputan.index', compact('jemputan'));
    }

    public function create(): View
    {
        return view('data_master.jemputan.create');
    }

    public function store(JemputanRequest $request): RedirectResponse
    {
        $tahun = $this->service->getActiveTahunAjaran();
        $this->service->store($request->validated(), $tahun->id);

        return redirect()->route('jemputan.index')->with('success', 'Jemputan berhasil disimpan');
    }

    public function show(Jemputan $jemputan): View
    {
        $tahun = $this->service->getActiveTahunAjaran();
        
        $anggota_jemputan = $jemputan->anggotaJemputan()
            ->with(['anggotaKelas.siswa', 'anggotaKelas.kelas'])
            ->get();

        $siswa_belum_masuk_jemputan = $this->service->getSiswaTersedia($tahun->id);

        return view('data_master.jemputan.show', compact('jemputan', 'anggota_jemputan', 'siswa_belum_masuk_jemputan'));
    }

    public function edit(Jemputan $jemputan): View
    {
        return view('data_master.jemputan.edit', compact('jemputan'));
    }

    public function update(JemputanRequest $request, Jemputan $jemputan): RedirectResponse
    {
        $jemputan->update($request->validated());
        return redirect()->route('jemputan.index')->with('success', 'Jemputan berhasil diupdate');
    }

    public function destroy(Jemputan $jemputan): RedirectResponse
    {
        $jemputan->delete();
        return redirect()->route('jemputan.index')->with('success', 'Jemputan berhasil dihapus');
    }
}