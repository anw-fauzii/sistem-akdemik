<?php

namespace App\Http\Controllers;

use App\Models\AnggotaJemputan;
use App\Http\Requests\StoreAnggotaJemputanRequest;
use App\Services\AnggotaJemputanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AnggotaJemputanController extends Controller
{
    public function __construct(
        protected AnggotaJemputanService $service
    ) {}

    public function store(StoreAnggotaJemputanRequest $request): RedirectResponse
    {
        try {
            $this->service->assignBulk(
                $request->validated('anggota_kelas_ids'),
                $request->validated('jemputan_id'),
                $request->validated('keterangan')
            );

            return back()->with('success', 'Anggota jemputan berhasil ditambahkan');
            
        } catch (\Exception $e) {
            Log::error('Gagal tambah jemputan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function destroy(AnggotaJemputan $anggotaJemputan): RedirectResponse
    {
        try {
            $this->service->remove($anggotaJemputan);
            return back()->with('success', 'Anggota jemputan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Gagal hapus jemputan: ' . $e->getMessage());
            return back()->with('error', 'Anggota jemputan tidak dapat dihapus');
        }
    }
}