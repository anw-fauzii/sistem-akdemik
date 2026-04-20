<?php

namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\KategoriAdministrasi;
use App\Http\Requests\KategoriAdministrasiRequest;
use App\Services\KategoriAdministrasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriAdministrasiController extends Controller
{
    public function __construct(
        protected KategoriAdministrasiService $service
    ) {}

    public function index(): View
    {
        return view('data_master.kategori_administrasi.index', [
            'kategori' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('data_master.kategori_administrasi.create');
    }

    public function store(KategoriAdministrasiRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('kategori-administrasi.index')
            ->with('success', 'Kategori berhasil disimpan');
    }

    public function edit(KategoriAdministrasi $kategoriAdministrasi): View
    {
        return view('data_master.kategori_administrasi.edit', [
            'kategori' => $kategoriAdministrasi
        ]);
    }

    public function update(KategoriAdministrasiRequest $request, KategoriAdministrasi $kategoriAdministrasi): RedirectResponse
    {
        $this->service->update($kategoriAdministrasi, $request->validated());

        return redirect()->route('kategori-administrasi.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(KategoriAdministrasi $kategoriAdministrasi): RedirectResponse
    {
        $this->service->delete($kategoriAdministrasi);

        return redirect()->route('kategori-administrasi.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}