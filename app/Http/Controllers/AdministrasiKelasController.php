<?php

namespace App\Http\Controllers;

use App\Models\AdministrasiKelas;
use App\Models\KategoriAdministrasi;
use App\Models\TahunAjaran;
use App\Http\Requests\StoreAdministrasiKelasRequest;
use App\Services\AdministrasiKelasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class AdministrasiKelasController extends Controller
{
    public function __construct(
        protected AdministrasiKelasService $service
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = $this->service->getKelasAktif(Auth::user()->email, $tahunAjaran->id);

        // Proteksi: Jika guru tidak punya kelas, cegah error Fatal!
        if (!$kelas) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai wali kelas atau pendamping pada tahun ajaran ini.');
        }

        $kategori = KategoriAdministrasi::where('jenis', 'kelas')
            ->with(['administrasiKelas' => fn($q) => $q->where('kelas_id', $kelas->id)])
            ->get();

        return view('administrasi.kelas.index', compact('kategori', 'kelas'));
    }

    public function create(): View|RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = $this->service->getKelasAktif(Auth::user()->email, $tahunAjaran->id);

        if (!$kelas) {
            return redirect()->route('administrasi-kelas.index')->with('error', 'Akses ditolak. Anda tidak memiliki kelas.');
        }

        $kategori = KategoriAdministrasi::where('jenis', 'kelas')->get();
        return view('administrasi.kelas.create', compact('kategori'));
    }

    public function store(StoreAdministrasiKelasRequest $request): RedirectResponse
    {
        try {
            $tahunAjaran = TahunAjaran::latest()->firstOrFail();
            $kelas = $this->service->getKelasAktif(Auth::user()->email, $tahunAjaran->id);
            
            abort_if(!$kelas, 403, 'Anda tidak memiliki kelas.');

            $this->service->uploadFiles($request->validated(), $request->file('files'), $kelas, $tahunAjaran);
            
            return redirect()->route('administrasi-kelas.index')->with('success', 'Administrasi Kelas berhasil diunggah');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Upload GDrive Kelas Gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunggah file.')->withInput();
        }
    }

    public function show(AdministrasiKelas $administrasiKelas): Response
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = $this->service->getKelasAktif(Auth::user()->email, $tahunAjaran->id);
        abort_if(!$kelas || $administrasiKelas->kelas_id !== $kelas->id, 403, 'Akses ditolak.');

        $data = Gdrive::get($administrasiKelas->link);

        return response($data->file, 200)
            ->header('Content-Type', $data->ext)
            ->header('Content-disposition', 'attachment; filename="' . $data->filename . '"');
    }

    public function destroy(AdministrasiKelas $administrasiKelas): RedirectResponse
    {
        $tahunAjaran = TahunAjaran::latest()->firstOrFail();
        $kelas = $this->service->getKelasAktif(Auth::user()->email, $tahunAjaran->id);

        // PROTEKSI: Hanya wali kelas yang bersangkutan yang bisa menghapus!
        abort_if(!$kelas || $administrasiKelas->kelas_id !== $kelas->id, 403, 'Akses ditolak.');

        try {
            $this->service->deleteFile($administrasiKelas);
            return redirect()->route('administrasi-kelas.index')->with('success', 'Administrasi Kelas berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus file.');
        }
    }
}