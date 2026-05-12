<?php

namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\TargetCapaianTahsin;
use App\Http\Requests\TargetCapaianTahsinRequest;
use App\Models\DaftarJilid;
use App\Models\SurahAlquran;
use App\Models\TahunAjaran;
use App\Services\TargetCapaianTahsinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TargetCapaianTahsinController extends Controller
{
    public function __construct(
        protected TargetCapaianTahsinService $service
    ) {}

    public function index(): View
    {
        return view('data_master.target_capaian_t2q.index', [
            'target' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        $tahunAjaran = TahunAjaran::latest()->first();
        $daftarJilid = DaftarJilid::all();
        $surahAlquran = SurahAlquran::all();
        return view('data_master.target_capaian_t2q.create', compact('tahunAjaran', 'daftarJilid', 'surahAlquran'));
    }

    public function store(TargetCapaianTahsinRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('target-capaian-tahsin.index')
            ->with('success', 'Target Capaian Tahsin berhasil disimpan');
    }

    public function edit(TargetCapaianTahsin $targetCapaianTahsin): View
    {
        return view('data_master.target_capaian_t2q.edit', [
            'targetCapaian' => $targetCapaianTahsin,
            'daftarJilid' => DaftarJilid::all(),
            'surahAlquran' => SurahAlquran::all(),
        ]);
    }

    public function update(TargetCapaianTahsinRequest $request, TargetCapaianTahsin $targetCapaianTahsin): RedirectResponse
    {
        $this->service->update($targetCapaianTahsin, $request->validated());

        return redirect()->route('target-capaian-tahsin.index')
            ->with('success', 'Target Capaian Tahsin berhasil diupdate');
    }

    public function destroy(TargetCapaianTahsin $targetCapaianTahsin): RedirectResponse
    {
        $this->service->delete($targetCapaianTahsin);

        return redirect()->route('target-capaian-tahsin.index')
            ->with('success', 'Target Capaian Tahsin berhasil dihapus');
    }
}