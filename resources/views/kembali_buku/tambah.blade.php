<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Katalog Buku</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
            Silahkan isi semua data yang bertanda<span style="color:red;">*</span>
            </div>
          <tr>
            <td>Buku <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext judul" name="judul">
              <input type="hidden" class="form-control form-control-sm id" value="{{Auth::user()->id}}" name="user_id">
            </td>
          </tr>
          <tr>
            <td>Author <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext author" name="author">
            </td>
          </tr>
          <tr>
            <td>Bahasa <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext bahasa" name="bahasa">
            </td>
          </tr>
          <tr>
            <td>Total Halaman <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext total_halaman" name="total_halaman">
            </td>
          </tr>    
          <tr>
            <td>Image</td>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="foto" accept="image/*">
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
