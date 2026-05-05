<?php

namespace App\Http\Controllers;

use App\Models\KedisiplinanPoin;
use App\Services\KedisiplinanPoinService;
use App\Http\Requests\StoreKedisiplinanPoinRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class KedisiplinanPoinController extends Controller
{
    public function __construct(
        private readonly KedisiplinanPoinService $poinService
    ) {}

    public function index(): View
    {
        $kedisiplinan_poin = $this->poinService->getAll();
        return view('data_master.kedisiplinan_poin.index', compact('kedisiplinan_poin'));
    }

    public function create(): View
    {
        return view('data_master.kedisiplinan_poin.create');
    }

    public function store(StoreKedisiplinanPoinRequest $request): RedirectResponse
    {
        try {
            $this->poinService->createRule($request->validated());
            return redirect()->route('kedisiplinan-poin.index')
                            ->with('success', 'Aturan kedisiplinan berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    public function edit(KedisiplinanPoin $kedisiplinanPoin): View
    {
        return view('data_master.kedisiplinan_poin.edit', compact('kedisiplinanPoin'));
    }

    public function update(StoreKedisiplinanPoinRequest $request, KedisiplinanPoin $kedisiplinanPoin): RedirectResponse
    {
        try {
            $this->poinService->updateRule($kedisiplinanPoin, $request->validated());
            return redirect()->route('kedisiplinan-poin.index')
                            ->with('success', 'Aturan kedisiplinan berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    public function destroy(KedisiplinanPoin $kedisiplinanPoin): RedirectResponse
    {
        try {
            $this->poinService->deleteRule($kedisiplinanPoin);
            return redirect()->route('kedisiplinan-poin.index')
                            ->with('success', 'Aturan kedisiplinan berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()
                            ->with('error', $e->getMessage());
        }
    }
}