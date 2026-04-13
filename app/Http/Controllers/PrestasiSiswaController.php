<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKelas;
use App\Models\PrestasiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class PrestasiSiswaController extends Controller
{

    public function index()
    {
        if (user()?->hasRole('admin')) {
            $prestasi = PrestasiSiswa::with('anggotaKelas.siswa', 'anggotaKelas.kelas')
                ->latest()->get();

            return view('prestasi_siswa.admin_index', compact('prestasi'));
        } else {
            $tahunAjaran = TahunAjaran::latest()->first();

            $anggotaKelas = AnggotaKelas::whereSiswaNis(auth()->user()->email)
                ->whereHas('kelas', function ($q) use ($tahunAjaran) {
                    $q->where('tahun_ajaran_id', $tahunAjaran->id);
                })
                ->first();

            if (!$anggotaKelas) {
                return back()->with('error', 'Data siswa tidak ditemukan');
            }

            $prestasi = PrestasiSiswa::whereHas('anggotaKelas', function ($q) use ($anggotaKelas) {
                $q->where('anggota_kelas_id', $anggotaKelas->id);
            })
                ->latest()
                ->get();

            return view('prestasi_siswa.siswa_index', compact('prestasi'));
        }
    }

    public function create()
    {
        $anggotaKelas = AnggotaKelas::with(['siswa', 'kelas'])
            ->tahunAjaranAktif()
            ->get();

        return view('prestasi_siswa.create', compact('anggotaKelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_kelas_id' => 'required|array',
            'anggota_kelas_id.*' => 'exists:anggota_kelas,id',
            'nama_prestasi' => 'required|string|max:255',
            'kategori' => 'required|in:akademik,non_akademik',
            'tingkat' => 'required|string|max:100',
            'peringkat' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'file_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->except('anggota_kelas_id');

        if ($request->hasFile('file_sertifikat')) {
            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('prestasiSiswa', 'public');
        }

        // simpan prestasi (master)
        $prestasi = PrestasiSiswa::create($data);

        // simpan ke pivot (multi siswa)
        $prestasi->anggotaKelas()->sync($request->anggota_kelas_id);

        return redirect()->route('prestasi-siswa.index')
            ->with('success', 'Data prestasi berhasil ditambahkan');
    }

    public function show($id)
    {
        // 1. Ambil Data
        $prestasi = PrestasiSiswa::with('anggotaKelas.siswa')->findOrFail($id);
        $anggota = $prestasi->anggotaKelas;
        $jumlahSiswa = $anggota->count();
        $manager = new ImageManager(new Driver());

        // 2. Olah Foto Siswa
        $foto = $manager->read(Storage::disk('public')->path($prestasi->file_sertifikat));
        $foto->resize(512, 512);

        // 3. Siapkan Kanvas & Template
        $template = $manager->read(public_path('frame.png'));
        $canvas = $manager->create(1000, 1000);

        // Susun Gambar (Foto di bawah, Frame di atas)
        $canvas->place($foto, 'center', 0, -100);
        $canvas->place($template, 'center');

        // 4. Pengaturan Teks NAMA
        if($prestasi->keterangan || $jumlahSiswa === 1){
            $nama = $prestasi->keterangan ?: $anggota->first()->siswa->nama_lengkap;
            $fontSizeNama = 22;
            $startY = 700;
        }else{
            $nama = $prestasi->anggotaKelas->map(fn($item) => $item->siswa->nama_lengkap)->implode(', ');
            $fontSizeNama = 17;
            $startY = 693; 
        }
        
        
        // Titik awal koordinat Y
        $lineHeightNama = 1.6;
        $maxWidth = 650;

        // Gambar NAMA
        $canvas->text($nama, 270, $startY, function ($font) use ($fontSizeNama, $lineHeightNama, $maxWidth) {
            $font->filename(public_path('fonts/Poppins-Bold.ttf'));
            $font->size($fontSizeNama);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top'); // Agar perhitungan Y dimulai dari atas teks
            $font->angle(-1.5);    // Mengikuti kemiringan frame
            $font->lineHeight($lineHeightNama);
            $font->wrap($maxWidth);
        });

        // 5. HITUNG DINAMIS UNTUK DESKRIPSI
        // Estimasi karakter per baris (Poppins Bold 19px di lebar 600px kira-kira 50-55 karakter)
        $estimasiKarakterPerBaris = 65; 
        $jumlahBarisNama = ceil(strlen($nama) / $estimasiKarakterPerBaris);
        
        /**
         * Logika Tinggi:
         * Jika 1 baris: hanya hitung tinggi font saja.
         * Jika > 1 baris: hitung jarak antar baris + tinggi font baris terakhir.
         */
        if ($jumlahBarisNama > 1) {
            $tinggiTotalNama = (($jumlahBarisNama - 1) * ($fontSizeNama * $lineHeightNama)) + ($fontSizeNama * 0.4);
        } else {
            $tinggiTotalNama = $fontSizeNama; 
        }

        $jarakAntarBlok = 8; 
        $currentYDeskripsi = $startY + $tinggiTotalNama + $jarakAntarBlok;

        $kelasList = $prestasi->anggotaKelas
            ->map(fn($item) => $item->kelas->tingkatan_kelas)
            ->unique()
            ->sort()
            ->values();

        if ($kelasList->count() == 1) {
            $kelasText = 'Kelas ' . $kelasList->first();
        } elseif ($kelasList->count() == 2) {
            $kelasText = 'Kelas ' . $kelasList->implode(' dan ');
        } else {
            $last = $kelasList->pop();
            $kelasText = 'Kelas ' . $kelasList->implode(', ') . ', dan ' . $last;
        }

        $deskripsi = "Siswa Kelas " . $kelasText . " SD Garut Islamic School Prima Insani yang meraih ". $prestasi->peringkat . " Pada ". $prestasi->nama_prestasi .".";

        $canvas->text($deskripsi, 270, $currentYDeskripsi, function ($font) {
            $font->filename(public_path('fonts/Poppins-Regular.ttf'));
            $font->size(14);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top');
            $font->angle(-1.5);
            $font->lineHeight(1.9);
            $font->wrap(610);
        });

        $tanggal = $prestasi->tanggal->translatedFormat('d F Y');
        $canvas->text($tanggal, 780, 810, function ($font) {
            $font->filename(public_path('fonts/Poppins-Bold.ttf'));
            $font->size(22);
            $font->color('#ffffff');
            $font->align('right');
        });

        // 8. Output Download
        return response()->streamDownload(function () use ($canvas) {
            echo $canvas->toPng();
        }, 'poster-prestasi-' . $id . '.png');
    }

    public function edit($id)
    {
        $prestasi = PrestasiSiswa::with('anggotaKelas')->findOrFail($id);

        $anggotaKelas = AnggotaKelas::with(['siswa', 'kelas'])
            ->tahunAjaranAktif()
            ->get();

        return view('prestasi_siswa.edit', compact('prestasi', 'anggotaKelas'));
    }

    public function update(Request $request, $id)
    {
        $prestasi = PrestasiSiswa::findOrFail($id);

        $request->validate([
            'anggota_kelas_id' => 'required|array',
            'anggota_kelas_id.*' => 'exists:anggota_kelas,id',
            'nama_prestasi' => 'required|string|max:255',
            'kategori' => 'required|in:akademik,non_akademik',
            'tingkat' => 'required|string|max:100',
            'peringkat' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'file_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->except('anggota_kelas_id');

        if ($request->hasFile('file_sertifikat')) {
            if ($prestasi->file_sertifikat) {
                Storage::disk('public')->delete($prestasi->file_sertifikat);
            }

            $data['file_sertifikat'] = $request->file('file_sertifikat')->store('prestasiSiswa', 'public');
        }

        // update master
        $prestasi->update($data);

        // update pivot
        $prestasi->anggotaKelas()->sync($request->anggota_kelas_id);

        return redirect()->route('prestasi-siswa.index')
            ->with('success', 'Data prestasi berhasil diupdate');
    }

    public function destroy($id)
    {
        $prestasi = PrestasiSiswa::findOrFail($id);

        if ($prestasi->file_sertifikat) {
            Storage::disk('public')->delete($prestasi->file_sertifikat);
        }

        // hapus relasi pivot
        $prestasi->anggotaKelas()->detach();

        $prestasi->delete();

        return redirect()->route('prestasi-siswa.index')
            ->with('success', 'Data prestasi berhasil dihapus');
    }
}