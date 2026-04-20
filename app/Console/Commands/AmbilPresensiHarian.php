<?php

namespace App\Console\Commands;

use App\Services\FingerspotSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AmbilPresensiHarian extends Command
{
    protected $signature = 'ambil:presensi-harian';
    protected $description = 'Mengambil data presensi otomatis setiap pagi';

    public function handle(FingerspotSyncService $fingerspotSyncService)
    {
        // Panggil Service, bukan Controller
        $fingerspotSyncService->syncToday();

        Log::info('Ambil presensi harian otomatis jalan.');
        $this->info('Presensi berhasil diambil!'); // Muncul di terminal
    }
}