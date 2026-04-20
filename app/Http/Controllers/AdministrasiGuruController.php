<?php

namespace App\Http\Controllers;

use App\Models\AdministrasiGuru;
use App\Models\KategoriAdministrasi;
use App\Http\Requests\StoreAdministrasiGuruRequest;
use App\Services\AdministrasiGuruService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class AdministrasiGuruController extends Controller
{
    public function __construct(
        protected AdministrasiGuruService $service
    ) {
        // Sentralisasi Role: Kunci seluruh akses khusus untuk Guru SD
        $this->middleware(['auth', 'role:guru_sd']);
    }

    public function index(): View
    {
        $kategori = KategoriAdministrasi::where('jenis', 'guru')
            ->with(['administrasiGuru' => fn($q) => $q->where('guru_nipy', Auth::user()->email)])
            ->get();

        return view('administrasi.guru.index', compact('kategori'));
    }

    public function create(): View
    {
        $kategori = KategoriAdministrasi::where('jenis', 'guru')->get();
        return view('administrasi.guru.create', compact('kategori'));
    }

    public function store(StoreAdministrasiGuruRequest $request): RedirectResponse
    {
        try {
            $this->service->uploadFiles($request->validated(), $request->file('files'));
            return redirect()->route('administrasi-guru.index')->with('success', 'Administrasi berhasil diupload');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Upload GDrive Gagal: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunggah file. Silakan coba lagi.')->withInput();
        }
    }

    public function show(AdministrasiGuru $administrasiGuru): Response
    {
        // PROTEKSI KEAMANAN: Pastikan yang mau diunduh adalah file miliknya sendiri!
        abort_if($administrasiGuru->guru_nipy !== Auth::user()->email, 403, 'Akses ditolak.');

        $data = Gdrive::get($administrasiGuru->link);

        return response($data->file, 200)
            ->header('Content-Type', $data->ext)
            ->header('Content-disposition', 'attachment; filename="' . $data->filename . '"');
    }

    public function destroy(AdministrasiGuru $administrasiGuru): RedirectResponse
    {
        abort_if($administrasiGuru->guru_nipy !== Auth::user()->email, 403, 'Akses ditolak.');

        try {
            $this->service->deleteFile($administrasiGuru);
            return redirect()->route('administrasi-guru.index')->with('success', 'Administrasi berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus file.');
        }
    }
}