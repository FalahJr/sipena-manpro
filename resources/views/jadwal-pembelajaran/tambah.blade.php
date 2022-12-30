<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Jadwal Pembelajaran</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <tr>
              <td>Mata Pelajaran <span style="color:red;">*</span></td>
              <td>
              <select class="form-control form-control-sm inputtext mapel_id" name="mapel_id">
                <option disabled selected value>Pilih Mata Pelajaran</option>
                  <?php foreach($mapel as $mapel){ ?>

                  <option value="<?= $mapel->id ?>">
                    <?= $mapel->nama ?>
                  </option>
                  <?php }?>
                </select>
                <input type="hidden" class="form-control form-control-sm id" name="id">
              </td>
            </tr>
            <tr>
              <td>Kelas</td>
              <td>
                <select class="form-control form-control-sm inputtext kelas_id" name="kelas_id">
                <option disabled selected value>Pilih Kelas</option>
                  <?php foreach($kelas as $kelas){ ?>

                  <option value="<?= $kelas->id ?>">
                    <?= $kelas->nama ?>
                  </option>
                  <?php }?>
                </select>
              </td>
            </tr>
            <tr>
              <td>Jadwal Hari <span style="color:red;">*</span></td>
              <td>
                <!-- <input type="text" class="form-control form-control-sm inputtext jadwal_hari" name="jadwal_hari"> -->
                <select class="form-control form-control-sm inputtext jadwal_hari" name="jadwal_hari">
                <option disabled selected value>Pilih Hari</option>

                  <option value="Senin">
                   Senin
                  </option>
                  <option value="Selasa">
                   Selasa
                  </option> <option value="Rabu">
                   Rabu
                  </option> <option value="Kamis">
                   Kamis
                  </option> <option value="Jumat">
                   Jumat
                  </option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Jadwal Waktu Mulai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jadwal_waktu" name="jadwal_waktu_mulai">
              </td>
            </tr>
            <tr>
              <td>Jadwal Waktu Akhir <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jadwal_waktu" name="jadwal_waktu_akhir">
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