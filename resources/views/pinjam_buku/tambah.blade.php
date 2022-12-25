<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Pinjam Buku</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
            <div class="alert alert-warning" role="alert">
              1. Maksimal peminjaman 3 buku
            </div>
            @if (Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isNotEmpty())
            <tr>
              <td>Peminjam<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="user_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($users as $user)
                    <option value="<?= $user->id ?>">
                      <?= $user->username ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            @endif
          <tr>
            <td>Pinjaman Buku<span style="color:red;">*</span></td>
            <td>
              <select class="form-control multiselect-ui form-control-sm inputtext"  name="perpus_katalog_id[]" multiple="multiple">
                @foreach($items as $item)
                  <option value="<?= $item->id ?>">
                    <?= $item->judul ?>
                  </option>
                  @endforeach

                </select>
            </td>
          </tr>

          <tr>
            <td>Tanggal Peminjaman <span style="color:red;">*</span></td>
            <td>
              <input type="date" class="form-control form-control-sm inputDate inputtext tanggal_peminjaman" name="tanggal_peminjaman">
            </td>
          </tr>
          @if (Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isNotEmpty())
          <tr>
            <td>Dikonfirmasi Pegawai <span style="color:red;">*</span></td>
            <td>
              <select class="form-control form-control-sm inputtext" name="pegawai_id">
                <option disabled selected value>Pilih</option>
                @foreach($employees as $employee)
                  <option value="<?= $employee->id ?>">
                    <?= $employee->nama_lengkap ?>
                  </option>
                  @endforeach
                </select>
            </td>
          </tr>
          @endif

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
