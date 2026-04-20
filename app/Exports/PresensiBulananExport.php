<?php

namespace App\Exports;

use App\Models\BulanSpp;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresensiBulananExport implements FromView
{
    public function __construct(
        protected array $statistikPerKelas,
        protected BulanSpp $bulan
    ) {}

    public function view(): View
    {
        return view('export.excel.presensi_bulanan', [
            'statistikPerKelas' => $this->statistikPerKelas,
            'bulan'             => $this->bulan,
        ]);
    }
}