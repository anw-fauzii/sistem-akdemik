<?php

namespace App\Http\Controllers;

use App\Models\PesanSaran;
use App\Models\PesanSaranDetail;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PesanSaranController extends Controller
{
    // ======================
    // LIST CHAT
    // ======================
    public function index()
    {
        $isAdmin = auth()->user()->hasRole('admin');
        $nis = auth()->user()->email;

        $threadsQuery = PesanSaran::query()
            ->with(['siswa', 'latestDetail'])
            ->withMax('detail as last_message_at', 'created_at');

        if ($isAdmin) {
            $threadsQuery->withCount([
                'detail as unread_count' => function ($q) {
                    $q->where('sender', 'user')->where('is_read', false);
                }
            ]);
        } else {
            $threadsQuery->where('siswa_nis', $nis)
                ->withCount([
                    'detail as unread_count' => function ($q) {
                        $q->where('sender', 'admin')->where('is_read', false);
                    }
                ]);
        }

        $threads = $threadsQuery
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->get();

        $threads->map(function ($item) {
            $item->last_message = optional($item->latestDetail)->pesan;
            return $item;
        });

        return view('pesan_saran.index', compact('threads'));
    }

    // ======================
    // BUAT CHAT BARU
    // ======================
    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required'
        ]);

        $nis = auth()->user()->email;
        $siswa = Siswa::where('nis', $nis)->first();

        $thread = PesanSaran::create([
            'siswa_nis' => $nis,
            'nama_siswa' => $siswa->nama_lengkap ?? '-',
            'subjek' => $request->subjek
        ]);

        PesanSaranDetail::create([
            'pesan_saran_id' => $thread->id,
            'sender' => 'user',
            'pesan' => $request->pesan
        ]);

        // 🔥 RETURN JSON (penting)
        return response()->json([
            'success' => true,
            'id' => $thread->id
        ]);
    }

    // ======================
    // DETAIL CHAT
    // ======================
    public function show($id)
    {
        $pesan = PesanSaran::findOrFail($id);

        return view('pesan_saran.show', compact('pesan'));
    }

    // ======================
    // KIRIM PESAN
    // ======================
    public function kirim(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required'
        ]);

        PesanSaranDetail::create([
            'pesan_saran_id' => $id,
            'sender' => auth()->user()->hasRole('admin') ? 'admin' : 'user',
            'pesan' => $request->pesan,
            'is_read' => false
        ]);

        PesanSaran::whereKey($id)->touch();

        return response()->json(['success' => true]);
    }

    // ======================
    // FETCH CHAT (AJAX)
    // ======================
    public function fetch($id)
    {
        $data = PesanSaranDetail::where('pesan_saran_id', $id)
            ->orderBy('created_at')
            ->get();

        // tandai sudah dibaca
        PesanSaranDetail::where('pesan_saran_id', $id)
            ->where('sender', '!=', auth()->user()->hasRole('admin') ? 'admin' : 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($data);
    }

    public function data()
    {
        $isAdmin = auth()->user()->hasRole('admin');

        if ($isAdmin) {
            $threads = PesanSaran::with('siswa')
                ->withCount([
                    'detail as unread_count' => function ($q) {
                        $q->where('sender', 'user')->where('is_read', false);
                    }
                ])
                ->latest('updated_at')
                ->get();
        } else {
            $nis = auth()->user()->email;

            $threads = PesanSaran::with('siswa')
                ->where('siswa_nis', $nis)
                ->withCount([
                    'detail as unread_count' => function ($q) {
                        $q->where('sender', 'admin')->where('is_read', false);
                    }
                ])
                ->latest('updated_at')
                ->get();
        }

        return response()->json($threads->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_siswa' => $item->subjek ?? null,
                'subjek' => $item->subjek,
                'last_message' => optional($item->detail()->latest()->first())->pesan,
                'last_message_at' => optional($item->detail()->latest()->first())->created_at,
                'unread_count' => $item->unread_count,
            ];
        }));
    }
}
