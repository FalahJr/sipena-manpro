<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Top Up Saldo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <center>
          <div class="alert alert-warning" role="alert">
            Silahkan Transfer Dahulu Ke Rekening Sekolah
            <br>
            No Rekening : BCA 012312023 a/n SMK Suka Cita Bersama Dirinya
            <br>
            Lalu Lampirkan Bukti Transfer
          </div>
        </center>
        <div class="row">
          <table class="table table_modal">
            <tr>
              <td>Nominal</td>
            </tr>
            <tr>
              <td>
                <input type="text" class="form-control form-control-sm inputtext rp nominal" value="" name="nominal">
              </td>
            </tr>
            <tr>
              <td>Bukti Transfer</td>
            </tr>
            <tr>
              <td>
                <input type="file" class="form-control form-control-sm uploadGambar" name="image" accept="image/*">
                <input type="hidden" class="form-control form-control-sm id" name="id">
              </td>
            </tr>
            <tr>
              <td>Keterangan</td>
            </tr>
            <tr>
              <td>
                <textarea name="keterangan" class="form-control keterangan" rows="8" cols="80"></textarea>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" id="simpan" type="button">Process</button>
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
      </div>
      </div>

  </div>
</div>
