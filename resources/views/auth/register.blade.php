<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <title>DompetQu</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--===============================================================================================-->
    <link rel="shortcut icon" href="{{asset('assets/dompetqu.png')}}" />
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/vendor/bootstrap/css/bootstrap.min.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/fonts/iconic/css/material-design-iconic-font.min.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/vendor/animate/animate.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/vendor/css-hamburgers/hamburgers.min.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/vendor/animsition/css/animsition.min.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/vendor/select2/select2.min.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/vendor/daterangepicker/daterangepicker.css')}}">
  <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/login-v3/css/main.css')}}">
  <!--===============================================================================================-->
    <style type="text/css">
      .merah{
        background: red;
      }
    </style>
  </head>
  <body>
    <div class="limiter">
      <div class="container-login100" style="background-image: url('assets/login-v3/images/bg-01.jpg');">
        <div class="wrap-login100" style="width: 600px;">
          <form class="login100-form validate-form" autocomplete="off" method="GET" action="{{ url('doregister') }}">
            {{ csrf_field() }}
           <!--  <span class="login100-form-logo">
              <i class="zmdi zmdi-landscape"></i>
            </span> -->
            {{-- <div class="login100-form-title p-b-34 p-t-27"> --}}
              {{-- <img src="{{asset('assets/atonergi.png')}}" width="256px" height="64px"> --}}
                {{-- <h2>DompetQu</h2>
            </div> --}}

            <span class="login100-form-title p-b-34 p-t-27">
              DompetQu
            </span>

            @if (session('sukses'))
            <div class="alert alert-success" role="alert">
              <a href="{{url('/verification')}}/{{encrypt($id)}}">Success, Akun anda sudah terdaftar, Klik disini untuk verifikasi, Kode Verifikasi dikirim melalui email anda.</a>
            </div>
            @endif

            @if (session('gagal'))
            <div class="alert alert-danger" role="alert">
              Gagal, Akun anda gagal terdaftar, terjadi kesalahan, silahkan cek ulang data diri anda, dan coba lagi.
            </div>
            @endif

            <div class="wrap-input100 validate-input" data-validate = "Enter Fullname">
              <input required="" class="input100" autocomplete="off" value="" type="text" name="fullname" id="fullname" placeholder="Fullname (Contoh : Kevin Tanes Cahyani)" >
              {{-- <span class="focus-input100" data-placeholder="&#xf0e0;"></span> --}}
              {{-- @if (session('username'))
                <div class="red"  style="color: red"><b>Fullname </b></div>
              @endif --}}
            </div>

            <div class="wrap-input100 validate-input" data-validate="Enter Email">
              <input required="" class="input100" autocomplete="off" value="" type="email" name="email" id="email" placeholder="Email (Contoh : ferdyp73@gmail.com)">
              {{-- <span class="focus-input100" data-placeholder="&#xf207;"></span> --}}
              @if (session('email'))
              <div class="red"  style="color: red"><b>Email sudah terdaftar</b></div>
              @endif
              {{-- @if (session('email'))
              <div class="red"  style="color: red"><b>Masukkan email yang benar</b></div>
              @endif --}}
            </div>

            <div class="wrap-input100 validate-input" data-validate="Enter No HP">
              <input required="" class="input100" autocomplete="off" value="" type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==13) return false;" name="nohp" id="nohp" placeholder="No HP (Contoh : 0895355153024)">
              {{-- <span class="focus-input100" data-placeholder="&#xf207;"></span> --}}
              @if (session('nohp'))
              <div class="red"  style="color: red"><b>No Hp sudah terdaftar</b></div>
              @endif
            </div>

            <div class="wrap-input100 validate-input" data-validate = "Enter Username">
              <input required="" class="input100" autocomplete="off" value="" type="text" name="username" id="username" placeholder="Usename (Contoh : kevin123)" >
              {{-- <span class="focus-input100" data-placeholder="&#xf0e0;"></span> --}}
              {{-- @if (session('username'))
                <div class="red"  style="color: red"><b>Fullname </b></div>
              @endif --}}
              @if (session('username'))
              <div class="red"  style="color: red"><b> Username sudah digunakan! </b></div>
              @endif
            </div>

            <div class="wrap-input100 validate-input" data-validate="Enter Password">
              <input required="" class="input100" autocomplete="off" value="" type="password" pattern=".{8,}" required title="8 characters minimum" name="password" id="password" placeholder="Password">
              {{-- <span class="focus-input100" data-placeholder="&#xf191;"></span> --}}
              {{-- @if (session('password'))
              <div class="red"  style="color: red"><b>Password min 8 digit</b></div>
              @endif --}}
            </div>

            <div class="wrap-input100 validate-input" data-validate="Enter Confirm Password">
              <input required="" class="input100" autocomplete="off" value="" type="password" pattern=".{8,}" required title="8 characters minimum" name="confirmpass" id="confirmpass" placeholder="Confirm Password">
              {{-- <span class="focus-input100" data-placeholder="&#xf191;"></span> --}}
              @if (session('password'))
              <div class="red"  style="color: red"><b> Password confirm anda tidak sama! </b></div>
              @endif
            </div>

            {{-- <div class="text-center p-t-90">
               <a class="txt1" href="#">
                 Forgot Password?
               </a>
             </div> --}}
             <div class="contact100-form-checkbox" style="text-align:justify; display:flex; flex-direction:column; align-items:center; justify-content:center;">
               {{-- <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
               <label class="label-checkbox100" for="ckb1"> --}}
               <input class="input-checkbox100" id="ckb1" type="checkbox" value="Y" name="term">
               <label class="label-checkbox100" for="ckb1">
                I agree to all Terms & Conditions
               </label>

               @if (session('term'))
               <div class="red"  style="color: red"><b> Ceklis all Terms & Conditions, untuk melanjutkan pendaftaran </b></div>
               @endif
             </div>

            <div class="container-login100-form-btn">
              <button type="submit" class="login100-form-btn" style="width: 300px;">
                Register
              </button>
            </div>

            <br>
            <div class="container-login100-form-btn">
              <a href="{{url('/')}}" class="login100-form-btn" style="width: 300px;">Back to login</a>
            </div>


          </form>
        </div>
      </div>
    </div>


    <!-- container-scroller -->
    <!-- plugins:js -->
    {{-- <script src="../../assets/vendors/js/vendor.bundle.base.js"></script> --}}
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('assets/node_modules/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('assets/js/misc.js')}}"></script>

    <!-- endinject -->
  </body>
</html>
