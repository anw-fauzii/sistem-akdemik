<?php

namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Http\Requests\TahunAjaranRequest;
use App\Services\TahunAjaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function __construct(
        protected TahunAjaranService $service
    ) {}

    public function index(): View
    {
        return view('data_master.tahun_ajaran.index', [
            'tahun_ajaran' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('data_master.tahun_ajaran.create');
    }

    public function store(TahunAjaranRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil disimpan dan data siswa telah direset.');
    }

    public function edit(TahunAjaran $tahunAjaran): View
    {
        return view('data_master.tahun_ajaran.edit', [
            'tahun_ajaran' => $tahunAjaran
        ]);
    }

    public function update(TahunAjaranRequest $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->service->update($tahunAjaran, $request->validated());

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diupdate.');
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        try {
            $this->service->delete($tahunAjaran);
            return redirect()->route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}