<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Kegiatan OSIS</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
            Silahkan isi semua data yang bertanda<span style="color:red;">*</span>
            </div>
            <tr>
              <td>Kegiatan <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext kegiatan" name="kegiatan">
              </td>
            </tr>
            <tr>
              <td>Jam Mulai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jam_mulai" name="jam_mulai">
              </td>
            </tr>
            <tr>
              <td>Jam Selesai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jam_selesai" name="jam_selesai">
              </td>
            </tr>
            <tr>
              <td>Pelaksana<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="pelaksana">
                  <option >Pilih</option>
                  @foreach($students as $student)
                    <option value="<?= $student->nama_lengkap ?>">
                      <?= $student->nama_lengkap ?>
                    </option>
                    @endforeach
  
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