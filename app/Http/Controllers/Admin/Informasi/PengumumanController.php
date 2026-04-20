<?php

namespace App\Http\Controllers\Admin\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Http\Requests\PengumumanRequest;
use App\Services\PengumumanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function __construct(
        protected PengumumanService $service
    ) {}

    public function index(): View
    {
        return view('informasi.pengumuman.index', [
            'pengumuman' => $this->service->getActiveAnnouncements()
        ]);
    }

    public function create(): View
    {
        return view('informasi.pengumuman.create');
    }

    public function store(PengumumanRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil disimpan');
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('informasi.pengumuman.edit', compact('pengumuman'));
    }

    public function update(PengumumanRequest $request, Pengumuman $pengumuman): RedirectResponse
    {
        $this->service->update($pengumuman, $request->validated());

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diupdate');
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $this->service->delete($pengumuman);

        return redirect()->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}