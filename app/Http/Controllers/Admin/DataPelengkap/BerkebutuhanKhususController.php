<?php

namespace App\Http\Controllers\Admin\DataPelengkap;

use App\Http\Controllers\Controller;
use App\Models\BerkebutuhanKhusus;
use App\Http\Requests\BerkebutuhanKhususRequest;
use App\Services\BerkebutuhanKhususService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BerkebutuhanKhususController extends Controller
{
    public function __construct(
        protected BerkebutuhanKhususService $service
    ) {}

    public function index(): View
    {
        return view('pelengkap.berkebutuhan_khusus.index', [
            'kategori' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('pelengkap.berkebutuhan_khusus.create');
    }

    public function store(BerkebutuhanKhususRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('kategori-kebutuhan.index')
            ->with('success', 'Berkebutuhan khusus berhasil disimpan');
    }

    public function edit(BerkebutuhanKhusus $kategoriKebutuhan): View
    {
        return view('pelengkap.berkebutuhan_khusus.edit', [
            'kategori' => $kategoriKebutuhan
        ]);
    }

    public function update(BerkebutuhanKhususRequest $request, BerkebutuhanKhusus $kategoriKebutuhan): RedirectResponse
    {
        $this->service->update($kategoriKebutuhan, $request->validated());

        return redirect()->route('kategori-kebutuhan.index')
            ->with('success', 'Berkebutuhan khusus berhasil diupdate');
    }

    public function destroy(BerkebutuhanKhusus $kategoriKebutuhan): RedirectResponse
    {
        $this->service->delete($kategoriKebutuhan);

        return redirect()->route('kategori-kebutuhan.index')
            ->with('success', 'Berkebutuhan khusus berhasil dihapus');
    }
}