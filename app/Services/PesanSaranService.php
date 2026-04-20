<?php

namespace App\Services;

use App\Models\PesanSaran;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PesanSaranService
{
    public function getThreadsForUser(User $user): Collection
    {
        $isAdmin = $user->hasRole('admin');

        $query = PesanSaran::with(['siswa', 'latestDetail'])
            ->withMax('detail as last_message_at', 'created_at');

        if ($isAdmin) {
            $query->withCount([
                'detail as unread_count' => fn ($q) => $q->where('sender', 'user')->where('is_read', false)
            ]);
        } else {
            $query->where('siswa_nis', $user->email)
                ->withCount([
                    'detail as unread_count' => fn ($q) => $q->where('sender', 'admin')->where('is_read', false)
                ]);
        }

        return $query->orderByDesc('last_message_at')
                    ->orderByDesc('id')
                    ->get();
    }

    /**
     * Membuat thread baru beserta pesan pertamanya dengan Database Transaction.
     */
    public function createThread(User $user, array $data): PesanSaran
    {
        return DB::transaction(function () use ($user, $data) {
            $thread = PesanSaran::create([
                'siswa_nis' => $user->email,
                'subjek'    => $data['subjek'] ?? null,
                'status'    => 'open', // Asumsi ada default status
            ]);

            $thread->detail()->create([
                'sender' => 'user',
                'pesan'  => $data['pesan'],
            ]);

            return $thread;
        });
    }

    /**
     * Menambahkan pesan balasan ke thread yang ada.
     */
    public function replyToThread(PesanSaran $thread, User $user, string $pesan): void
    {
        DB::transaction(function () use ($thread, $user, $pesan) {
            $thread->detail()->create([
                'sender'  => $user->hasRole('admin') ? 'admin' : 'user',
                'pesan'   => $pesan,
                'is_read' => false,
            ]);

            // Touch parent untuk update timestamp `updated_at`
            $thread->touch(); 
        });
    }

    /**
     * Mengambil isi chat dan otomatis menandainya sebagai sudah dibaca.
     */
    public function fetchAndMarkAsRead(PesanSaran $thread, User $user): Collection
    {
        $senderToMarkRead = $user->hasRole('admin') ? 'user' : 'admin';

        $thread->detail()
            ->where('sender', $senderToMarkRead)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $thread->detail()->orderBy('created_at')->get();
    }
}