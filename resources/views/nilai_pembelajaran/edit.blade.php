@extends('main')
@section('content')
<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb bg-info">
          <li class="breadcrumb-item"><i class="fa fa-home"></i>&nbsp;<a href="{{url('admin/nilai-pembelajaran')}}">Pembelajaran Siswa</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
      </nav>
    </div>
  	<div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            
           <h4 class="card-title">Edit Nilai Pembelajaran</h4>
           
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
          <form action="{{url('admin/nilai-pembelajaran/update')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" class="form-control form-control-sm id" value="{{$data->id}}" name="id">
            <tr>
              <td>Siswa <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext @if($errors->has('nama')) is-invalid @endif" name="siswa_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($students as $student)
                    <option value="<?= $student->id ?>" @if($data->siswa_id == $student->id) selected @endif>
                      <?= $student->nama_lengkap ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Kelas <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext @if($errors->has('nama')) is-invalid @endif" name="kelas_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($classes as $class)
                    <option value="<?= $class->id ?>" @if($data->kelas_id == $class->id) selected @endif>
                      <?= $class->nama ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Mata Pelajaran <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext @if($errors->has('mapel_id')) is-invalid @endif" name="mapel_id">
                  <option disabled selected value>Pilih</option>
                  @foreach($lessons as $lesson)
                    <option value="<?= $lesson->id ?>" @if($data->mapel_id == $lesson->id) selected @endif>
                      <?= $lesson->nama ?>
                    </option>
                    @endforeach
  
                  </select>
              </td>
            </tr>
            <tr>
              <td>Semester <span style="color:red;">*</span></td>
              <td>
                <select class="form-control form-control-sm inputtext" name="semester">
                  <option value="Ganjil" @if($data->semester == "Ganjil") selected @endif>Ganjil</option>
                  <option value="Genap" @if($data->semester == "Genap") selected @endif>Genap</option>
                  </select>
              </td>
            </tr>
            <tr>
              <td>Ulangan Harian <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext ulangan_harian @if($errors->has('ulangan_harian')) is-invalid @endif" value="{{$data->ulangan_harian}}" name="ulangan_harian">
              </td>
            </tr>
            <tr>
              <td>Nilai Tugas <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext nilai_tugas @if($errors->has('nilai_tugas')) is-invalid @endif" value="{{$data->nilai_tugas}}" name="nilai_tugas">
              </td>
            </tr>
            <tr>
              <td>Nilai UTS <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext nilai_uts @if($errors->has('nilai_uts')) is-invalid @endif" value="{{$data->nilai_uts}}" name="nilai_uts">
              </td>
            </tr>
            <tr>
              <td>Nilai UAS <span style="color:red;">*</span></td>
              <td>
                <input type="number" class="form-control form-control-sm inputtext nilai_uas @if($errors->has('nilai_uas')) is-invalid @endif" value="{{$data->nilai_uas}}" name="nilai_uas">
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

