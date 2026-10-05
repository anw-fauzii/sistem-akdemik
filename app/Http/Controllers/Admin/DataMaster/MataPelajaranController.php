<?php
namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Http\Requests\MataPelajaranRequest;
use App\Models\TahunAjaran;
use App\Services\KategoriMataPelajaranService;
use App\Services\MataPelajaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    public function __construct(
        protected KategoriMataPelajaranService $kategoriMataPelajaranservice,
        protected MataPelajaranService $mataPelajaranService
    ) {}

    public function index(): View
    {
        return view('mapel.daftar.index', [
            'mataPelajaran' => $this->mataPelajaranService->getAll()
        ]);
    }

    public function create(): View
    {
        return view('mapel.daftar.create', [
            'kategori' => $this->kategoriMataPelajaranservice->getAll(),
            'tahunAjaran' => TahunAjaran::latest()->first(),
        ]);
    }

    public function store(MataPelajaranRequest $request): RedirectResponse
    {
        $this->mataPelajaranService->store($request->validated());
        return redirect()->route('daftar-mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil disimpan');
    }

    public function edit(MataPelajaran $mataPelajaran): View
    {
        return view('mapel.daftar.edit', ['mataPelajaran' => $mataPelajaran,
            'kategori' => $this->kategoriMataPelajaranservice->getAll(),
            'tahunAjaran' => TahunAjaran::latest()->first(),
        ]);
    }

    public function update(MataPelajaranRequest $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $this->mataPelajaranService->update($mataPelajaran, $request->validated());
        return redirect()->route('daftar-mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil diupdate');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        $this->mataPelajaranService->delete($mataPelajaran);
        return redirect()->route('daftar-mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus');
    }

    public function importPrevious(MataPelajaranService $service)
    {
        $result = $service->duplicateFromPreviousSemester();

        return redirect()->route('daftar-mata-pelajaran.index')
                ->with($result['status'], $result['message']);
    }
}