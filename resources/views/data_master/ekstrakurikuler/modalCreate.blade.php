<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="createForm" action="{{ route('anggota-ekstrakurikuler.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah Anggota Ekstrakurikuler</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="ekstrakurikuler_id" value="{{ $ekstrakurikuler->id }}">

                    <div class="form-group">
                        <label for="anggota_kelas_ids">Pilih Siswa</label>
                        <select multiple="multiple" size="10" name="anggota_kelas_ids[]" id="anggota_kelas_ids"
                            class="duallistbox form-control">
                            @foreach ($siswa_belum_masuk_ekstrakurikuler as $belum_masuk)
                                <option value="{{ $belum_masuk->id }}">
                                    {{ $belum_masuk->nis }} | {{ $belum_masuk->siswa_nama }} ({{ $belum_masuk->kelas }})
                                </option>
                            @endforeach
                        </select>
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
        // PERBAIKAN: Teks Dual Listbox disesuaikan, tanpa perlu hack console.log
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: '<strong class="text-primary">Siswa Belum Masuk Ekskul</strong>',
            selectedListLabel: '<strong class="text-success">Siswa Akan Ditambahkan</strong>',
            moveOnSelect: false,
            infoText: 'Total {0} siswa',
            infoTextEmpty: 'Kosong',
            filterPlaceHolder: 'Cari Nama / NIS...',
            filterTextClear: 'Hapus Pencarian'
        });

        // Spinner saat submit
        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function() {
            // Validasi: Jangan kunci tombol jika box kanan masih kosong
            if ($('#anggota_kelas_ids').val().length > 0) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            }
        });
    });
</script>
