@extends('layouts.app2')

@section('title')
    <title>Rekap Administrasi Guru</title>
@endsection

@section('content')
    <div class="app-main__inner">
        <div class="app-page-title mb-3">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon shadow-sm">
                        <i class="pe-7s-id icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>Rekap Administrasi Guru
                        <div class="page-title-subheading">
                            Memeriksa dan memverifikasi dokumen administrasi yang telah diunggah oleh guru.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body py-3 px-4">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="pe-7s-search font-weight-bold" style="font-size: 1.2rem;"></i></span>
                    </div>
                    <input type="text" id="searchInput" class="form-control border-left-0 form-control-lg" placeholder="Ketik nama guru atau NIPY..." style="font-size: 1.1rem; box-shadow: none;">
                </div>
            </div>
        </div>

        <div id="guruContainer">
            @forelse($dataGuru as $guru)
                @php
                    $totalFile = $guru->administrasiGuru->count();
                    $verified = $guru->administrasiGuru->where('status', 1)->count();
                    $pending = $totalFile - $verified;
                @endphp
                
                <div class="main-card mb-4 card guru-card shadow-sm border-0">
                    <div class="p-3 bg-white d-flex justify-content-between align-items-center py-3" style="height: auto;">
                        <div>
                            <i class="pe-7s-user mr-2 text-primary font-weight-bold" style="font-size: 1.5rem; vertical-align: middle;"></i>
                            <span class="font-weight-bold guru-name text-dark" style="font-size: 1.1rem;">{{ $guru->nama_lengkap }}, {{ $guru->gelar }}.</span>
                            <span class="text-muted ml-2 guru-nipy">(NIPY: {{ $guru->nipy }})</span>
                        </div>
                        
                        <div class="badge-status-container" data-total="{{ $totalFile }}" data-pending="{{ $pending }}">
                            @if($totalFile == 0)
                                <span class="badge badge-secondary px-3 py-2">Belum Upload</span>
                            @elseif($pending == 0)
                                <span class="badge badge-success px-3 py-2">Semua Terverifikasi ({{ $totalFile }})</span>
                            @else
                                <span class="badge badge-warning px-3 py-2">Menunggu Verifikasi: {{ $pending }} File</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="mb-0 table table-hover table-striped align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="25%">Kategori Administrasi</th>
                                        <th width="35%">Keterangan File</th>
                                        <th width="15%" class="text-center">Status</th>
                                        <th width="20%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($guru->administrasiGuru as $admin)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong class="text-dark">{{ $admin->kategoriAdministrasi->nama_kategori ?? 'Kategori Dihapus' }}</strong>
                                            </td>
                                            <td class="text-muted">
                                                {{ $admin->keterangan }}
                                            </td>
                                            <td class="text-center status-column">
                                                @if($admin->status)
                                                    <span class="badge badge-success px-2 py-1"><i class="pe-7s-check font-weight-bold"></i> Diverifikasi</span>
                                                @else
                                                    <span class="badge badge-warning px-2 py-1"><i class="pe-7s-clock font-weight-bold"></i> Menunggu</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('administrasi.download', $admin->id) }}" class="btn btn-sm btn-info shadow-sm mr-1" target="_blank" title="Unduh File">
                                                    <i class="pe-7s-download font-weight-bold"></i> Unduh
                                                </a>

                                                @if(!$admin->status)
                                                    <form action="{{ route('administrasi.verify', $admin->id) }}" method="POST" class="d-inline-block m-0 verify-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button" class="btn btn-sm btn-success shadow-sm verify-btn" title="Tandai Sudah Diperiksa">
                                                            <i class="pe-7s-check font-weight-bold"></i> Verify
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="pe-7s-info text-muted d-block mb-2" style="font-size: 2.5rem;"></i>
                                                <span class="text-muted">Guru ini belum mengunggah dokumen administrasi apapun.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info shadow-sm">
                    Tidak ada data guru yang ditemukan di database.
                </div>
            @endforelse
            
            <div id="noResult" class="text-center py-5 d-none">
                <i class="pe-7s-search text-muted mb-3" style="font-size: 4rem;"></i>
                <h4 class="text-muted font-weight-bold">Guru tidak ditemukan</h4>
                <p class="text-muted">Coba ketikkan nama atau NIPY yang lain.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // --- 1. SCRIPT PENCARIAN REALTIME ---
            const searchInput = document.getElementById('searchInput');
            const guruCards = document.querySelectorAll('.guru-card');
            const noResult = document.getElementById('noResult');

            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchValue = this.value.toLowerCase().trim();
                    let visibleCount = 0;

                    guruCards.forEach(card => {
                        const name = card.querySelector('.guru-name').innerText.toLowerCase();
                        const nipy = card.querySelector('.guru-nipy').innerText.toLowerCase();

                        if (name.includes(searchValue) || nipy.includes(searchValue)) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    if (visibleCount === 0 && guruCards.length > 0) {
                        noResult.classList.remove('d-none');
                    } else {
                        noResult.classList.add('d-none');
                    }
                });
            }

            // --- 2. SCRIPT VERIFIKASI AJAX + LIVE RECOUNT ---
            document.querySelectorAll('.verify-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const form = this.closest('form');
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;
                    const btn = this;

                    Swal.fire({
                        title: 'Verifikasi Dokumen',
                        text: 'Apakah dokumen ini sudah sesuai dan lengkap?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Verifikasi!',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-success shadow-sm mx-2',
                            cancelButton: 'btn btn-secondary shadow-sm mx-2'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const originalBtnText = btn.innerHTML;
                            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                            btn.disabled = true;

                            fetch(url, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const tr = form.closest('tr');
                                    const card = form.closest('.guru-card');
                                    
                                    // A. Update Status baris tabel menjadi terverifikasi
                                    const statusTd = tr.querySelector('.status-column');
                                    statusTd.innerHTML = '<span class="badge badge-success px-2 py-1"><i class="pe-7s-check font-weight-bold"></i> Diverifikasi</span>';
                                    
                                    // B. Hapus tombol verify
                                    form.remove();

                                    // C. PERBAIKAN KRUSIAL: Hitung mundur counter di Header Card secara realtime
                                    const badgeContainer = card.querySelector('.badge-status-container');
                                    if (badgeContainer) {
                                        let total = parseInt(badgeContainer.getAttribute('data-total'));
                                        let pending = parseInt(badgeContainer.getAttribute('data-pending'));
                                        
                                        // Kurangi antrean pending sebanyak 1
                                        pending = pending - 1;
                                        badgeContainer.setAttribute('data-pending', pending);
                                        
                                        // Ubah teks atau warna lencana berdasarkan sisa antrean
                                        if (pending === 0) {
                                            badgeContainer.innerHTML = `<span class="badge badge-success px-3 py-2">Semua Terverifikasi (${total})</span>`;
                                        } else {
                                            badgeContainer.innerHTML = `<span class="badge badge-warning px-3 py-2">Menunggu Verifikasi: ${pending} File</span>`;
                                        }
                                    }

                                    // Notifikasi Toastr/Swal
                                    if(typeof toastr !== 'undefined') {
                                        toastr.success(data.message, 'Berhasil');
                                    } else {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: data.message,
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    }
                                } else {
                                    Swal.fire('Gagal!', data.message, 'error');
                                    btn.innerHTML = originalBtnText;
                                    btn.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error!', 'Terjadi gangguan koneksi ke server.', 'error');
                                btn.innerHTML = originalBtnText;
                                btn.disabled = false;
                            });
                        }
                    });
                });
            });
            
        });
    </script>
@endsection