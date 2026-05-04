<?php
namespace App\Http\Controllers;

use App\Http\Requests\SuratIzinRequest;
use App\Models\SuratIzin;
use App\Services\SuratIzinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SuratIzinController extends Controller
{
    public function __construct(
        protected SuratIzinService $service
    ) {}

    public function index(): View
    {
        $user = Auth::user();

        if (user()?->hasRole('siswa_sd')) {
            return view('surat_izin.siswa_index', [
                'suratIzin' => $this->service->getListForSiswa($user->email)
            ]);
        }

        if (user()?->hasAnyRole(['guru_sd', 'guru_tk'])) {
            return view('surat_izin.admin_index', [
                'suratIzin' => $this->service->getListForGuru($user->email)
            ]);
        }

        return view('surat_izin.admin_index', [
            'suratIzin' => $this->service->getAllList()
        ]);
    }

    public function create(): View
    {
        return view('surat_izin.create');
    }

    public function store(SuratIzinRequest $request): RedirectResponse
    {
        $anggota = $this->service->getActiveStudentMember(auth()->user()->email);

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan di tahun ajaran aktif');
        }

        $this->service->store(
            array_merge($request->validated(), ['anggota_kelas_id' => $anggota->id]),
            $request->file('file')
        );

        return redirect()->route('surat-izin.index')->with('success', 'Surat izin berhasil dikirim');
    }

    public function show(SuratIzin $suratIzin): View
    {
        // Eager load relasi untuk detail view
        $suratIzin->load(['anggotaKelas.siswa', 'anggotaKelas.kelas']);
        
        return view('surat_izin.show', compact('suratIzin'));
    }

    public function edit(SuratIzin $suratIzin): View
    {
        return view('surat_izin.edit', [
            'surat' => $suratIzin
        ]);
    }

    public function update(SuratIzinRequest $request, SuratIzin $suratIzin): RedirectResponse
    {
        $this->authorize('update', $suratIzin);

        $this->service->update($suratIzin, $request->validated(), $request->file('file'));

        return redirect()->route('surat-izin.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(SuratIzin $suratIzin): RedirectResponse
    {
        $this->authorize('delete', $suratIzin);

        $this->service->delete($suratIzin);

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}