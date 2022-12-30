<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Jadwal Peminjaman Fasilitas</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <table class="table table_modal">
    
            @if(Auth::user()->role_id==1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y" )->get()->isNotEmpty())
            <tr>
              <td>Dipinjam Oleh <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext user_id" name="user_id">
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
              <td>Fasilitas <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext peminjaman_fasilitas_id" name="peminjaman_fasilitas_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($facilities as $facility)
                    <option value="<?= $facility->id ?>">
                      <?= $facility->nama ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Jam Mulai <span style="color:red;">*</span></td>
              <td>
                <input type="time" value="{{date('H:i')}}" class="form-control form-control-sm inputtext jam_mulai" name="jam_mulai">
              </td>
            </tr>
            <tr>
              <td>Jam Selesai <span style="color:red;">*</span></td>
              <td>
                <input type="time" class="form-control form-control-sm inputtext jam_selesai" name="jam_selesai">
              </td>
            </tr>
            <?php 

$month = date('m');
$day = date('d');
$year = date('Y');

$today = $year . '-' . $month . '-' . $day;
?>
            <tr>
              <td>Tanggal <span style="color:red;">*</span></td>
              <td>
                <input type="date" value="<?php echo $today; ?>" min="<?php echo $today; ?>" class="form-control form-control-sm inputtext tanggal" name="tanggal">
              </td>
            </tr>
            @if(Auth::user()->role_id==1 || DB::table("pegawai")->where("user_id",Auth::user()->id)->where("is_tata_usaha","Y" )->get()->isNotEmpty())
            <tr>
              <td>Dikonfirmasi Oleh<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext pegawai_id" name="pegawai_id">
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
