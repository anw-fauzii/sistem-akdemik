<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrasiGuru;
use App\Services\AdministrasiGuruService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class AdminAdministrasiController extends Controller
{
    public function __construct(
        protected AdministrasiGuruService $service
    ) {}

    public function index(): View
    {
        // Panggil Service untuk mengambil data yang sudah dikelompokkan per Guru
        $dataGuru = $this->service->getAdministrasiGroupedByGuru();

        return view('admin.administrasi.index', compact('dataGuru'));
    }

    public function verify(AdministrasiGuru $administrasiGuru, Request $request) // Tambahkan Request
    {
        try {
            $this->service->verifyAdministrasi($administrasiGuru);

            // Jika request datang dari AJAX (JavaScript Fetch)
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil diverifikasi!'
                ]);
            }

            // Fallback jika fitur JS mati (kembali ke cara lama)
            return back()->with('success', 'Status administrasi berhasil diverifikasi!');
            
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi dokumen.'
                ], 500);
            }
            return back()->with('error', 'Gagal memverifikasi dokumen.');
        }
    }

    public function download(AdministrasiGuru $administrasiGuru): Response
    {
        // Admin bebas mendownload tanpa abort_if pengecekan email guru
        $data = Gdrive::get($administrasiGuru->link);

        return response($data->file, 200)
            ->header('Content-Type', $data->ext)
            ->header('Content-disposition', 'attachment; filename="' . $data->filename . '"');
    }
}