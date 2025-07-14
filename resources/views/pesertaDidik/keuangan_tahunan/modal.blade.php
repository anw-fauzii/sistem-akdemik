<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formPembayaran">
          @csrf
          <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title">Pembayaran SPP</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              <div class="modal-body">
                  <input type="hidden" name="tagihan_id" id="tagihan_id">
                  <div class="mb-3">
                      <label class="form-label">Metode Pembayaran</label>
                      <select class="form-control" id="metode" name="metode" required>
                          <option value="">-- Pilih --</option>
                          <option value="lunas">Lunas</option>
                          <option value="cicil">Cicil</option>
                      </select>
                  </div>
                  <div class="mb-3" id="nominalCicilGroup" style="display: none;">
                      <label class="form-label">Nominal Cicilan</label>
                      <input type="number" class="form-control" name="nominal" id="nominal">
                  </div>
                  <div id="sisaTagihanText" class="text-muted"></div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="btnBayar">Bayar</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
          </div>
      </form>
    </div>
</div>