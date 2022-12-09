<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Tambah List Penjualan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body pt-3">
        <div class="row">
          <table class="table table_modal mt-3" id="dynamic_field">
            <tr class="row d-flex">
              <div class="col-4 pr-0 mb-4">
              <label>Pegawai Koperasi</label>
              </div>
              <div class="col-7 pr-0">
              <select class="form-control form-control-sm inputtext select2" name="pegawai_id">
                <option disabled selected value>Pilih Pegawai</option>
                @foreach($employees as $employee)
                  <option value="<?= $employee->id ?>">
                    <?= $employee->nama_lengkap ?>
                  </option>
                  @endforeach
                </select>
              </div>
            </tr>
          <tr class="row d-flex">
            <div class="col-6 pr-0">
              <select class="form-control form-control-sm inputtext w-100 select2" name="koperasi_list_id[]">
                <option disabled selected value>Pilih Barang</option>
                @foreach($cooperatives as $cooperative)
                  <option value="<?= $cooperative->id ?>">
                    <?= $cooperative->nama ?> || <?= $cooperative->harga ?>
                  </option>
                  @endforeach
                </select>
            </div>
            <div class="col-3">
              <input type="number" min="1" value="1" class="form-control form-control-sm" name="jumlah_pembelian[]">
            </div>
            <div class="col-2"><button type="button" name="add" id="add" class="btn btn-info"> <i class="mdi mdi-plus menu-icon"></i></button></div> 
          </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success" id="simpan" type="button">Simpan</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
      </div>

  </div>
</div>
