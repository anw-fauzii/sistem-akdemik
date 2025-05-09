<!-- components/modal-pembayaran.blade.php -->
<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formPembayaran" method="POST" action="{{ route('keuangan-tahunan.store') }}">
          @csrf
          <input type="hidden" name="tagihan_id" id="tagihan_id">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel">Metode Pembayaran</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                  <div class="form-group mb-3">
                      <label for="metode">Pilih Metode Pembayaran:</label>
                      <select name="metode" id="metode" class="form-control" required>
                          <option value="">-- Pilih --</option>
                          <option value="lunas">Lunas</option>
                          <option value="cicil">Cicil</option>
                      </select>
                  </div>
                  <div class="form-group mb-3" id="nominalCicilGroup" style="display: none;">
                      <label for="nominal">Nominal Cicilan:</label>
                      <input type="number" class="form-control" name="nominal" id="nominal" min="1">
                      <small id="sisaTagihanText" class="text-muted"></small>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-success">Bayar</button>
              </div>
          </div>
      </form>
    </div>
  </div>