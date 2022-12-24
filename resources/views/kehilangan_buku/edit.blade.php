@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/kehilangan-buku')}}">Kehilangan Buku</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Kehilangan Buku</h4>
           
                    <!-- Modal -->
    <!-- Modal content-->

    <div class="row">
      @if ($errors->any())
      <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
      </div>
     @endif
    </div>
        <div class="row">
          <form action="{{url('admin/kehilangan-buku/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            <tr>
              <td>User<span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="user_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($users as $user)
                    <option value="<?= $user->id ?>" @if($user->id == $user_id) selected @endif>
                      <?= $user->username ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
          
            <tr>
            <td>Kehilangan Buku<span style="color:red;">*</span></td>
            <td>
              <select class="form-control form-control-sm inputtext" name="perpus_katalog_id">
              <option disabled selected value>Pilih</option>
                @foreach($books as $book)
                  <option value="<?= $book->id ?>" @if($book->id == $book_id) selected @endif>
                    <?= $book->judul ?>
                  </option>
                  @endforeach
                </select>
            </td>
          </tr>

          <tr>
            <td>Nominal <span style="color:red;">*</span></td>
            <td>
              <input type="text" class="form-control form-control-sm inputtext" value="{{$data->nominal}}" name="nominal" disabled>
            </td>
          </tr>

          <tr>
            <td>Tanggal Pembayaran <span style="color:red;">*</span></td>
            <td>
              <input type="date" class="form-control form-control-sm inputtext tanggal_pembayaran @if($errors->has('tanggal_pembayaran')) is-invalid @endif" value="{{$data->tanggal_pembayaran}}" name="tanggal_pembayaran">
            </td>
          </tr>

          <button class="btn btn-success mt-3" id="simpan" type="submit">Simpan Data</button>
        </form>
        </div>
      </div>
    </div>
    </div>

  </div>
</div>

                  </div>
                </div>
    </div>
  </div>
</div>
<!-- content-wrapper ends -->
@endsection

@section('extra_script')
<script>
  if("{{Session::has('success')}}"){
    iziToast.success({
  icon: 'fa fa-save',
  message: "{{Session::get('success')}}",
});
}
</script>
@endsection

