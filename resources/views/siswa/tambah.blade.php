<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Siswa</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <tr>
              <td>Username <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext username" name="username">
              </td>
            </tr>
            <tr>
              <td>Password <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext password" name="password">
              </td>
            </tr>
            <tr>
              <td>Kelas <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext w-100 select2" name="kelas_id">
                  <option disabled selected value>Pilih Kelas</option>
                  @foreach($classes as $class)
                    <option value="<?= $class->id ?>">
                      <?= $class->nama ?>
                    </option>
                    @endforeach
                  </select>
              </td>
            </tr>
            <tr>
              <td>Wali Murid <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext w-100 select2" name="wali_murid_id">
                  <option disabled selected value>Pilih Wali Murid</option>
                  @foreach($studentGuardians as $studentGuardian)
                    <option value="<?= $studentGuardian->id ?>">
                      <?= $studentGuardian->nama_lengkap ?>
                    </option>
                    @endforeach
                  </select>
              </td>
            </tr>
            <tr>
              <td>NISN <span style="color:red;">*</span></td>
              <td>
                <input type="text" class="form-control form-control-sm inputtext nisn" name="nisn">
              </td>
            </tr>
          <tr>
            <td>Nama Lengkap <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama_lengkap" name="nama_lengkap">
              <input type="hidden" class="form-control form-control-sm id" name="id">
            </td>
          </tr>
          <tr>
            <td>Nama Ayah <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama_ayah" name="nama_ayah">
            </td>
          </tr>
          <tr>
            <td>Nama Ibu <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext nama_ibu" name="nama_ibu">
            </td>
          </tr>
          <!-- <tr>
            <td>Email <span style="color:red;">*</span></td>
            <td>
              <input type="email" class="form-control form-control-sm inputtext agama" name="email">
            </td>
          </tr> -->
          <tr>
            <td>Tempat Lahir <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext tempat_lahir" name="tempat_lahir">
            </td>
          </tr>
          <tr>
            <td>Tanggal Lahir</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_lahir" name="tanggal_lahir">
            </td>
          </tr>
          <tr>
            <td>No Hp</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext phone" name="phone">
            </td>
          </tr>
          <tr>
            <td>Alamat <span style="color:red;">*</span></td>
            <td>
              <textarea class="form-control form-control-sm alamat" name="alamat" rows="8" cols="80"></textarea>
            </td>
          </tr>
          <tr>
            <td>Agama <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext agama" name="agama">
            </td>
          </tr>
          <tr>
            <td>Jenis Kelamin <span style="color:red;">*</span></td>
            <td>
              <select class="form-control" name="jenis_kelamin">
                <option disabled value selected>Pilih</option>
                <option value="L"> Laki-Laki </option>
                <option value="P"> Perempuan </option>
              </select>
            </td>
          </tr>
          <tr>
            <td>Tanggal Daftar</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_daftar" name="tanggal_daftar">
            </td>
          </tr>
          <tr>
            <td>Image</td>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="image" accept="image/*">
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
