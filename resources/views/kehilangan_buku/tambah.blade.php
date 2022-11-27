<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Kehilangan Buku</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
            Silahkan isi semua data yang bertanda<span style="color:red;">*</span>
            </div>
            <tr>
              <td>User<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="user_id">
                  <option >Pilih</option>
                  @foreach($users as $user)
                    <option value="<?= $user->id ?>">
                      <?= $user->username ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
          <tr>
            <td>Kehilangan Buku<span style="color:red;">*</span></td>
            <td>
              <select class="form-control multiselect-ui form-control-sm inputtext"  name="perpus_katalog_id">
                <option>Pilih</option>
                @foreach($books as $book)
                  <option value="<?= $book->id ?>">
                    <?= $book->judul ?>
                  </option>
                  @endforeach

                </select>
            </td>
          </tr>
          <tr>
            <td>Nominal <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext" name="nominal">
            </td>
          </tr>    
          <tr>
            <td>Tanggal Pembayaran <span style="color:red;">*</span></td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_pembayaran" name="tanggal_pembayaran">
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
