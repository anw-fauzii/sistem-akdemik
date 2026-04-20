<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="createForm" action="{{ route('anggota-t2q.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah Anggota T2Q</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="guru_nipy" value="{{ $guru->nipy }}">

                    <div class="form-group">
                        <label for="anggota_kelas_ids">Pilih Siswa</label>
                        <select multiple="multiple" size="10" name="anggota_kelas_ids[]" id="anggota_kelas_ids"
                            class="duallistbox form-control">
                            @foreach ($siswa_belum_masuk_t2q as $belum_masuk_t2q)
                                <option value="{{ $belum_masuk_t2q->id }}">
                                    {{ $belum_masuk_t2q->nis }} | {{ $belum_masuk_t2q->siswa_nama }}
                                    ({{ $belum_masuk_t2q->kelas }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row pt-3 pb-0 justify-content-end">
                        <label for="tingkat" class="col-sm-3 col-form-label text-right">Tingkat Kelas</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="tingkat" required>
                                <option value="" selected disabled>-- Pilih Tingkat --</option>
                                <option value="PG">PG</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/bootstrap-duallistbox.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/jquery.bootstrap-duallistbox.min.js">
</script>

<script>
    function showCreateModal() {
        $('#modalCreate').appendTo('body').modal('show');
    }

    document.addEventListener("DOMContentLoaded", function() {
        // PERBAIKAN: Konfigurasi Teks Dual Listbox Tanpa Hack Console
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: '<strong class="text-primary">Siswa Belum Masuk T2Q</strong>',
            selectedListLabel: '<strong class="text-success">Siswa Akan Ditambahkan</strong>',
            moveOnSelect: false,
            infoText: 'Total {0} siswa',
            infoTextEmpty: 'Kosong',
            filterPlaceHolder: 'Cari Nama / NIS...',
            filterTextClear: 'Hapus Pencarian'
        });

        // Spinner saat submit + Validasi
        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function() {
            // Cek jika listbox kosong, biarkan HTML5/Laravel yang menolak, jangan kunci tombolnya
            if ($('#anggota_kelas_ids').val().length > 0) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            }
        });
    });
</script>
