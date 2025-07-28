<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PresensiBulananExport implements FromView
{
    protected $statistikPerKelas;
    protected $bulan;

    public function __construct($statistikPerKelas, $bulan)
    {
        $this->statistikPerKelas = $statistikPerKelas;
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        return view('export.excel.presensi_bulanan', [
            'statistikPerKelas' => $this->statistikPerKelas,
            'bulan' => $this->bulan,
        ]);
    }
}
