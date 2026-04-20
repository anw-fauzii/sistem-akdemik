<?php

namespace App\Http\Controllers;

use App\Models\BulanSpp;
use App\Http\Requests\BulanSppRequest;
use App\Services\BulanSppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BulanSppController extends Controller
{
    public function __construct(
        protected BulanSppService $service
    ) {}

    public function index(): View
    {
        return view('data_master.bulan_spp.index', [
            'bulan_spp' => $this->service->getAllInActiveYear()
        ]);
    }

    public function create(): View
    {
        return view('data_master.bulan_spp.create');
    }

    public function store(BulanSppRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('bulan-spp.index')
            ->with('success', 'Bulan SPP berhasil disimpan.');
    }

    public function edit(BulanSpp $bulanSpp): View
    {
        return view('data_master.bulan_spp.edit', compact('bulan_spp'));
    }

    public function update(BulanSppRequest $request, BulanSpp $bulanSpp): RedirectResponse
    {
        $this->service->update($bulanSpp, $request->validated());

        return redirect()->route('bulan-spp.index')
            ->with('success', 'Bulan SPP berhasil diupdate.');
    }

    public function destroy(BulanSpp $bulanSpp): RedirectResponse
    {
        try {
            $this->service->delete($bulanSpp);
            return redirect()->route('bulan-spp.index')->with('success', 'Bulan SPP berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}