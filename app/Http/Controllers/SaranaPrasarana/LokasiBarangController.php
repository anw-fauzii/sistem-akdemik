<?php

namespace App\Http\Controllers\SaranaPrasarana;

use App\Http\Controllers\Controller;
use App\Models\LokasiBarang;
use App\Http\Requests\LokasiBarangRequest;
use App\Services\LokasiBarangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class LokasiBarangController extends Controller
{
    public function __construct(
        protected LokasiBarangService $service
    ) {}

    public function index(): View
    {
        return view('sarana_prasarana.lokasi_barang.index', [
            'lokasi' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('sarana_prasarana.lokasi_barang.create');
    }

    public function store(LokasiBarangRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('lokasi-barang.index')
            ->with('success', 'Lokasi berhasil disimpan');
    }

    public function edit(LokasiBarang $lokasiBarang): View
    {
        return view('sarana_prasarana.lokasi_barang.edit', [
            'lokasi' => $lokasiBarang
        ]);
    }

    public function update(LokasiBarangRequest $request, LokasiBarang $lokasiBarang): RedirectResponse
    {
        $this->service->update($lokasiBarang, $request->validated());

        return redirect()->route('lokasi-barang.index')
            ->with('success', 'Lokasi berhasil diupdate');
    }

    public function destroy(LokasiBarang $lokasiBarang): RedirectResponse
    {
        $this->service->delete($lokasiBarang);

        return redirect()->route('lokasi-barang.index')
            ->with('success', 'Lokasi berhasil dihapus');
    }
}