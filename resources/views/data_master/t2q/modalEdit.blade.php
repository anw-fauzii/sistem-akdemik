<!-- Modal Pindah Guru -->
<div class="modal fade" id="modalEditGuru" tabindex="-1" role="dialog" aria-labelledby="modalEditGuruLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formEditGuru" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditGuruLabel">Pindah Guru T2Q</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Pindahkan siswa <strong id="namaSiswaEdit"></strong> ke kelompok guru:</p>
                    <div class="form-group">
                        <label for="guru_nipy">Pilih Guru Tujuan <span class="text-danger">*</span></label>
                        <select name="guru_nipy" id="guru_nipy" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Guru --</option>
                            @foreach ($list_guru as $g)
                                <option value="{{ $g->nipy }}">
                                    {{ $g->nama_lengkap }}{{ $g->gelar ? ', ' . $g->gelar : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="submitEditBtn" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script untuk menampilkan modal edit
    function showEditModal(id, namaSiswa) {
        document.getElementById('namaSiswaEdit').innerText = namaSiswa;

        // Set action URL form (menggunakan route name yang disesuaikan dengan param ID)
        let baseUrl = "{{ route('anggota-t2q.update', ':id') }}";
        let finalUrl = baseUrl.replace(':id', id);
        document.getElementById('formEditGuru').action = finalUrl;

        $('#modalEditGuru').appendTo('body').modal('show');
    }
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("formEditGuru");
        const submitBtn = document.getElementById("submitEditBtn");
        form.addEventListener("submit", function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>
