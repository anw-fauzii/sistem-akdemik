<?php

namespace App\Http\Controllers;

use App\Services\AkumulasiPoinKedisiplinanService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkumulasiPoinKedisiplinanController extends Controller
{
    public function __construct(
        private readonly AkumulasiPoinKedisiplinanService $akumulasiService
    ) {}

    /**
     * Menampilkan halaman akumulasi poin.
     */
    public function index(Request $request): View
    {
        $kelasList = $this->akumulasiService->getDaftarKelas();
        
        $akumulasiSiswa = collect(); // Default collection kosong
        $kelasTerpilih = $request->query('kelas_id');

        // Jika user sudah memilih kelas dan menekan tombol filter
        if ($kelasTerpilih) {
            $akumulasiSiswa = $this->akumulasiService->getAkumulasiByKelas((int) $kelasTerpilih);
        }

        return view('akumulasi_poin_kedisiplinan.index', compact('kelasList', 'akumulasiSiswa', 'kelasTerpilih'));
    }
}