<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>SIPENA</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <!-- <link href="lib/animate/animate.min.css" rel="stylesheet"> -->
  <link href="{{ asset('resources/lib/animate/animate.min.css') }}" rel="stylesheet">

  <link href="{{ asset('resources/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- <link href="lib/owlcarousel/asset/owl.carousel.min.css" rel="stylesheet"> -->

    <!-- Customized Bootstrap Stylesheet -->
    <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="{{ asset('resources/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('resources/css/style.css') }}" rel="stylesheet">


    <!-- Template Stylesheet -->
    <!-- <link href="css/style.css" rel="stylesheet"> -->
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0" id="home">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="{{ url('/') }}" class="navbar-brand p-0">
                    <!-- <h1 class="m-0">soFFer</h1> -->
                    <img src="{{asset('assets/sipenahorz.png')}}" alt="Logo">
                </a>
                <button class="navbar-toggler rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="{{ url('/') }}" class="nav-item nav-link active text-dark">Beranda</a>
                        <a href="{{ url('/') }}" class="nav-item nav-link text-dark">Tentang</a>
                        <a href="{{ url('/') }}" class="nav-item nav-link text-dark">Fitur</a>
                        <a href="{{ url('/') }}" class="nav-item nav-link text-dark">Overview</a>
                        <!-- <a href="#contact" class="nav-item nav-link">Contact</a> -->
                    </div>
                    <a href="{{ route('registerWalimurid') }}" class="btn btn-light rounded-pill py-2 px-4 ms-3 d-none d-lg-block">Info PPDB</a>
                    <a href="{{ route('logindashboard') }}" class="btn btn-light rounded-pill py-2 px-4 ms-3 d-none d-lg-block">Login Dashboard</a>

                </div>
            </nav>

            
        </div>
        <!-- Contact Start -->
        <div class="container-xxl py-6" id="contact" style="background-color:F2F2F2">
            <div class="container mt-5">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                        <h1 class="mb-3">Registrasi Wali Murid</h1>
                        <p class="mb-4">Jika anda ingin mendaftarkan anak anda untuk menjadi calon siswa / siswi maka diwajibkan untuk registrasi membuat akun wali murid terlebih dahulu. Jika sudah mempunyai akun silahkan LOGIN 
                            .</p>
                            <a href="{{ route('loginWalimurid') }}">Login Disini</a>
                        <!-- <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-phone-alt"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-2">Call Us</p>
                                <h5 class="mb-0">+012 345 6789</h5>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-2">Mail Us</p>
                                <h5 class="mb-0">info@example.com</h5>
                            </div>
                        </div>
                        <div class="d-flex mb-0">
                            <div class="flex-shrink-0 btn-square rounded-circle bg-primary text-white">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-2">Our Office</p>
                                <h5 class="mb-0">123 Street, New York, USA</h5>
                            </div>
                        </div> -->
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                    <!-- <div class="table_modal"> -->
                        <form action="{{url('/ppdb-register-simpan')}}" method="POST" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
            <!-- {{ csrf_field() }} -->
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Your Name">
                                        <label for="name">Username</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Your Email">
                                        <label for="email">Password</label>
                                    </div>
                                </div>
                                <!-- <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Your Email">
                                        <label for="email">Email</label>
                                    </div>
                                </div> -->
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Subject">
                                        <label for="subject">Nama Lengkap</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="">
                                    <select class="form-control jk bg-white" id="jk" name="jk">
                <option value="" selected>- Pilih Jenis Kelamin-</option>
                <option value="L"> Laki-Laki </option>
                <option value="P"> Perempuan </option>
              </select>
                                                      
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Subject">
                                        <label for="subject">Tempat Lahir</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="Subject">
                                        <label for="subject">Tanggal Lahir</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Subject">
                                        <label for="subject">No Hp</label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Leave a message here" id="alamat" name="alamat" style="height: 150px"></textarea>
                                        <label for="message">Alamat</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="file" class="form-control bg-white form-control-sm profil_picture" name="image" accept="image/*">
                                        <label for="subject">Foto Profil</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary rounded-pill py-3 px-5" type="submit" id="simpan">Registrasi</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->
        <!-- Navbar & Hero End -->
                <!-- Footer Start -->
        <div class="container-fluid bg-primary footer text-white wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5 px-lg-5">
                <div class="row g-5">
                    <div class="col-md-4 col-lg-4 mt-3">
                        <p class="section-title  h5">
                          <!-- Address<span></span> -->
                    <img src="{{asset('assets/sipenahorz.png')}}" class="mt-0" alt="Logo" width="50%">

                        </p>
                        <p>Sipena merupakan aplikasi berbasis website dan aplikasi android yang dibuat oleh perusahaan FiveCods. dan Star7 yang dapat memudahkan pengguna untuk mengakses segala kebutuhan yang ada dalam lingkungan  sekolah.</p>
                        <!-- <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div> -->
                    </div>
                    <div class="col-md-4 col-lg-4 offset-lg-1">
                        <p class="section-title text-white h5 mb-4">Quick Link<span></span></p>
                        <a class="btn btn-link text-white" href="">PPDB</a>
                        <a class="btn btn-link text-white" href="">Tentang</a>
                        <a class="btn btn-link text-white" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white" href="">Terms & Conditions</a>
                    </div>
                    <div class="col-md-3 col-lg-3">
                      <p class="section-title text-white h5 mb-4">Alamat<span></span></p>
                      <p><i class="fa fa-map-marker-alt me-3"></i>Jl. Ketintang, Ketintang, Kec. Gayungan, Kota Surabaya, Jawa Timur 60231</p>
                      <p><i class="fa fa-phone-alt me-3"></i>(031) 8280009</p>
                      <!-- <p><i class="fa fa-envelope me-3"></i>info@example.com</p> -->
                      <div class="d-flex pt-2">
                          <a class="btn btn-outline-light btn-social text-white" href=""><i class="fab fa-twitter"></i></a>
                          <a class="btn btn-outline-light btn-social text-white" href=""><i class="fab fa-facebook-f"></i></a>
                          <a class="btn btn-outline-light btn-social text-white" href=""><i class="fab fa-instagram"></i></a>
                          <a class="btn btn-outline-light btn-social text-white" href=""><i class="fab fa-linkedin-in"></i></a>
                      </div>
                  </div>

                    <!-- <div class="col-md-6 col-lg-3">
                        <p class="section-title text-white h5 mb-4">Newsletter<span></span></p>
                        <p>Lorem ipsum dolor sit amet elit. Phasellus nec pretium mi. Curabitur facilisis ornare velit non vulpu</p>
                        <div class="position-relative w-100 mt-3">
                            <input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Your Email" style="height: 48px;">
                            <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
                        </div>
                    </div> -->
                </div>
            </div>
          <div class="container px-lg-5 bg-primary">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-12 text-center text-white text-md-center mb-3 mb-md-0">
                            <!-- &copy; <a class="border-bottom" href="#">SIPENA</a>, All Right Reserved.  -->
							
							Designed By <a class="text-white" href="">FiveCods & Star7</a>
                           
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        @section('extra_script')
<script>

baseUrlChange += "/admin/wali-murid";

// $('#simpan').click(function(){

// var formdata = new FormData();
// formdata.append('image', $('.uploadGambar')[0].files[0]);

// $.ajax({
//   type: "post",
//   url: baseUrlChange + '/ppdb-register-simpan='+"{{csrf_token()}}&"+$('.table_modal :input').serialize(),
//   data: formdata,
//   processData: false, //important
//   contentType: false,
//   cache: false,
//   success:function(data){
//     if (data.status == 1) {
//       iziToast.success({
//           icon: 'fa fa-save',
//           message: 'Data Berhasil Disimpan!',
//       });
//       reloadall();
//     }else if(data.status == 2){
//       iziToast.warning({
//           icon: 'fa fa-info',
//           message: 'Data Gagal Disimpan, Silahkan cek koneksi internet anda',
//       });
//     }else if (data.status == 3){
//       iziToast.success({
//           icon: 'fa fa-save',
//           message: 'Data Berhasil di Perbarui ! !',
//       });
//       reloadall();
//     }else if (data.status == 4){
//       iziToast.warning({
//           icon: 'fa fa-info',
//           message: 'Data Gagal di Perbarui !!',
//       });
//     } else if (data.status == 7) {
//       iziToast.warning({
//           icon: 'fa fa-info',
//           message: data.message,
//       });
//     }

//   }
// });
// })
// function reloadall() {
//       $('.table_modal :input').val("");
//       $('.image-holder').empty();
//       $('#tambah').modal('hide');
//       $('.role').val('').change();
//       $('.gender').val('').change();
//       // // $('#table_modal :input').val('');
//       // $(".inputtext").val("");
//       // var table1 = $('#table_modal').DataTable();
//       // table1.ajax.reload();
//       table.ajax.reload();
//     }
  if("{{Session::has('success')}}"){
    iziToast.success({
  icon: 'fa fa-save',
  message: "{{Session::get('success')}}",
});
}
</script>
@endsection
        <!-- Footer End -->
        <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('resources/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('resources/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('resources/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('resources/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('resources/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('resources/js/main.js') }}"></script>


    <!-- <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script> -->

    <!-- Template Javascript -->
    <!-- <script src="js/main.js"></script> -->
</body>

</html>