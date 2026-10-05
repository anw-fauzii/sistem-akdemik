@php
    $semester1 = $item->semester ? $files->filter(fn($f) => str_contains($f->link, 'Semester 1')) : collect();
    $semester2 = $item->semester ? $files->filter(fn($f) => str_contains($f->link, 'Semester 2')) : collect();
@endphp

<div class="modal fade" id="modalCreate{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="modalCreateLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold" id="modalCreateLabel{{ $item->id }}">
                    <i class="pe-7s-folder mr-2"></i> File: {{ $item->nama_kategori }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                @if ($item->semester)

                    <div class="bg-secondary text-white px-3 py-2 font-weight-bold">Semester 1</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($semester1 as $f)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('administrasi-kelas.show', $f->id) }}" target="_blank"
                                        class="text-primary font-weight-bold text-decoration-none">
                                        <i class="pe-7s-download mr-1 font-weight-bold"></i> {{ $f->keterangan }}
                                    </a>
                                    <span
                                        class="badge badge-pill {{ $f->status ? 'badge-success' : 'badge-warning' }} ml-2">
                                        {{ $f->status ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </div>
                                <form action="{{ route('administrasi-kelas.destroy', $f->id) }}" method="POST"
                                    class="delete-form d-inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-button"
                                        title="Hapus File dari GDrive">
                                        <i class="pe-7s-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada file di Semester 1</li>
                        @endforelse
                    </ul>

                    <div class="bg-secondary text-white px-3 py-2 font-weight-bold mt-2">Semester 2</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($semester2 as $f)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('administrasi-kelas.show', $f->id) }}" target="_blank"
                                        class="text-primary font-weight-bold text-decoration-none">
                                        <i class="pe-7s-download mr-1 font-weight-bold"></i> {{ $f->keterangan }}
                                    </a>
                                    <span
                                        class="badge badge-pill {{ $f->status ? 'badge-success' : 'badge-warning' }} ml-2">
                                        {{ $f->status ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </div>
                                <form action="{{ route('administrasi-kelas.destroy', $f->id) }}" method="POST"
                                    class="delete-form d-inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-button"
                                        title="Hapus File dari GDrive">
                                        <i class="pe-7s-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada file di Semester 2</li>
                        @endforelse
                    </ul>
                @else
                    <div class="bg-secondary text-white px-3 py-2 font-weight-bold">Daftar File</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($files as $f)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('administrasi-kelas.show', $f->id) }}" target="_blank"
                                        class="text-primary font-weight-bold text-decoration-none">
                                        <i class="pe-7s-download mr-1 font-weight-bold"></i> {{ $f->keterangan }}
                                    </a>
                                    <span
                                        class="badge badge-pill {{ $f->status ? 'badge-success' : 'badge-warning' }} ml-2">
                                        {{ $f->status ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                    </span>
                                </div>
                                <form action="{{ route('administrasi-kelas.destroy', $f->id) }}" method="POST"
                                    class="delete-form d-inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-button"
                                        title="Hapus File dari GDrive">
                                        <i class="pe-7s-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada file administrasi</li>
                        @endforelse
                    </ul>

                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
