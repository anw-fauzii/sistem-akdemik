<?php

namespace App\Http\Controllers;

use App\Models\AdministrasiKelas;
use App\Models\KategoriAdministrasi;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class AdministrasiKelasController extends Controller
{
    public function index(){
        if (user()?->hasRole('guru_sd')) {
            $tahun_ajaran = TahunAjaran::latest()->first();
            $kelas = Kelas::where(function($q){
                $q->where('guru_nipy', Auth::user()->email)
                ->orWhere('pendamping_nipy', Auth::user()->email);
            })->whereTahunAjaranId($tahun_ajaran->id)->first();
            $kategori = KategoriAdministrasi::whereJenis('kelas')->with(['administrasi_kelas' => function($q) use($kelas)   {
                $q->where('kelas_id', $kelas->id);
            }])->get();
            return view('administrasi.kelas.index', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create(){
        if (user()?->hasRole('guru_sd')) {
            $kategori = KategoriAdministrasi::whereJenis('kelas')->get();
            return view('administrasi.kelas.create', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        $kelas = Kelas::where(function($q){
                $q->where('guru_nipy', Auth::user()->email)
                ->orWhere('pendamping_nipy', Auth::user()->email);
            })->whereTahunAjaranId($tahun_ajaran->id)->first();
        $kategori_adm = KategoriAdministrasi::find($request->judul);
        $nama_tahun_ajaran = str_replace('/', '_', $tahun_ajaran->nama_tahun_ajaran);
        $basePath = $nama_tahun_ajaran . '/Kelas/' . $kelas->nama_kelas . '/' . $kategori_adm->nama_kategori;
        if ($request->filled('semester')) {
            $basePath .= '/Semester ' . $request->semester;
        }
        if ($request->hasFile('link')) {
            foreach ($request->file('link') as $file) {
                $filename = $basePath . '/' . $file->getClientOriginalName();
                Gdrive::put($filename, $file);
                AdministrasiKelas::create([
                    'tahun_ajaran_id'         => $tahun_ajaran->id,
                    'kelas_id'                => $kelas->id,
                    'kategori_administrasi_id'=> $request->judul,
                    'keterangan'              => $file->getClientOriginalName(),
                    'link'                    => $filename,
                ]);
            }
        }
        return redirect()
            ->route('administrasi-kelas.index')
            ->with('success', 'Administrasi berhasil diupload');
    }

    public function show($id)
    {
        $administrasi = AdministrasiKelas::findOrFail($id);
        $data = Gdrive::get($administrasi->link);
        return response($data->file, 200)
            ->header('Content-Type', $data->ext)
            ->header('Content-disposition', 'attachment; filename="'.$data->filename.'"');
    }

    public function destroy($id)
    {
        if (user()?->hasRole('guru_sd')) {
            $administrasi = AdministrasiKelas::findOrFail($id);
            Gdrive::delete($administrasi->link);
            $administrasi->delete();
            return redirect()->route('administrasi-kelas.index')->with('success', 'Administrasi berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
