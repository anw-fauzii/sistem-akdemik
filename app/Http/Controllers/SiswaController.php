<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiswaRequest;
use App\Services\SiswaService;
use App\Models\{Siswa, TahunAjaran, Pekerjaan, Penghasilan, Transportasi, BerkebutuhanKhusus, JenjangPendidikan, TarifSpp, Kelas};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function __construct(
        protected SiswaService $service
    ) {}

    public function index(): View
    {
        return view('data_master.siswa.index', [
            'siswa' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('data_master.siswa.create', $this->getFormData());
    }

    public function store(SiswaRequest $request): RedirectResponse
    {
        try {
            $this->service->createSiswa($request->validated());
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show(Siswa $siswa): View
    {
        return view('data_master.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa): View
    {
        return view('data_master.siswa.edit', array_merge(['siswa' => $siswa], $this->getFormData()));
    }

    public function update(SiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->service->updateSiswa($siswa, $request->validated());
        return redirect()->route('siswa.index')->with('success', 'Data siswa diperbarui');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->service->deleteSiswa($siswa);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }

    private function getFormData(): array
    {
        $tahun_ajaran = TahunAjaran::latest()->first();
        return [
            'pekerjaan'           => Pekerjaan::all(['id', 'nama_pekerjaan']),
            'penghasilan'         => Penghasilan::all(['id', 'nama_penghasilan']),
            'transportasi'        => Transportasi::all(['id', 'nama_transportasi']),
            'berkebutuhan_khusus' => BerkebutuhanKhusus::all(['id', 'nama_berkebutuhan_khusus']),
            'pendidikan'          => JenjangPendidikan::all(['id', 'nama_jenjang_pendidikan']),
            'tarif_spp'           => TarifSpp::all(['id', 'unit', 'tahun_masuk']),
            'kelas'               => Kelas::whereTahunAjaranId($tahun_ajaran?->id)->get(),
        ];
    }
}