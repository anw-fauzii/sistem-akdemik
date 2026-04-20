<?php

namespace App\Http\Controllers;

use App\Models\PesanSaran;
use App\Http\Requests\StoreThreadRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Services\PesanSaranService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PesanSaranController extends Controller
{
    public function __construct(
        protected PesanSaranService $service
    ) {}

    public function index(): View
    {
        $threads = $this->service->getThreadsForUser(auth()->user());

        // Mapping sederhana untuk view
        $threads->each(function ($item) {
            $item->last_message = $item->latestDetail?->pesan;
        });

        return view('pesan_saran.index', compact('threads'));
    }

    public function data(): JsonResponse
    {
        $threads = $this->service->getThreadsForUser(auth()->user());

        // Menggunakan latestDetail dari relasi (Bebas N+1 Query)
        $mappedData = $threads->map(fn ($item) => [
            'id'              => $item->id,
            'nama_siswa'      => $item->siswa?->nama_lengkap ?? null,
            'subjek'          => $item->subjek,
            'last_message'    => $item->latestDetail?->pesan,
            'last_message_at' => $item->last_message_at, // Hasil dari withMax
            'unread_count'    => $item->unread_count,
        ]);

        return response()->json($mappedData);
    }

    public function store(StoreThreadRequest $request): JsonResponse
    {
        $thread = $this->service->createThread(auth()->user(), $request->validated());

        return response()->json([
            'success' => true,
            'id'      => $thread->id
        ]);
    }

    public function show(PesanSaran $pesanSaran): View
    {
        return view('pesan_saran.show', ['pesan' => $pesanSaran]);
    }

    public function kirim(StoreMessageRequest $request, PesanSaran $pesanSaran): JsonResponse
    {
        $this->service->replyToThread($pesanSaran, auth()->user(), $request->pesan);

        return response()->json(['success' => true]);
    }

    public function fetch(PesanSaran $pesanSaran): JsonResponse
    {
        $messages = $this->service->fetchAndMarkAsRead($pesanSaran, auth()->user());

        return response()->json($messages);
    }
}