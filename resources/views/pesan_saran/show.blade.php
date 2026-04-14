@extends('layouts.app2')

@section('title')
    <title>Detail Pesan Saran</title>
@endsection

@section('content')
    <style>
        .chat-shell {
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
            overflow: hidden;
            background: #fff;
        }

        .chat-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .chat-header-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .chat-header-subtitle {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .chat-box {
            height: 65vh;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fb;
        }

        .message-row {
            display: flex;
            margin-bottom: 14px;
        }

        .message-row.me {
            justify-content: flex-end;
        }

        .message-row.other {
            justify-content: flex-start;
        }

        .bubble {
            padding: 10px 14px;
            border-radius: 12px;
            max-width: 75%;
            font-size: 14px;
            line-height: 1.5;
        }

        .bubble.other {
            background: #fff;
            border: 1px solid #e9ecef;
        }

        .bubble.me {
            background: #dcf8c6;
        }

        .time {
            font-size: 11px;
            text-align: right;
            margin-top: 6px;
            color: #666;
        }

        /* 🔥 DATE SEPARATOR */
        .date-separator {
            text-align: center;
            margin: 15px 0;
            font-size: 12px;
            color: #6c757d;
            position: relative;
        }

        .date-separator::before,
        .date-separator::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #ddd;
        }

        .date-separator::before {
            left: 0;
        }

        .date-separator::after {
            right: 0;
        }

        .chat-input {
            display: flex;
            gap: 10px;
            padding: 12px;
            border-top: 1px solid #e9ecef;
        }

        .chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .empty-chat {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>

    <div class="app-main__inner">

        <div class="main-card card">
            <div class="card-header d-flex justify-content-between">
                <a href="{{ route('pesan-saran.index') }}" class="btn btn-light">←</a>
            </div>

            <div class="card-body p-0">
                <div class="chat-shell">

                    {{-- HEADER --}}
                    <div class="chat-header">
                        <div class="chat-header-title">{{ $pesan->nama_siswa }}</div>
                        <div class="chat-header-subtitle">
                            {{ $pesan->subjek ?: 'Tanpa subjek' }}
                        </div>
                    </div>

                    {{-- CHAT --}}
                    <div id="chatBox" class="chat-box"></div>

                    {{-- INPUT --}}
                    <form id="chatForm" class="chat-input">
                        @csrf
                        <input type="text" id="inputPesan" placeholder="Ketik pesan...">
                        <button class="btn btn-primary">Kirim</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        const currentUser = "{{ auth()->user()->hasRole('admin') ? 'admin' : 'user' }}";

        function loadChat() {
            fetch("{{ route('pesan-saran.fetch', $pesan->id) }}")
                .then(res => res.json())
                .then(data => {

                    let html = '';
                    let lastDate = null;

                    if (!data.length) {
                        html = '<div class="empty-chat">Belum ada pesan</div>';
                    }

                    data.forEach(item => {

                        let currentDate = new Date(item.created_at);

                        let dateString = currentDate.toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                        // 🔥 LABEL WA STYLE
                        let now = new Date();
                        let label = dateString;

                        if (currentDate.toDateString() === now.toDateString()) {
                            label = "Hari ini";
                        } else {
                            let yesterday = new Date();
                            yesterday.setDate(yesterday.getDate() - 1);

                            if (currentDate.toDateString() === yesterday.toDateString()) {
                                label = "Kemarin";
                            }
                        }

                        // 🔥 separator
                        if (lastDate !== label) {
                            html += `<div class="date-separator">${label}</div>`;
                            lastDate = label;
                        }

                        let isMe = item.sender === currentUser;

                        html += `
                    <div class="message-row ${isMe ? 'me' : 'other'}">
                        <div class="bubble ${isMe ? 'me' : 'other'}">
                            ${item.pesan}
                            <div class="time">
                                ${currentDate.toLocaleTimeString([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                })}
                            </div>
                        </div>
                    </div>
                `;
                    });

                    let box = document.getElementById('chatBox');
                    box.innerHTML = html;
                    box.scrollTop = box.scrollHeight;
                });
        }

        loadChat();
        setInterval(loadChat, 3000);

        // kirim pesan
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let input = document.getElementById('inputPesan');

            if (!input.value.trim()) return;

            fetch("{{ route('pesan-saran.kirim', $pesan->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    pesan: input.value
                })
            }).then(() => {
                input.value = "";
                loadChat();
            });
        });

        // enter kirim
        document.getElementById('inputPesan').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('chatForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>
@endsection
