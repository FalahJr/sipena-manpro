<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Tambah Kantin</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
              QRCode akan dibuat setelah menambahkan data kantin
            </div>
          <tr>
            <td>Nama Kantin <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama" name="nama">
              <input type="hidden" class="form-control form-control-sm id" name="id">
            </td>
          </tr>
          <tr>
            <td>Pilih Pegawai</td>
            <td>
              <select class="form-control form-control-sm inputtext walikelas" name="pegawai_id">
                <option disabled selected value>Pilih</option>
                  <?php foreach($items as $item){ ?>
                  <option value="<?= $item->id ?>">
                    <?= $item->nama_lengkap ?>
                  </option>
                  <?php }?>
                </select>
            </td>
          </tr>
          <tr>
            <td>Foto Kantin</td>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="foto" accept="image/*">
            </td>
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
