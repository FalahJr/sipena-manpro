<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Tambah Nilai Pembelajaran</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
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
            @if (DB::table("guru")->where("user_id",Auth::user()->id)->where("is_mapel","Y")->get()->isNotEmpty())
            <tr>
              <td>Mata Pelajaran</td>
              <td>
              <input type="text" class="form-control form-control-sm inputtext mapel_id" name="mapel_id" readonly value="{{ DB::table("mapel")->where("guru_id",DB::table("guru")->where("user_id",Auth::user()->id)->first()->id)->first()->nama }}">
            </td>
           </tr>
            @else
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
            @endif
            <tr>
              <td>Semester <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="semester">
                  <option disabled selected value>Pilih</option>
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
