<?php

namespace App\Http\Controllers\SaranaPrasarana;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use App\Http\Requests\KategoriBarangRequest;
use App\Services\KategoriBarangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriBarangController extends Controller
{
    public function __construct(
        protected KategoriBarangService $service
    ) {}

    public function index(): View
    {
        return view('sarana_prasarana.kategori_barang.index', [
            'kategori' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('sarana_prasarana.kategori_barang.create');
    }

    public function store(KategoriBarangRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori berhasil disimpan');
    }

    public function edit(KategoriBarang $kategoriBarang): View
    {
        return view('sarana_prasarana.kategori_barang.edit', [
            'kategori' => $kategoriBarang
        ]);
    }

    public function update(KategoriBarangRequest $request, KategoriBarang $kategoriBarang): RedirectResponse
    {
        $this->service->update($kategoriBarang, $request->validated());

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(KategoriBarang $kategoriBarang): RedirectResponse
    {
        $this->service->delete($kategoriBarang);

        return redirect()->route('kategori-barang.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}