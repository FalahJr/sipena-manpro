<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Tambah Osis</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
             Calon osis adalah siswa yang belom menjadi anggota osis
            </div>
            <tr>
              <td>Calon Osis<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="id">
                  <option disabled selected value>Pilih</option>
                  @foreach($students as $student)
                    <option value="<?= $student->id ?>">
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
