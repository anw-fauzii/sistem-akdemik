<div class="modal fade" id="modalCreate{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateLabel">{{$item->nama_kategori}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @if ($item->semester)
                    
                    <h5>Semester 1</h5>
                    <ul>
                        @forelse ($semester1 as $f)
                            <li>
                                {{ $f->keterangan }} —
                                <span class="badge badge-sm badge-pill {{ $f->status ? 'badge-success' : 'badge-warning' }}">
                                    {{ $f->status ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                </span>
                            </li>

                        @empty
                            <li>Tidak ada file</li>
                        @endforelse
                    </ul>
                    <hr>
                    <h5>Semester 2</h5>
                    <ul>
                        @forelse ($semester2 as $f)
                            <li>
                                {{ $f->keterangan }} —
                                <span class="badge badge-sm badge-pill {{ $f->status ? 'badge-success' : 'badge-warning' }}">
                                    {{ $f->status ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                </span>
                            </li>

                        @empty
                            <li>Tidak ada file</li>
                        @endforelse
                    </ul>
                @else
                    <h5>Daftar File</h5>
                    <ul>
                        @forelse ($files as $f)
                            <li class="mb-1">
                                {{ $f->keterangan }} —
                                <span class="badge badge-sm badge-pill {{ $f->status ? 'badge-success' : 'badge-warning' }}">
                                    {{ $f->status ? 'Sudah diperiksa' : 'Belum diperiksa' }}
                                </span>
                            </li>
                        @empty
                            <li>Tidak ada file</li>
                        @endforelse
                    </ul>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showCreateModal(id) {
        $('#modalCreate'+id).appendTo('body').modal('show');
    }
</script>
