<?php 

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Http\Requests\AnggotaKelasRequest;
use App\Services\AnggotaKelasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnggotaKelasController extends Controller
{
    public function __construct(
        protected AnggotaKelasService $service
    ) {}

    public function index(): View
    {
        $data = $this->service->getGuruClassData();
        return view('anggota_kelas.index', $data);
    }

    public function store(AnggotaKelasRequest $request): RedirectResponse
    {
        $this->service->addStudentsToClass($request->validated());

        return back()->with('success', 'Anggota kelas berhasil ditambahkan');
    }

    public function destroy(AnggotaKelas $anggotaKelas): RedirectResponse
    {
        try {
            $this->service->removeStudentFromClass($anggotaKelas);
            return back()->with('success', 'Anggota kelas berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus anggota kelas');
        }
    }
}