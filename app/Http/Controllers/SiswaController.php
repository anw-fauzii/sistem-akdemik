<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\BerkebutuhanKhusus;
use App\Models\JenjangPendidikan;
use App\Models\Kelas;
use App\Models\Pekerjaan;
use App\Models\Penghasilan;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index()
    {
        if (user()?->hasRole('admin')) {
            $siswa = Siswa::all();
            return view('data_master.siswa.index', compact('siswa'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function create()
    {
        if (user()?->hasRole('admin')) {
            $pekerjaan = Pekerjaan::all();
            $penghasilan = Penghasilan::all();
            $transportasi = Transportasi::all();
            $berkebutuhan_khusus = BerkebutuhanKhusus::all();
            $tahun_ajaran = TahunAjaran::latest()->first();
            $pendidikan = JenjangPendidikan::all();
            $kelas  = Kelas::whereTahunAjaranId($tahun_ajaran->id)->get();
            return view('data_master.siswa.create', compact('kelas','berkebutuhan_khusus','transportasi','penghasilan','pekerjaan','pendidikan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function store(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nis' => 'required|unique:siswa,nis', 
                'kelas_id' => 'required',
                'jenis_pendaftaran' => 'required',
                'nama_lengkap' => 'required',
                'jenis_kelamin' => 'required',
                'nisn' => 'nullable|unique:siswa,nisn', 
                'nik' => 'nullable|unique:siswa,nik', 
                'no_kk' => 'unique:siswa,no_kk', 
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'akta_lahir' => 'nullable|unique:siswa,akta_lahir', 
                'kewarganegaraan' => 'required',
                'nama_negara' => 'required',
                'berkebutuhan_khusus_id' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'desa' => 'required',
                'kecamatan' => 'required',
                'kabupaten' => 'required',
                'provinsi' => 'required',
                'kode_pos' => 'required',
                'tempat_tinggal' => 'required',
                'transportasi_id' => 'required',
                'anak_ke' => 'required',
                'jumlah_saudara' => 'required',
                
                'nik_ayah' => 'required', 
                'nama_ayah' => 'required', 
                'lahir_ayah' => 'required',
                'jenjang_pendidikan_ayah_id' => 'required', 
                'pekerjaan_ayah_id' => 'required',
                'penghasilan_ayah_id' => 'required',
                'berkebutuhan_khusus_ayah_id' => 'required',
        
                'nik_ibu' => 'required', 
                'nama_ibu' => 'required', 
                'lahir_ibu' => 'required',
                'jenjang_pendidikan_ibu_id' => 'required',
                'pekerjaan_ibu_id' => 'required',
                'penghasilan_ibu_id' => 'required',
                'berkebutuhan_khusus_ibu_id' => 'required',
        
                'nomor_hp' => 'required',
                'whatsapp' => 'required',
                'email' => 'required',
        
                'tinggi_badan' => 'required',
                'berat_badan' => 'required',
                'jarak' => 'required',
                'waktu_tempuh' => 'required',
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'gelar.required' => 'Gelar wajib diisi.',
                'jabatan.required' => 'Jabatan wajib diisi.',
                'nipy.required' => 'NIPY wajib diisi.',
                'nipy.unique' => 'NIPY sudah digunakan.',
                'telepon.required' => 'Telepon wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
                'nuptk.required' => 'NUPTK sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
            ]);

            DB::beginTransaction(); // Memulai transaksi

            try {
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['nis'],
                    'password' => Hash::make('pass1234'),
                ]);

                $siswa = new Siswa($validated); 
                $siswa->nis = $validated['nis'];
                $siswa->agama = 1;
                $siswa->save();
                $user->assignRole('siswa');
        
                DB::commit(); 
        
                return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
            } catch (\Exception $e) {
                DB::rollBack(); 
        
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if (user()?->hasRole('admin')) {
            $pekerjaan = Pekerjaan::all();
            $penghasilan = Penghasilan::all();
            $transportasi = Transportasi::all();
            $berkebutuhan_khusus = BerkebutuhanKhusus::all();
            $tahun_ajaran = TahunAjaran::latest()->first();
            $pendidikan = JenjangPendidikan::all();
            $kelas  = Kelas::whereTahunAjaranId($tahun_ajaran->id)->get();
            $siswa = Siswa::findOrFail($id);
            return view('data_master.siswa.edit', compact('siswa','kelas','berkebutuhan_khusus','transportasi','penghasilan','pekerjaan','pendidikan'));
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if (user()?->hasRole('admin')) {
            $validated = $request->validate([
                'nis' => 'required|unique:siswa,nis,' . $id . ',nis', 
                'kelas_id' => 'required',
                'jenis_pendaftaran' => 'required',
                'nama_lengkap' => 'required',
                'jenis_kelamin' => 'required',
                'nisn' => 'nullable|unique:siswa,nisn,' . $id . ',nis',
                'nik' => 'nullable|unique:siswa,nik,' . $id . ',nis',
                'no_kk' => 'unique:siswa,no_kk,' . $id . ',nis',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'akta_lahir' => 'nullable|unique:siswa,akta_lahir,' . $id . ',nis',
                'kewarganegaraan' => 'required',
                'nama_negara' => 'required',
                'berkebutuhan_khusus_id' => 'required',
                'alamat' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'desa' => 'required',
                'kecamatan' => 'required',
                'kabupaten' => 'required',
                'provinsi' => 'required',
                'kode_pos' => 'required',
                'tempat_tinggal' => 'required',
                'transportasi_id' => 'required',
                'anak_ke' => 'required',
                'jumlah_saudara' => 'required',
                
                'nik_ayah' => 'required', 
                'nama_ayah' => 'required', 
                'lahir_ayah' => 'required',
                'jenjang_pendidikan_ayah_id' => 'required', 
                'pekerjaan_ayah_id' => 'required',
                'penghasilan_ayah_id' => 'required',
                'berkebutuhan_khusus_ayah_id' => 'required',
        
                'nik_ibu' => 'required',
                'nama_ibu' => 'required', 
                'lahir_ibu' => 'required',
                'jenjang_pendidikan_ibu_id' => 'required',
                'pekerjaan_ibu_id' => 'required',
                'penghasilan_ibu_id' => 'required',
                'berkebutuhan_khusus_ibu_id' => 'required',
        
                'nomor_hp' => 'required',
                'whatsapp' => 'required',
                'email' => 'required',
        
                'tinggi_badan' => 'required',
                'berat_badan' => 'required',
                'jarak' => 'required',
                'waktu_tempuh' => 'required',
            ], [
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'gelar.required' => 'Gelar wajib diisi.',
                'jabatan.required' => 'Jabatan wajib diisi.',
                'nipy.required' => 'NIPY wajib diisi.',
                'nipy.unique' => 'NIPY sudah digunakan.',
                'telepon.required' => 'Telepon wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
                'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
                'nuptk.required' => 'NUPTK sudah digunakan.',
                'alamat.required' => 'Alamat wajib diisi.',
            ]);

            DB::beginTransaction();

            try {
                $user = User::where('email', $id)->first();
                if (!$user) {
                    throw new \Exception("User dengan nis $id tidak ditemukan.");
                }
        
                $user->update([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['nis'],
                ]);
        
                $siswa = Siswa::where('nis', $user->email)->first();
                if (!$siswa) {
                    throw new \Exception("Siswa dengan NIS $id tidak ditemukan.");
                }
        
                $siswa->update($validated);
        
                DB::commit();
        
                return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diupdate.');
        
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function destroy($id)
    {
        if (user()?->hasRole('admin')) {
            $siswa = Siswa::findOrFail($id);
            if ($siswa->user) { 
                $siswa->user->delete();
            }
            $siswa->delete();
            return redirect()->route('siswa.index')->with('success', 'Siswa dan akun User terkait berhasil dihapus');
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function import(Request $request)
    {
        if (user()?->hasRole('admin')) {
            $request->validate([
                'file_import' => 'required|mimes:xlsx,csv,xls',
            ]);

            try {
                Excel::import(new SiswaImport, $request->file('file_import'));
                return back()->with('success', 'Peserta didik berhasil diimport');
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi error: ' . $e->getMessage());
            }
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }

    public function format()
    {
        if (user()?->hasRole('admin')) {
            $file = public_path() . "/format_excel/format_import_siswa.xlsx";
            $headers = array(
                'Content-Type: application/xlsx',
            );
            return Response::download($file, 'format_import_siswa ' . date('Y-m-d H_i_s') . '.xlsx', $headers);
        } else {
            return response()->view('errors.403', [abort(403)], 403);
        }
    }
}