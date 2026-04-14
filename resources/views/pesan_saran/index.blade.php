@extends('layouts.app2')

@section('title')
    <title>Pesan Saran</title>
@endsection

@section('content')
    @php
        $isAdmin = auth()->user()->hasRole('admin');
    @endphp

    <style>
        .chat-list {
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
            overflow: hidden;
            background: #fff;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 16px 18px;
            border-bottom: 1px solid #eef0f3;
            text-decoration: none;
            color: inherit;
            transition: 0.2s;
        }

        .chat-item:last-child {
            border-bottom: none;
        }

        .chat-item:hover {
            background: #f8f9fb;
            text-decoration: none;
            color: inherit;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 12px;
            color: #495057;
            flex-shrink: 0;
        }

        .chat-content {
            flex: 1;
            min-width: 0;
        }

        .chat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .chat-name {
            font-weight: 600;
            color: #343a40;
        }

        .chat-time {
            font-size: 12px;
            color: #6c757d;
            white-space: nowrap;
        }

        .chat-preview {
            font-size: 13px;
            color: #6c757d;
            margin-top: 4px;
        }

        .pesan-textarea {
            min-height: 110px;
            resize: vertical;
        }
    </style>

    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-chat icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Pesan Saran
                        <div class="page-title-subheading">
                            Riwayat komunikasi saran, komentar, dan apresiasi.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Daftar Percakapan</span>
                @if (!$isAdmin)
                    <button type="button" class="btn btn-primary" onclick="showCreateModal()">
                        Buat Pesan
                    </button>
                @endif
            </div>

            <div class="card-body">
                <div class="chat-list">
                    @forelse ($threads as $item)
                        <a href="{{ route('pesan-saran.show', $item->id) }}" class="chat-item">
                            <div class="avatar">
                                {{ strtoupper(substr($item->siswa->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>

                            <div class="chat-content">
                                <div class="chat-top">
                                    <div class="chat-name">
                                        {{ $isAdmin ? $item->siswa->nama_lengkap : $item->subjek }}
                                    </div>

                                    <div class="d-flex align-items-center" style="gap:6px;">
                                        @if (!empty($item->unread_count))
                                            <span class="badge badge-sm badge-pill badge-success">
                                                {{ $item->unread_count }} pesan baru
                                            </span>
                                        @endif

                                        <div class="chat-time">
                                            @if ($item->last_message_at)
                                                @php
                                                    $waktu = \Carbon\Carbon::parse($item->last_message_at);
                                                @endphp

                                                @if ($waktu->isToday())
                                                    {{ $waktu->translatedFormat('H:i') }}
                                                @else
                                                    {{ $waktu->format('d/m/y') }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="chat-preview">
                                    {{ \Illuminate\Support\Str::limit($item->last_message ?? 'Belum ada pesan', 40) }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center p-4 text-muted">
                            Belum ada chat
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if (!$isAdmin)
        <div class="modal fade" id="modalCreate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="formCreate">
                        @csrf

                        <div class="modal-header">
                            <h5 class="mb-0">Buat Pesan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <input type="text" name="subjek" class="form-control mb-2" placeholder="Subjek">
                            <textarea name="komentar" class="form-control mb-2 pesan-textarea" placeholder="Komentar..." required></textarea>
                            <textarea name="solusi" class="form-control mb-2 pesan-textarea" placeholder="Solusi..." required></textarea>
                            <textarea name="pesan" class="form-control mb-2 pesan-textarea" placeholder="Pesan Apresiasi..." required></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button class="btn btn-success">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function showCreateModal() {
            $('#modalCreate').appendTo('body').modal('show');
        }

        document.getElementById('formCreate')?.addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('pesan-saran.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = "/pesan-saran/" + res.id;
                    }
                });
        });

        function loadThreads() {
            fetch("{{ route('pesan-saran.index.data') }}")
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    if (!data.length) {
                        html = `
                    <div class="text-center p-4 text-muted">
                        Belum ada chat
                    </div>
                `;
                    }

                    data.forEach(item => {

                        let waktu = '-';

                        if (item.last_message_at) {
                            let t = new Date(item.last_message_at);
                            let now = new Date();

                            if (t.toDateString() === now.toDateString()) {
                                waktu = t.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });
                            } else {
                                waktu = t.toLocaleDateString('id-ID');
                            }
                        }

                        html += `
                    <a href="/pesan-saran/${item.id}" class="chat-item">
                        <div class="avatar">
                            ${(item.nama_siswa ?? 'A')[0].toUpperCase()}
                        </div>

                        <div class="chat-content">
                            <div class="chat-top">
                                <div class="chat-name">
                                    ${item.nama_siswa ?? item.subjek}
                                </div>

                                <div class="d-flex align-items-center" style="gap:6px;">
                                    ${item.unread_count > 0 ? `
                                                            <span class="badge badge-sm badge-pill badge-success">
                                                                ${item.unread_count} pesan baru
                                                            </span>
                                                        ` : ''}

                                    <div class="chat-time">${waktu}</div>
                                </div>
                            </div>

                            <div class="chat-preview">
                                ${(item.last_message ?? 'Belum ada pesan').substring(0, 40)}
                            </div>
                        </div>
                    </a>
                `;
                    });

                    document.querySelector('.chat-list').innerHTML = html;
                });
        }
        loadThreads();
        setInterval(loadThreads, 3000);
    </script>
@endsection
