<?php

namespace App\Http\Controllers\SaranaPrasarana;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\LokasiBarang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use App\Http\Requests\BarangRequest;
use App\Services\BarangService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;

class BarangController extends Controller
{
    public function __construct(
        protected BarangService $service,
        protected LokasiBarang $lokasiBarang,
        protected KategoriBarang $kategoriBarang
    ) {}

    public function index(): View
    {
        return view('sarana_prasarana.barang.index', [
            'barang' => $this->service->getAll(),
            'lokasi' => $this->lokasiBarang->all(),
            'kategori' => $this->kategoriBarang->all()
        ]);
    }

    public function create(): View
    {
        return view('sarana_prasarana.barang.create', [
            'lokasi' => $this->lokasiBarang->all(),
            'kategori' => $this->kategoriBarang->all()
        ]);
    }

    public function store(BarangRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil disimpan');
    }

    public function edit(Barang $barang): View
    {
        return view('sarana_prasarana.barang.edit', [
            'barang' => $barang,
            'lokasi' => $this->lokasiBarang->all(),
            'kategori' => $this->kategoriBarang->all()
        ]);
    }

    public function update(BarangRequest $request, Barang $barang): RedirectResponse
    {
        $this->service->update($barang, $request->validated());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diupdate');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        $this->service->delete($barang);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }

    public function format(): BinaryFileResponse
    {
        $file = public_path('format_excel/format_import_barang.xlsx');
        return response()->download($file, 'format_import_barang_' . now()->format('Y-m-d_H_i_s') . '.xlsx');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate(['file_import' => 'required|mimes:xlsx,csv,xls']);

        try {
            Excel::import(new BarangImport, $request->file('file_import'));
            return back()->with('success', 'Barang sedang diproses di latar belakang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
