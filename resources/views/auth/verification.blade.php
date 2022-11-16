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

      input[type="number"] {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="limiter">
      <div class="container-login100" style="background-image: url('../assets/login-v3/images/bg-01.jpg');">
        <div class="wrap-login100" style="width: 600px;">
          <form class="login100-form validate-form" autocomplete="off" method="GET" action="{{ url('doverification') }}">
            {{ csrf_field() }}
           <!--  <span class="login100-form-logo">
              <i class="zmdi zmdi-landscape"></i>
            </span> -->
            {{-- <div class="login100-form-title p-b-34 p-t-27"> --}}
              {{-- <img src="{{asset('assets/atonergi.png')}}" width="256px" height="64px"> --}}
                {{-- <h2>DompetQu</h2>
            </div> --}}

            <span class="login100-form-title p-b-34 p-t-27">
              Verification
            </span>

            {{-- @if (session('sukses'))
            <div class="alert alert-success" role="alert">
              <a href="#">Success, Akun anda sudah terdaftar, Klik disini untuk verifikasi, Kode Verifikasi dikirim melalui email anda.</a>
            </div>
            @endif --}}

            @if (session('gagal'))
            <div class="alert alert-danger" role="alert">
              Gagal Kirim ulang code , Terjadi kesalahan,
            </div>
            @endif

            <div class="wrap-input100 validate-input" data-validate="Enter Code Verification">
              <input required="" class="input100" autocomplete="off" style="padding: 0 0px 0 0px;" value="" type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==6) return false;" name="code" id="code" placeholder="Enter Verification Code">
              {{-- <span class="focus-input100" data-placeholder="&#xf207;"></span> --}}
              @if (session('verification'))
              <div class="red"  style="color: red"><b>Code Verification Salah!</b></div>
              @endif
            </div>

            <input type="hidden" name="id" value="{{encrypt($id)}}">

            <div class="contact100-form-checkbox text-center">
              {{-- <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember">
              <label class="label-checkbox100" for="ckb1"> --}}
                Belom terima code verification melalui email?, <a href="{{url('/resendverification')}}/{{encrypt($id)}}" style="color: white;">Resend Code</a>
              </label>
            </div>

            <div class="container-login100-form-btn">
              <button type="submit" class="login100-form-btn" style="width: 300px;">
                Submit
              </button>
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
