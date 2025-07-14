<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="createForm" action="{{ route('anggota-jemputan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Tambah jemputan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="jemputan_id" value="{{$jemputan->id}}">
                    <div class="form-group">
                        <label for="siswa">Pilih Siswa</label>
                        <select multiple="multiple" size="10" name="siswa_nis[]" id="siswa_nis" class="duallistbox form-control">
                            @foreach($siswa_belum_masuk_jemputan as $belum_masuk_jemputan)
                                <option value="{{$belum_masuk_jemputan->id}}">{{$belum_masuk_jemputan->siswa->nis}} | {{$belum_masuk_jemputan->siswa_nama}} ({{$belum_masuk_jemputan->kelas}})</option>
                            @endforeach
                        </select>
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
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Tambahkan di head -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/bootstrap-duallistbox.min.css">

<!-- Tambahkan di sebelum </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap4-duallistbox/4.0.2/jquery.bootstrap-duallistbox.min.js"></script>


<script>
    function showCreateModal() {
        $('#modalCreate').appendTo('body').modal('show');
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Inisialisasi dual listbox
        $('.duallistbox').bootstrapDualListbox({
            nonSelectedListLabel: 'Siswa yang belum masuk kelas',
            selectedListLabel: 'Siswa hang dipilih',
            moveOnSelect: false,
            infoText: 'Total {0} mata pelajaran',
            infoTextEmpty: 'Kosong',
            filterPlaceHolder: 'Cari...',
            filterTextClear: 'Hapus Filter'
        });

        // Spinner saat submit
        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>
<script>
    // Override console.log dari plugin ini
    const originalConsoleLog = console.log;
    console.log = function(message, ...args) {
        if (typeof message === 'string' && message.includes('Total {0} mata pelajaran')) {
            return;
        }
        originalConsoleLog.apply(console, [message, ...args]);
    };
</script>

