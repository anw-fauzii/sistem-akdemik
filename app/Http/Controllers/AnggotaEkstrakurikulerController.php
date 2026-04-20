<?php

namespace App\Http\Controllers;

use App\Models\AnggotaEkstrakurikuler;
use App\Http\Requests\StoreAnggotaEkskulRequest;
use App\Services\AnggotaEkstrakurikulerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AnggotaEkstrakurikulerController extends Controller
{
    public function __construct(
        protected AnggotaEkstrakurikulerService $service
    ) {
        // Sentralisasi Keamanan: Blokir akses selain admin di level konstruktor
        $this->middleware(['auth', 'role:admin']);
    }

    public function store(StoreAnggotaEkskulRequest $request): RedirectResponse
    {
        try {
            $this->service->assignBulk(
                $request->validated('anggota_kelas_ids'),
                $request->validated('ekstrakurikuler_id')
            );

            return back()->with('success', 'Anggota ekstrakurikuler berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Gagal tambah anggota ekskul: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function destroy(AnggotaEkstrakurikuler $anggotaEkstrakurikuler): RedirectResponse
    {
        try {
            // Method di Service akan menangani penghapusan dan update tabel Siswa
            $this->service->remove($anggotaEkstrakurikuler);
            
            return back()->with('success', 'Anggota ekstrakurikuler berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Gagal hapus anggota ekskul: ' . $e->getMessage());
            return back()->with('error', 'Anggota ekstrakurikuler tidak dapat dihapus');
        }
    }
}