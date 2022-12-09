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
              <td>Kelas <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext w-100 select2" name="koperasi_list_id[]">
                  <option disabled selected value>Pilih Barang</option>
                  @foreach($cooperatives as $cooperative)
                    <option value="<?= $cooperative->id ?>">
                      <?= $cooperative->nama ?> || <?= $cooperative->harga ?>
                    </option>
                    @endforeach
                  </select>
              </td>
            </tr>
            <tr>
              <td>Wali Murid <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext w-100 select2" name="koperasi_list_id[]">
                  <option disabled selected value>Pilih Barang</option>
                  @foreach($cooperatives as $cooperative)
                    <option value="<?= $cooperative->id ?>">
                      <?= $cooperative->nama ?> || <?= $cooperative->harga ?>
                    </option>
                    @endforeach
                  </select>
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
            <td>Tanggal Lahir</td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tgl_lahir" name="tgl_lahir">
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
              <!-- <div class="alert alert-warning" role="alert">
              This address will also be used for the shop address (Format: street name and house number (space) sub-district (space) city)
              </div> -->
            </td>
          </tr>
          <tr>
            <td>Agama <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext agama" name="agama">
            </td>
          </tr>
          <tr>
            <td>Image</td>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="image" accept="image/*">
            </td>
          </tr>
          <!-- <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder" id="image-holder">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr> -->
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
