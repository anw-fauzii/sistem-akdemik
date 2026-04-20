<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Http\Requests\GuruRequest;
use App\Services\GuruService;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GuruController extends Controller
{
    public function __construct(
        protected GuruService $service
    ) {}

    public function index(): View
    {
        return view('data_master.guru.index', [
            'guru' => $this->service->getAll()
        ]);
    }

    public function create(): View
    {
        return view('data_master.guru.create');
    }

    public function store(GuruRequest $request): RedirectResponse
    {
        try {
            $this->service->store($request->validated());
            return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Guru $guru): View
    {
        return view('data_master.guru.edit', compact('guru'));
    }

    public function update(GuruRequest $request, Guru $guru): RedirectResponse
    {
        try {
            $this->service->update($guru, $request->validated());
            return redirect()->route('guru.index')->with('success', 'Data guru berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        $this->service->delete($guru);
        return redirect()->route('guru.index')->with('success', 'Guru dan akun User berhasil dihapus');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate(['file_import' => 'required|mimes:xlsx,csv,xls']);

        try {
            // Menggunakan facade Excel
            Excel::import(new GuruImport, $request->file('file_import'));
            return back()->with('success', 'Guru & Staf sedang diproses di latar belakang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function format(): BinaryFileResponse
    {
        $file = public_path('format_excel/format_import_guru.xlsx');
        return response()->download($file, 'format_import_guru_' . now()->format('Y-m-d_H_i_s') . '.xlsx');
    }
}