<!-- Modal -->
<div id="tambah" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Mutasi Siswa</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <center>
          <div class="alert alert-warning" role="alert">
            Silahkan Scan Dulu Berkas, Mejadi Format Gambar
          </div>
        </center>
        <div class="row">
          <table class="table table_modal">
          <tr>
            <td>Pilih Siswa</td>
            <input type="hidden" class="form-control form-control-sm id" name="id">
          </tr>
          <tr>
            <td>
              <select class="form-select" name="siswa_id" id="siswa_id">
                <option selected value="">Pilih Siswa</option>
                @foreach ($data2 as $item)
                  <option value="{{ $item->id }}">{{ $item->nama_lengkap }}</option>
                @endforeach
              </select>
            </td>
          </tr>
          <tr>
            <td>Surat Keterangan Pindah Sekolah Asal</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar" name="image" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder" id="image-holder">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr>
          <tr>
          <td>Tanda Bukti Mutasi Dispen Provinsi</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar1" name="image1" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder1" id="image-holder1">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr>
          <tr>
          <td>Surat Rekom_penyaluran Dari Deriktorat Jendral Dikdasmen</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar2" name="image2" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder2" id="image-holder2">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr>
          <tr>
          <td>Raport Asal</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar3" name="image3" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder3" id="image-holder3">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr>
          <tr>
          <td>Fotocoy Raport</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar4" name="image4" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder4" id="image-holder4">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr>
          <tr>
          <td>Fotocopy Sertifikat</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar5" name="image5" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder5" id="image-holder5">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
            </td>
          </tr>
          <tr>
          <td>Surat Rekomendasi Penerimaan</td>
          </tr>
          <tr>
            <td>
              <input type="file" class="form-control form-control-sm uploadGambar6" name="image6" accept="image/*">
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2">
              <div class="col-md-8 col-sm-6 col-xs-12 image-holder6" id="image-holder6">

                {{-- <img src="#" class="thumb-image img-responsive" height="100px" alt="image" style="display: none"> --}}

            </div>
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
