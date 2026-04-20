<?php

namespace App\Http\Controllers\Admin\DataPelengkap;

use App\Http\Controllers\Controller;
use App\Models\JenjangPendidikan;
use App\Http\Requests\JenjangPendidikanRequest;
use App\Services\JenjangPendidikanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JenjangPendidikanController extends Controller
{
    public function __construct(
        protected JenjangPendidikanService $service
    ) {}

    public function index(): View
    {
        return view('pelengkap.pendidikan.index', [
            'pendidikan' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('pelengkap.pendidikan.create');
    }

    public function store(JenjangPendidikanRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('jenjang-pendidikan.index')
            ->with('success', 'Jenjang pendidikan berhasil disimpan');
    }

    public function edit(JenjangPendidikan $jenjangPendidikan): View
    {
        return view('pelengkap.pendidikan.edit', [
            'pendidikan' => $jenjangPendidikan
        ]);
    }

    public function update(JenjangPendidikanRequest $request, JenjangPendidikan $jenjangPendidikan): RedirectResponse
    {
        $this->service->update($jenjangPendidikan, $request->validated());

        return redirect()->route('jenjang-pendidikan.index')
            ->with('success', 'Jenjang pendidikan berhasil diupdate');
    }

    public function destroy(JenjangPendidikan $jenjangPendidikan): RedirectResponse
    {
        $this->service->delete($jenjangPendidikan);

        return redirect()->route('jenjang-pendidikan.index')
            ->with('success', 'Jenjang pendidikan berhasil dihapus');
    }
}