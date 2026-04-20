<?php

namespace App\Http\Controllers;

use App\Models\TarifSpp;
use App\Http\Requests\TarifSppRequest;
use App\Services\TarifSppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TarifSppController extends Controller
{
    public function __construct(
        protected TarifSppService $service
    ) {
        // Asumsi middleware diset di route, tapi bisa juga di sini
        // $this->middleware(['auth', 'role:admin']);
    }

    public function index(): View
    {
        return view('data_master.tarif_spp.index', [
            'tarif_spp' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('data_master.tarif_spp.create');
    }

    public function store(TarifSppRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('tarif-spp.index')
            ->with('success', 'Tarif SPP berhasil disimpan.');
    }

    public function edit(TarifSpp $tarifSpp): View
    {
        return view('data_master.tarif_spp.edit', compact('tarifSpp'));
    }

    public function update(TarifSppRequest $request, TarifSpp $tarifSpp): RedirectResponse
    {
        $this->service->update($tarifSpp, $request->validated());

        return redirect()->route('tarif-spp.index')
            ->with('success', 'Tarif SPP berhasil diperbarui.');
    }

    public function destroy(TarifSpp $tarifSpp): RedirectResponse
    {
        try {
            $this->service->delete($tarifSpp);
            return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}