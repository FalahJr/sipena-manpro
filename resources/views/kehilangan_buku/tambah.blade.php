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
            @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isNotEmpty())
            <tr>
              <td>User<span style="color:red;">*</span></td>
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
          <tr>
            @endif
            <td>Kehilangan Buku<span style="color:red;">*</span></td>
            <td>
              <select class="form-control multiselect-ui form-control-sm inputtext"  name="perpus_katalog_id">
                <option disabled selected value>Pilih</option>
                @foreach($books as $book)
                  <option value="<?= $book->id ?>">
                    <?= $book->judul ?>
                  </option>
                  @endforeach

                </select>
            </td>
          </tr>
          @if(Auth::user()->role_id != 1 && DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isEmpty()) 
          <tr>
            <td>Saldo anda</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext" readonly value="{{FormatRupiahFront(Auth::user()->saldo)}}">
            </td>
          </tr>  
          @endif
          <tr>
            <td>Nominal</td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext" readonly value="{{FormatRupiahFront("50000")}}">
            </td>
          </tr>   
          @if(Auth::user()->role_id == 1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_perpus","Y" )->get()->isNotEmpty()) 
          <tr>
            <td>Tanggal Pembayaran <span style="color:red;">*</span></td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_pembayaran" name="tanggal_pembayaran">
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
