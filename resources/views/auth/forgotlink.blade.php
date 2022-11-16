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

      input[type="email"] {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="limiter">
      <div class="container-login100" style="background-image: url('../../assets/login-v3/images/bg-01.jpg');">
        <div class="wrap-login100" style="width: 600px;">
          <form class="login100-form validate-form" autocomplete="off" method="GET" action="{{ url('doforgotlink') }}">
            {{ csrf_field() }}
           <!--  <span class="login100-form-logo">
              <i class="zmdi zmdi-landscape"></i>
            </span> -->
            {{-- <div class="login100-form-title p-b-34 p-t-27"> --}}
              {{-- <img src="{{asset('assets/atonergi.png')}}" width="256px" height="64px"> --}}
                {{-- <h2>DompetQu</h2>
            </div> --}}

            <span class="login100-form-title p-b-34 p-t-27">
              Forgot Password
            </span>

            @if (session('sukses'))
            <div class="alert alert-success" role="alert">
              <a href="{{url('/forgotlogin')}}/{{encrypt($id)}}">Success, Password Berhasil diperbarui, klik disini untuk login aplikasi</a>
            </div>
            @endif

            @if (session('gagal'))
            <div class="alert alert-danger" role="alert">
              Gagal, Password gagal diperbarui
            </div>
            @endif


            <div class="wrap-input100 validate-input" data-validate="Enter New Password">
              <input required="" class="input100" autocomplete="off" value="" type="password" pattern=".{8,}" required title="8 characters minimum" name="password" id="password" placeholder="New Password">
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

            <input type="hidden" name="id" value="{{encrypt($id)}}">

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
