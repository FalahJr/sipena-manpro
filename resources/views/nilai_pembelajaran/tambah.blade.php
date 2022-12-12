<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Tambah Nama Fasilitas</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
            Silahkan isi semua data yang bertanda<span style="color:red;">*</span>
            </div>
            <tr>
              <td>Siswa <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="siswa_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($students as $student)
                    <option value="<?= $student->id ?>">
                      <?= $student->nama_lengkap ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Kelas <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="kelas_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($classes as $class)
                    <option value="<?= $class->id ?>">
                      <?= $class->nama ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Mata Pelajaran <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="mapel_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($lessons as $lesson)
                    <option value="<?= $lesson->id ?>">
                      <?= $lesson->nama ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Semester <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="semester">
                  <option value="Ganjil">Ganjil</option>
                  <option value="Genap">Genap</option>
                  </select>
              </td>
            </tr>
            <tr>
              <td>Ulangan Harian <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext ulangan_harian" name="ulangan_harian">
              </td>
            </tr>
            <tr>
              <td>Nilai Tugas <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext nilai_tugas" name="nilai_tugas">
              </td>
            </tr>
            <tr>
              <td>Nilai UTS <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext nilai_uts" name="nilai_uts">
              </td>
            </tr>
            <tr>
              <td>Nilai UAS <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext nilai_uas" name="nilai_uas">
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