<?php

namespace App\Http\Controllers;

use App\Models\AdministrasiGuru;
use App\Models\KategoriAdministrasi;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class AdministrasiGuruController extends Controller
{
    public function index(){
        if (user()?->hasRole('guru_sd')) {
            $kategori = KategoriAdministrasi::whereJenis('guru')->with(['administrasi_guru' => function($q){
                $q->where('guru_nipy', Auth::user()->email);
            }])->get();
            return view('administrasi.guru.index', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create(){
        if (user()?->hasRole('guru_sd')) {
            $kategori = KategoriAdministrasi::whereJenis('guru')->get();
            return view('administrasi.guru.create', compact('kategori'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        $kategori_adm = KategoriAdministrasi::find($request->judul);
        $nama_tahun_ajaran = str_replace('/', '_', $tahun_ajaran->nama_tahun_ajaran);
        $basePath = $nama_tahun_ajaran . '/Per Guru/' . Auth::user()->email . '_' . Auth::user()->name . '/' . $kategori_adm->nama_kategori;
        if ($request->filled('semester')) {
            $basePath .= '/Semester ' . $request->semester;
        }
        if ($request->hasFile('link')) {
            foreach ($request->file('link') as $file) {
                $filename = $basePath . '/' . $file->getClientOriginalName();
                Gdrive::put($filename, $file);
                AdministrasiGuru::create([
                    'tahun_ajaran_id'         => $tahun_ajaran->id,
                    'guru_nipy'               => Auth::user()->email,
                    'kategori_administrasi_id'=> $request->judul,
                    'keterangan'              => $file->getClientOriginalName(),
                    'link'                    => $filename,
                ]);
            }
        }
        return redirect()
            ->route('administrasi-guru.index')
            ->with('success', 'Administrasi berhasil diupload');
    }

    public function show($id)
    {
        $administrasi = AdministrasiGuru::findOrFail($id);
        $data = Gdrive::get($administrasi->link);
        return response($data->file, 200)
            ->header('Content-Type', $data->ext)
            ->header('Content-disposition', 'attachment; filename="'.$data->filename.'"');
    }

    public function destroy($id)
    {
        if (user()?->hasRole('guru_sd')) {
            $administrasi = AdministrasiGuru::findOrFail($id);
            Gdrive::delete($administrasi->link);
            $administrasi->delete();
            return redirect()->route('administrasi-guru.index')->with('success', 'Administrasi berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}
