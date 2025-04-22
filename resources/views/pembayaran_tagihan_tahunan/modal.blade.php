<div class="modal fade" id="modalBayar{{ $loop->index }}" tabindex="-1" role="dialog" aria-labelledby="modalBayarLabel{{ $loop->index }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <form id="createForm" method="POST" action="{{ route('pembayaran-tagihan-tahunan.store') }}">
            @csrf
            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahun_ajaran_id }}">
            <input type="hidden" name="tagihan_tahunan_id" value="{{ $item['id'] }}">
            <input type="hidden" name="siswa_nis" value="{{ $siswa_nis }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBayarLabel{{ $loop->index }}">Bayar: {{ $item['jenis'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Total Tagihan:</strong> Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}</p>
                    <p><strong>Sudah Dibayar:</strong> Rp {{ number_format($item['total_dibayar'], 0, ',', '.') }}</p>
                    <p><strong>Sisa:</strong> Rp {{ number_format($item['sisa_tagihan'], 0, ',', '.') }}</p>

                    <div class="mb-3">
                        <label for="jumlah_bayar{{ $loop->index }}" class="form-label">Jumlah yang dibayar</label>
                        <input type="number" class="form-control" name="jumlah_bayar" id="jumlah_bayar{{ $loop->index }}"
                            min="1" max="{{ $item['sisa_tagihan'] }}" required>
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
<script>
    function showCreateModal(id) {
        $('#' + id).appendTo('body').modal('show');
    }

    document.addEventListener("DOMContentLoaded", function () {

        const form = document.getElementById("createForm");
        const submitBtn = document.getElementById("submitBtn");

        form.addEventListener("submit", function () {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
        });
    });
</script>