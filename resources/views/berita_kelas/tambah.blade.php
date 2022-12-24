<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Berita Kelas</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">

          <tr>
            <td>Judul <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext judul" name="judul">
              <input type="hidden" class="form-control form-control-sm id" name="id">
            </td>
          </tr>
          <tr>
            <td>Deskripsi <span style="color:red;">*</span></td>
            <td>
              <textarea class="form-control form-control-sm deskripsi" name="deskripsi" rows="8" cols="80"></textarea>
              <!-- <div class="alert alert-warning" role="alert">
              This address will also be used for the shop address (Format: street name and house number (space) sub-district (space) city)
              </div> -->
            </td>
          </tr>
          <tr>
            <td>Pilih Kelas</td>
            <td>
              <select class="form-control form-control-sm inputtext walikelas" name="kelas_id">
                <option disabled selected value>Pilih</option>
                  <?php foreach($items as $item){ ?>
                  <option value="<?= $item->id ?>">
                    <?= $item->nama ?>
                  </option>
                  <?php }?>
                </select>
            </td>

          </tr>
          <tr>
            <td>Foto</td>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="foto" accept="image/*">
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
