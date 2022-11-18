<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Guru</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
              Silahkan isi semua data yang bertanda<span style="color:red;">*</span>
            </div>
            <tr>
              <td>Nama <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext nama" name="nama">
                <input type="hidden" class="form-control form-control-sm id" name="id">
              </td>
            </tr>
            <tr>
              <td>Guru Mata Pelajaran</td>
              <td>
                <select class="form-control form-control-sm inputtext guru_mapel" name="guru_mapel">
                <option >Pilih Guru</option>
                  <?php foreach($guru as $mapel){ ?>

                  <option value="<?= $mapel->id ?>">
                    <?= $mapel->nama_lengkap ?>
                  </option>
                  <?php }?>
                </select>
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