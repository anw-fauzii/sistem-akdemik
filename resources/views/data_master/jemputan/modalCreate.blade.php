<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="createForm" action="{{ route('anggota-jemputan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah Anggota Jemputan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="jemputan_id" value="{{ $jemputan->id }}">

                    <div class="form-group">
                        <label for="anggota_kelas_ids">Pilih Siswa</label>
                        <select multiple="multiple" size="10" name="anggota_kelas_ids[]" id="anggota_kelas_ids"
                            class="duallistbox form-control">
                            @foreach ($siswa_belum_masuk_jemputan as $belum_masuk_jemputan)
                                <option value="{{ $belum_masuk_jemputan->id }}">
                                    {{ $belum_masuk_jemputan->siswa->nis ?? '-' }} |
                                    {{ $belum_masuk_jemputan->siswa->nama_lengkap ?? 'Nama Tidak Ada' }}
                                    ({{ $belum_masuk_jemputan->kelas->nama_kelas ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row pt-3 pb-0 justify-content-end">
                        <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="keterangan" required>
                                <option value="" selected disabled>-- Pilih Keterangan --</option>
                                <option value="PP">PP</option>
                                <option value="Pulang">Pulang</option>
                                <option value="Pergi">Pergi</option>
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
        // PERBAIKAN 2: Konfigurasi Teks yang Benar (Tanpa Hack Console)
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: '<strong class="text-primary">Belum Punya Jemputan</strong>',
            selectedListLabel: '<strong class="text-success">Akan Ditambahkan</strong>',
            moveOnSelect: false,
            infoText: 'Total {0} siswa', // <-- Teks diubah jadi siswa (bukan mata pelajaran)
            infoTextEmpty: 'Tidak ada siswa',
            filterPlaceHolder: 'Cari Nama / NIS...',
            filterTextClear: 'Hapus Pencarian'
        });

        // UX: Spinner saat submit agar user tidak klik 2 kali
        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function() {
            // Hanya jalankan spinner jika form valid (memiliki minimal 1 siswa terpilih)
            if ($('#anggota_kelas_ids').val().length > 0) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
            }
        });
    });
</script>
