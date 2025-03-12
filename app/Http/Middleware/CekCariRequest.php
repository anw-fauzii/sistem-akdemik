<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekCariRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('tahun_ajaran_id') || !$request->has('siswa_nis')) {
            return redirect()->route('pembayaran-spp.index')->with('error', 'Silakan cari data terlebih dahulu.');
        }

        return $next($request);
    }
}
