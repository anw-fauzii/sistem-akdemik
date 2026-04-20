<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        if (!$user || $user->roles->isEmpty()) {
            Auth::logout(); 
            return redirect()->route('login')->with('error', 'Anda belum memiliki role akses!');
        }

        if (user()?->hasAnyRole(['admin', 'dapur'])) {
            $data = $this->dashboardService->getAdminDashboardData();
            return view('dashboard.admin', $data);
        } 

        if (user()?->hasAnyRole(['siswa_sd', 'siswa_tk'])) {
            $data = $this->dashboardService->getSiswaDashboardData($user->email);
            return view('dashboard.siswa', $data);
        }

        if (user()?->hasAnyRole(['guru_sd', 'guru_tk'])) {
            $data = $this->dashboardService->getGuruDashboardData($user->email);
            return view('dashboard.guru', $data);
        } 

        if (user()?->hasRole('puskesmas')) {
            $data = $this->dashboardService->getPuskesmasDashboardData();
            return view('dashboard.puskesmas', $data);
        }  

        abort(403, 'Role tidak dikenali.');
    }
}