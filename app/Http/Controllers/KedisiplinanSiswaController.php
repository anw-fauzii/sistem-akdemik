<?php

namespace App\Http\Controllers;

use App\Models\KedisiplinanSiswa;
use App\Services\KedisiplinanSiswaService;
use App\Http\Requests\StoreKedisiplinanSiswaRequest;
use App\Http\Requests\UpdateKedisiplinanSiswaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class KedisiplinanSiswaController extends Controller
{
    public function __construct(
        private readonly KedisiplinanSiswaService $kedisiplinanService
    ) {}

    /**
     * Menampilkan daftar riwayat poin kedisiplinan siswa.
     */
    public function index(): View
    {
        $riwayatSiswa = $this->kedisiplinanService->getAll();
        
        return view('kedisiplinan_siswa.index', compact('riwayatSiswa'));
    }

    /**
     * Menampilkan form untuk mencatat pelanggaran/prestasi baru.
     */
    public function create(): View
    {
        $aturanList = $this->kedisiplinanService->getAturanList();
        $siswaList = $this->kedisiplinanService->getSiswaList();

        return view('kedisiplinan_siswa.create', compact('aturanList', 'siswaList'));
    }

    public function store(StoreKedisiplinanSiswaRequest $request): RedirectResponse
    {
        try {
            $this->kedisiplinanService->recordPoint($request->validated());

            return redirect()->route('kedisiplinan-siswa.index')
                            ->with('success', 'Data kedisiplinan siswa berhasil dicatat.');
                            
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit untuk riwayat yang sudah ada.
     */
    public function edit(KedisiplinanSiswa $kedisiplinanSiswa): View
    {
        $aturanList = $this->kedisiplinanService->getAturanList();
        $siswaList = $this->kedisiplinanService->getSiswaList();

        return view('kedisiplinan_siswa.edit', compact('kedisiplinanSiswa', 'aturanList', 'siswaList'));
    }

    /**
     * Menyimpan perubahan pada riwayat kedisiplinan.
     */
    public function update(StoreKedisiplinanSiswaRequest $request, KedisiplinanSiswa $kedisiplinanSiswa): RedirectResponse
    {
        try {
            $this->kedisiplinanService->updateRecord($kedisiplinanSiswa, $request->validated());

            return redirect()->route('kedisiplinan-siswa.index')
                            ->with('success', 'Data kedisiplinan siswa berhasil diperbarui.');
                            
        } catch (Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
        }
    }

    /**
     * Menghapus riwayat kedisiplinan (Hard Delete).
     */
    public function destroy(KedisiplinanSiswa $kedisiplinanSiswa): RedirectResponse
    {
        try {
            $this->kedisiplinanService->deleteRecord($kedisiplinanSiswa);

            return redirect()->route('kedisiplinan-siswa.index')
                            ->with('success', 'Data kedisiplinan siswa berhasil dihapus.');
                            
        } catch (Exception $e) {
            return redirect()->back()
                            ->with('error', $e->getMessage());
        }
    }
}