<?php
namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\KategoriMataPelajaran;
use App\Http\Requests\KategoriMataPelajaranRequest;
use App\Services\KategoriMataPelajaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KategoriMataPelajaranController extends Controller
{
    public function __construct(
        protected KategoriMataPelajaranService $service
    ) {}

    public function index(): View
    {
        return view('mapel.kategori.index', [
            'kategori' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('mapel.kategori.create');
    }

    public function store(KategoriMataPelajaranRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());
        return redirect()->route('kategori-mata-pelajaran.index')->with('success', 'Kategori berhasil disimpan');
    }

    public function edit(KategoriMataPelajaran $kategoriMataPelajaran): View
    {
        return view('mapel.kategori.edit', ['kategori' => $kategoriMataPelajaran]);
    }

    public function update(KategoriMataPelajaranRequest $request, KategoriMataPelajaran $kategoriMataPelajaran): RedirectResponse
    {
        $this->service->update($kategoriMataPelajaran, $request->validated());
        return redirect()->route('kategori-mata-pelajaran.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(KategoriMataPelajaran $kategoriMataPelajaran): RedirectResponse
    {
        $this->service->delete($kategoriMataPelajaran);
        return redirect()->route('kategori-mata-pelajaran.index')->with('success', 'Kategori berhasil dihapus');
    }
}