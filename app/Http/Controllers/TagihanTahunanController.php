<?php

namespace App\Http\Controllers;

use App\Models\TagihanTahunan;
use App\Http\Requests\TagihanTahunanRequest;
use App\Services\TagihanTahunanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagihanTahunanController extends Controller
{
    public function __construct(
        protected TagihanTahunanService $service
    ) {}

    public function index(): View
    {
        return view('data_master.tagihan_tahunan.index', [
            'tagihan_tahunan' => $this->service->getAllActive()
        ]);
    }

    public function create(): View
    {
        return view('data_master.tagihan_tahunan.create');
    }

    public function store(TagihanTahunanRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('tagihan-tahunan.index')
            ->with('success', 'Biaya tahunan berhasil disimpan.');
    }

    public function edit(TagihanTahunan $tagihanTahunan): View
    {
        return view('data_master.tagihan_tahunan.edit', compact('tagihanTahunan'));
    }

    public function update(TagihanTahunanRequest $request, TagihanTahunan $tagihanTahunan): RedirectResponse
    {
        $this->service->update($tagihanTahunan, $request->validated());

        return redirect()->route('tagihan-tahunan.index')
            ->with('success', 'Biaya tahunan berhasil diupdate.');
    }

    public function destroy(TagihanTahunan $tagihanTahunan): RedirectResponse
    {
        try {
            $this->service->delete($tagihanTahunan);
            return redirect()->route('tagihan-tahunan.index')->with('success', 'Biaya tahunan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}