<!DOCTYPE html>
<html lang="en">

<head>
	<!-- set the encoding of your site -->
	<meta charset="utf-8">
	<!-- set the viewport width and initial-scale on mobile devices -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sipena</title>

	<link rel="shortcut icon" href="{{asset('assets/iwak.jpeg')}}">
	<!-- include the site stylesheet -->
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,200italic,300,300italic,400italic,600,600italic,700,700italic,900,900italic%7cMontserrat:400,700%7cOxygen:400,300,700' rel='stylesheet' type='text/css'>
	<!-- include the site stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.css')}}">

	<link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- include the site stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/css/animate.css')}}">
	<!-- include the site stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/css/icon-fonts.css')}}">
	<!-- include the site stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
	<!-- include the site stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">

	<link rel="stylesheet" href="{{asset('assets/node_modules/izitoast/dist/css/iziToast.min.css')}}">

	<style media="screen">
		.imageproduk {
			border-radius: 20px;
		}
	</style>

</head>
@if (session('password') || $errors->any())

<body class="side-col-active">
	@else

	<body>
		@endif
		<!-- main container of all the page elements -->
		<div id="wrapper">
			<!-- Page Loader -->
			<div id="pre-loader" class="loader-container">
				<div class="loader">
					<img src="{{asset('assets/images/rings.svg')}}" alt="loader">
				</div>
			</div>
			<!-- W1 start here -->
			<div class="w1">
				<!-- mt header style4 start here -->
				<header id="mt-header" class="style17">
					<!-- mt top bar start here -->
					@if(Auth::check())
					<!-- mt top bar start here -->
					<div class="mt-top-bar">
						<div class="container">
							<div class="row">
								<div class="col-xs-12 col-sm-6 hidden-xs">
									{{-- <a href="mailto:webmaster@example.com" class="tel mailto"> <i aria-hidden="true" class="fa fa-envelope-o"></i> <span class="mailtotext"> info@schon.chairs <span> </a> --}}
								</div>
								<div class="col-xs-12 col-sm-6 text-right">
									<!-- mt top lang start here -->
									<div class="mt-top-lang">

										<a href="#" class="lang-opener text-capitalize" style="font-weight: bold;"> {{Auth::user()->fullname}} <i class="fa fa-angle-down" aria-hidden="true"></i></a>
										<div class="drop" style="width:100px; font-size:12px">
											<ul>
												<li style="padding-top: 5px; padding-bottom: 5px"><a href="{{url('/pembeli/profile')}}">My Account</a></li>
												<li style="padding-top: 5px; padding-bottom: 5px"><a href="{{ url('/logoutmember') }}">Sign Out</a></li>
											</ul>
										</div>
									</div><!-- mt top lang end here -->
									<span class="account">
										<a href="{{url('/pembeli/history')}}">History</a>
									</span>
								</div>
							</div>
						</div>
					</div><!-- mt top bar end here -->
					@endif
					<!-- mt top bar start here -->
					<!-- mt bottom bar start here -->
					<div class="mt-bottom-bar">
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-12">
									<!-- mt logo start here -->
									<div class="mt-logo"><a href="{{url('/')}}">
											<b>
												<h4 style="color:#F0C441;">Sipena</h4>
											</b>
											<!-- <img src="images/mt-logo.png" alt="schon"> -->
										</a></div>
									<!-- mt icon list start here -->
									<ul class="mt-icon-list">
										<li class="hidden-lg hidden-md">
											<a href="#" class="bar-opener mobile-toggle">
												<span class="bar"></span>
												<span class="bar small"></span>
												<span class="bar"></span>
											</a>
										</li>

										@if (Auth::check())
										<li class="drop">
											<a class="cart-opener" onclick="redirectchat()">
												<span class="icon-bubble"></span>
												<span class="num numchat">0</span>
											</a>
										</li>
										@endif

										@if (Auth::check())
										@if (Auth::user()->namatoko == null)
										<li class="drop">
											<a class="cart-opener" onclick="opentoko()" data-placement="bottom" data-toggle="tooltip" title="Manage Your Shop?">
												<span class="fa fa-store"></span>
											</a>
										</li>
										@else
										<li class="drop">
											<a class="cart-opener" onclick="location.href = '{{url('/penjual/home')}}';" data-placement="bottom" data-toggle="tooltip" title="Manage Your Shop">
												<span class="fa fa-store"></span>
												<span class="num numchat" id="countnotif">0</span>
											</a>
										</li>
										@endif
										@endif

										@if (Auth::check())
										<li class="drop">
											<a class="cart-opener" onclick="opencart()">
												<span class="icon-handbag"></span>
												<span class="num numcart">0</span>
											</a>

											<!-- mt drop start here -->
											<div class="mt-drop">
												<!-- mt drop sub start here -->
												<div class="mt-drop-sub">
													<!-- mt side widget start here -->
													<div class="mt-side-widget carditem">
														<!-- cart row start here -->

														{{-- <center> <img src="{{ asset('assets/demo/images/loading.gif') }}" style="height: 50px; width: 50px;" class="img-responsive"> </center> --}}

														<!-- cart row end here -->
														<!-- cart row start here -->
														{{-- <div class="cart-row">
														<a href="#" class="img"><img src="http://placehold.it/75x75" alt="image" class="img-responsive"></a>
														<div class="mt-h">
															<span class="mt-h-title"><a href="#">Marvelous Modern 3 Seater</a></span>
															<span class="price"><i class="fa fa-eur" aria-hidden="true"></i> 599,00</span>
															<span class="mt-h-title">Qty: 1</span>
														</div>
														<a href="#" class="close fa fa-times"></a>
													</div><!-- cart row end here -->
													<!-- cart row start here -->
													<div class="cart-row">
														<a href="#" class="img"><img src="http://placehold.it/75x75" alt="image" class="img-responsive"></a>
														<div class="mt-h">
															<span class="mt-h-title"><a href="#">Marvelous Modern 3 Seater</a></span>
															<span class="price"><i class="fa fa-eur" aria-hidden="true"></i> 599,00</span>
															<span class="mt-h-title">Qty: 1</span>
														</div>
														<a href="#" class="close fa fa-times"></a>
													</div><!-- cart row end here --> --}}
														<!-- cart row total start here -->
														{{-- <div class="cart-row-total">
														<span class="mt-total">Sub Total</span>
														<span class="mt-total-txt"><i class="fa fa-eur" aria-hidden="true"></i> 799,00</span>
													</div> --}}
														<!-- cart row total end here -->
														{{-- <div class="cart-btn-row">
														<a href="#" class="btn-type2">VIEW CART</a>
														<a href="#" class="btn-type3">CHECKOUT</a>
													</div> --}}
													</div><!-- mt side widget end here -->
												</div>
												<!-- mt drop sub end here -->
											</div><!-- mt drop end here -->
											<span class="mt-mdropover"></span>
										</li>
										@endif
										@if(Auth::check() == NULL)
										<li>
											@if (session('password') || $errors->any())
											<a href="#" class="side-opener active">
												@else
												<a href="#" class="side-opener">
													@endif
													<i class="icon-user"></i>
													<!-- <span class="bar"></span>
											<span class="bar small"></span>
											<span class="bar"></span> -->
												</a>
										</li>
										@endif
									</ul><!-- mt icon list end here -->
									<!-- navigation start here -->
									<nav id="nav" style="background-color: #043C87;">
										<ul>
											<li>
												<a href="{{url('/')}}">HOME</a>
											</li>
											<li>
												<a href="{{ url('/product') }}">For Sale</a>
											</li>
											<li>
												<a href="{{ url('/lelang') }}">For Auction</a>
											</li>
											{{-- <li>
											<a href="{{ url('contact') }}">Contact </a>

											</li> --}}
										</ul>
									</nav>
									<!-- mt icon list end here -->
								</div>
							</div>
						</div>
					</div>
					<!-- mt bottom bar end here -->
					@if (session('password') || $errors->any())
					<span class="mt-side-over active"></span>
					@else
					<span class="mt-side-over"></span>
					@endif
				</header>
				<!-- mt side menu start here -->
				<div class="mt-side-menu">
					<!-- mt holder start here -->
					<div class="mt-holder">
						<a href="#" class="side-close"><span></span><span></span></a>
						<strong class="mt-side-title">MY ACCOUNTS</strong>
						<!-- mt side widget start here -->
						<div class="mt-side-widget">
							<header>
								<span class="mt-side-subtitle">SIGN IN</span>
								<p>Welcome back! Sign in to Your Account</p>
							</header>
							<form class="" autocomplete="off" method="GET" action="{{ url('loginmember') }}">
								{{ csrf_field() }}
								<fieldset>
									<input type="text" placeholder="Email address" class="input" name="username">
									@if (session('username'))
									<div class="red" style="color: red"><b>Email Not Found</b></div>
									@endif
									<input type="password" placeholder="Password" class="input" name="password">
									@if (session('password'))
									<div class="red" style="color: red"><b>Wrong Password</b></div>
									@endif
									<div class="box">
										{{-- <span class="left"><input class="checkbox" type="checkbox" id="check1"><label for="check1">Remember Me</label></span> --}}
										<a onclick="forgotpassword()" style="cursor: pointer;" class="help">Forgot Password?</a>
									</div>
									<button type="submit" class="btn-type1">Sign In</button>
								</fieldset>
							</form>
						</div>
						<!-- mt side widget end here -->
						<div class="or-divider"><span class="txt">or</span></div>
						<!-- mt side widget start here -->
						<div class="mt-side-widget">
							<header>
								<span class="mt-side-subtitle">CREATE NEW ACCOUNT</span>
								<p>Create your very own account</p>
							</header>
							@if ($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
							@endif
							<form autocomplete="off" method="post" action="{{ url('registermember') }}">
								{{ csrf_field() }}

								<fieldset>
									<!-- <input type="text" placeholder="Fullname" class="input" name="fullname"> -->
									<input type="email" placeholder="Email address" class="input" name="email" required>
									<input type="password" placeholder="Password" class="input" name="password" id="password" required>

									<input id="password" class="input" type="password" placeholder="Re Type Password" id="password_confirmation" name="password_confirmation" required>
									<button type="submit" class="btn-type1">Sign Up</button>
								</fieldset>
							</form>
						</div>
						<!-- mt side widget end here -->
					</div>
					<!-- mt holder end here -->
				</div><!-- mt side menu end here -->
				<!-- mt search popup start here -->
				<div class="mt-search-popup">
					<div class="mt-holder">
						<a href="#" class="search-close"><span></span><span></span></a>
						<div class="mt-frame">
							<form action="#">
								<fieldset>
									<input type="text" placeholder="Search...">
									<span class="icon-microphone"></span>
									<button class="icon-magnifier" type="submit"></button>
								</fieldset>
							</form>
						</div>
					</div>
				</div><!-- mt search popup end here -->
				<!-- mt header style4 end here -->

				@yield('content')

				<!-- footer of the Page -->
				<footer id="mt-footer" class="style1 wow fadeInUp" data-wow-delay="0.4s">
					<!-- Footer Holder of the Page -->
					<div class="footer-holder dark">
						<div class="container">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 mt-paddingbottomsm">
									<!-- F Widget About of the Page -->
									<div class="f-widget-about">
										<div class="logo">
											<a href="{{url('/')}}">Sipena</a>
										</div>
										<p class="desctext"></p>

									</div>
									<!-- F Widget About of the Page end -->
								</div>
								{{-- <div class="col-xs-12 col-sm-6 col-md-6 mt-paddingbottomxs">
								<!-- Footer Tabs of the Page -->
								<div class="f-widget-tabs">
									<h3 class="f-widget-heading">Product Tags</h3>
									<ul class="list-unstyled tabs categorylist">
										<li><a href="#">Sofas</a></li>
										<li><a href="#">Armchairs</a></li>
										<li><a href="#">Living</a></li>
										<li><a href="#">Bedroom</a></li>
										<li><a href="#">Lighting</a></li>
										<li><a href="#">Tables</a></li>
										<li><a href="#">Pouf</a></li>
										<li><a href="#">Wood</a></li>
										<li><a href="#">Office</a></li>
										<li><a href="#">Outdoor</a></li>
										<li><a href="#">Kitchen</a></li>
										<li><a href="#">Stools</a></li>
										<li><a href="#">Footstools</a></li>
										<li><a href="#">Desks</a></li>
									</ul>
								</div>
								<!-- Footer Tabs of the Page -->
							</div> --}}
								<div class="col-xs-12 col-sm-6 col-md-6 text-right">
									<!-- F Widget About of the Page -->
									<div class="f-widget-about">
										<h3 class="f-widget-heading">Information</h3>
										<ul class="list-unstyled address-list align-right">
											<li><i class="fa fa-map-marker"></i>
												<address class="addresstext">Connaugt Road Central Suite 18B, 148 </address>
											</li>
											{{-- <li><i class="fa fa-phone"></i><a href="tel:15553332211">+1 (555) 333 22 11</a></li> --}}
											<li><i class="fa fa-envelope-o"></i><a class="mailto" href="mailto:&#105;&#110;&#102;&#111;&#064;&#115;&#099;&#104;&#111;&#110;&#046;&#099;&#104;&#097;&#105;&#114;"><span class="mailtotext"> </span></a></li>
										</ul>
									</div>
									<!-- F Widget About of the Page end -->
								</div>
							</div>
						</div>
					</div>
					<!-- Footer Holder of the Page end -->
					<!-- Footer Area of the Page -->
					<div class="footer-area" style="background-color: #043C87;">
						<div class="container">
							<div class="row">
								<div class="col-xs-12 col-sm-6" style="color: #F0C441">
									<p>© <a href="index.html" style="color: #F0C441">Sipena.</a> - All rights Reserved</p>
								</div>
								{{-- <div class="col-xs-12 col-sm-6 text-right">
								<div class="bank-card">
									<img src="images/bank-card.png" alt="bank-card">
								</div>
							</div> --}}
							</div>
						</div>
					</div>
					<!-- Footer Area of the Page end -->
				</footer><!-- footer of the Page end -->
			</div><!-- W1 end here -->
			<span id="back-top" class="fa fa-arrow-up"></span>
		</div>

		<!-- Modal -->
		<div id="tambah" class="modal fade" role="dialog">
			<div class="modal-dialog modal-xs">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header bg-gradient-info">
						<h4 class="modal-title">Form User</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<table class="table table_modal">
								<tr>
									<td>Fullname</td>
									<td>
										<input type="text" class="form-control form-control-sm inputtext fullname" name="fullname">
										<input type="hidden" class="form-control form-control-sm id" name="id">
									</td>
								</tr>
								<tr>
									<td>Nomor Rekening</td>
									<td>
										<input type="text" class="form-control form-control-sm inputtext nomor_rekening" name="nomor_rekening">
									</td>
								</tr>
								<tr>
									<td>Nama Bank</td>
									<td>
										<input type="text" class="form-control form-control-sm inputtext bank" name="bank">
									</td>
								</tr>
								<tr>
									<td>Email</td>
									<td>
										<input type="email" class="form-control form-control-sm inputtext email" name="email">
									</td>
								</tr>
								<tr>
									<td>Password</td>
									<td>
										<input type="text" class="form-control form-control-sm inputtext password" name="password">
									</td>
								</tr>
								<tr>
									<td>Level</td>
									<td>
										<select class="form-control role" name="role">
											<option value="" selected>- Pilih -</option>
											<option value="admin"> Admin </option>
											<option value="member"> Member </option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Phone</td>
									<td>
										<input type="text" class="form-control form-control-sm inputtext phone" name="phone">
									</td>
								</tr>
								<tr>
									<td>Address</td>
									<td>
										<textarea class="form-control form-control-sm address" name="address" rows="8" cols="80"></textarea>
										<div class="alert alert-warning" role="alert">
											Alamat ini juga akan digunakan untuk alamat toko
										</div>
									</td>
								</tr>
								<tr>
									<td>Gender</td>
									<td>
										<select class="form-control gender" name="gender">
											<option value="" selected>- Pilih -</option>
											<option value="L"> Laki - Laki </option>
											<option value="P"> Perempuan </option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Image</td>
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
							</table>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" id="simpan" type="button">Simpan</button>
							<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
						</div>
					</div>
				</div>

			</div>
		</div>
		@include('modal_toko')
		@include('forgot')

		<!-- include jQuery -->
		<script src="{{asset('assets/js/jquery.js')}}"></script>
		<!-- include jQuery -->
		<script src="{{asset('assets/js/plugins.js')}}"></script>
		<!-- include jQuery -->
		<script src="{{asset('assets/js/jquery.main.js')}}"></script>

		<script src="{{asset('assets/js/sweetalert.js')}}"></script>

		<script rel="stylesheet" src="{{asset('assets/node_modules/izitoast/dist/js/iziToast.min.js')}}"></script>

		<script src="{{asset('assets/js/accounting.min.js')}}"></script>

		<script src="{{asset('assets/js/jquery.maskMoney.js')}}"></script>
		{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js')}}"></script> --}}

		@yield('extra_script')

		<script type="text/javascript">
			$('.rp').maskMoney({
				prefix: 'Rp. ',
				thousands: '.',
				decimal: ',',
				precision: 0
			});

			@if(Auth::check())
			@if(Auth::user() - > role == "admin")
			$.ajax({
				url: "{{url('/')}}" + "/logoutmemberjson",
				success: function(data) {
					window.location.reload();
				},
				async: false
			});
			@endif

			function countcart() {
				$.ajax({
					url: "{{url('/')}}" + "/countcart",
					success: function(data) {
						$('.numcart').text(data);
					},
					async: false
				});
			}

			countcart();

			function addtocard(id) {
				$.ajax({
					url: "{{url('/')}}" + "/addcart",
					data: {
						id
					},
					success: function(data) {
						if (data.status == 1) {
							iziToast.success({
								icon: 'fa fa-save',
								message: 'Product Added To Cart Successfully!',
							});
							let count = $('.numcart').text();

							$('.numcart').text(parseInt(count) + 1);
						} else if (data.status == 2) {
							iziToast.warning({
								icon: 'fa fa-info',
								message: 'Product Failed Added To Cart!, Check your data and connection!',
							});
						} else if (data.status == 3) {
							iziToast.success({
								icon: 'fa fa-save',
								message: 'Product Added To Cart Successfully!',
							});
						} else if (data.status == 4) {
							iziToast.warning({
								icon: 'fa fa-info',
								message: 'Product Failed Added To Cart!',
							});
						} else if (data.status == 7) {
							swal({
								title: 'Do you want to change the cart to another store?',
								type: 'question',
								showCancelButton: true
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.value) {
									$.ajax({
										url: "{{url('/')}}" + "/changetoko",
										data: {
											id
										},
										success: function(data) {
											iziToast.success({
												icon: 'fa fa-save',
												message: 'Product Added To Cart Successfully!',
											});
											let count = $('.numcart').text();

											$('.numcart').text(1);
										}
									});
								} else {}
							})
						}
					}
				});
			}

			function opencart() {
				var html = '<center> <img src="{{ asset('
				assets / demo / images / loading.gif ') }}" style="height: 50px; width: 50px;" class="img-responsive"> </center>';

				$('.carditem').html(html);

				var html = "";

				$.ajax({
					url: "{{url('/')}}" + "/opencart",
					success: function(data) {
						var subtotal = 0;

						for (var i = 0; i < data.length; i++) {
							let cart = data[i];
							subtotal += cart.qty * cart.price;

							html += '<div class="cart-row" id="cartrow' + cart.id_cart + '">' +
								'<a href="#" class="img"><img src="' + "{{url('/')}}" + '/' + cart.image + '" alt="image" style="width:100%; height:66px" class="img-responsive"></a>' +
								'<div class="mt-h">' +
								'<span class="mt-h-title"><a href="#">' + cart.name + '</a></span>' +
								'<span class="price">' + "Rp. " + accounting.formatMoney(cart.price, "", 0, '.', ',') + '</span>' +
								'<span class="mt-h-title">Qty: ' + cart.qty + '</span>' +
								'</div>' +
								'<a style="color:red;" onclick="deletecart(' + cart.id_cart + ')" class="close fa fa-times"></a>' +
								'</div>';
						}

						if (data.length > 0) {
							html += '<div class="cart-row-total">' +
								'<span class="mt-total">Sub Total</span>' +
								'<span class="mt-total-txt">' + "Rp. " + accounting.formatMoney(subtotal, "", 0, '.', ',') + '</span>' +
								'</div>' +
								'<div class="col-xs-12 col-sm-12">' +
								'<a href="' + "{{url('/')}}/viewcart" + '" style="width:100%; text-align:center;" class="btn-type3">VIEW CART</a>' +
								'</div>';
						} else {
							html += '<div class="cart-row-total">' +
								'<span class="mt-total">Sub Total</span>' +
								'<span class="mt-total-txt">' + "Rp. " + accounting.formatMoney(subtotal, "", 0, '.', ',') + '</span>' +
								'</div>' +
								'<div class="col-xs-12 col-sm-12">' +
								'</div>';
						}

						$('.carditem').html(html);

					},
					async: false
				});
			}

			function deletecart(id) {

				$('#cartrow' + id).css('display', 'none');

				$.ajax({
					url: "{{url('/')}}" + "/deletecart",
					data: {
						id
					},
					success: function(data) {
						opencart();
						countcart();
					},
					async: false
				});
			}

			$.ajax({
				url: "{{url('/')}}" + "/countchat",
				success: function(data) {
					$('.numchat').text(data);
				},
				async: false
			});

			setInterval(function() {

				$.ajax({
					url: "{{url('/')}}" + "/countchat",
					success: function(data) {
						$('.numchat').text(data);
					},
					async: false
				});

			}, 3000);

			function redirectchat() {
				window.open("{{url('/')}}/chat", '_blank');
			}

			@else

			function addtocard(id) {
				swal(
					'Please login to add cart',
					'If you dont have an account, please create your account',
					'info'
				)
			}
			@endif

			$(document).ready(function() {
				$('[data-toggle="tooltip"]').tooltip();
			});

			var email = ""
			var address = ""
			var description = ""

			$(document).ready(function() {
				$.ajax({
					url: "{{url('/')}}" + '/getinfo',
					dataType: 'json',
					success: function(data) {
						if (data.info[0].email == null) {
							email = ""
						} else {
							email = data.info[0].email
						}

						if (data.info[0].address == null) {
							address = ""
						} else {
							address = data.info[0].address
						}

						if (data.info[0].description == null) {
							description = ""
						} else {
							description = data.info[0].description
						}

						$('.mailto').attr("href", "mailto:" + email + "");
						$('.mailtotext').text(email);
						$('.desctext').text(description);
						$('.addresstext').text(address);



						var htmlcat = ""
						for (var i = 0; i < data.category.length; i++) {
							var res = data.category[i];

							htmlcat += "<li><a href='" + "{{url('/')}}/product?sort=ASC&sortfield=name&category=" + res.id_category + "'>" + res.category_name + "</a></li>";
						}

						$('.categorylist').html(htmlcat);
					},
					async: false
				});
			});

			function opentoko() {
				swal({
					title: 'Do you want to open your own shop?',
					type: 'question',
					showCancelButton: true
				}).then((result) => {
					console.log(result);
					/* Read more about isConfirmed, isDenied below */
					if (result.value) {
						reloadall()
						$('#modal_toko').modal('show');
					} else {}
				})
			}

			function reloadall() {
				$('.table_modal :input').val("");
				$('.image-holder').empty();
				$('#tambah').modal('hide');
			}

			$(".uploadGambartoko").on('change', function() {
				$('.save').attr('disabled', false);
				// waitingDialog.show();
				if (typeof(FileReader) != "undefined") {
					var image_holder = $(".image-holder");
					image_holder.empty();
					var reader = new FileReader();
					reader.onload = function(e) {
						image_holder.html('<img style="width: 30px; height: 30px;" src="{{ asset('
							assets / demo / images / loading.gif ')}}" class="img-responsive">');
						$('.save').attr('disabled', true);
						setTimeout(function() {
							image_holder.empty();
							$("<img />", {
								"src": e.target.result,
								"class": "thumb-image img-responsive",
								"style": "height: 100px; width:100px; border-radius: 0px;",
							}).appendTo(image_holder);
							$('.save').attr('disabled', false);
						}, 2000)
					}
					image_holder.show();
					reader.readAsDataURL($(this)[0].files[0]);

					// waitingDialog.hide();
				} else {
					// waitingDialog.hide();
					alert("This browser does not support FileReader.");
				}
			});

			@if(Auth::check())
			$('#simpantoko').click(function() {

				var formdata = new FormData();
				formdata.append('image', $('.uploadGambartoko')[0].files[0]);

				$.ajax({
					type: "post",
					url: "{{url('/')}}" + "/admin/toko" + '/simpan?_token=' + "{{csrf_token()}}&" + $('.table_modal :input').serialize() + "&id={{Auth::user()->id_account}}",
					data: formdata,
					processData: false, //important
					contentType: false,
					cache: false,
					success: function(data) {
						$('#modal_toko').modal('hide');
						if (data.status == 1) {
							window.location.href = "{{url('/penjual/home')}}"
						} else {
							swal(
								'Failed to create new shop :(',
								'Check your data again, and please try again later',
								'info'
							)
						}
					}
				});
			})

			var notif = 0
			$.ajax({
				url: "{{url('/')}}" + "/notif",
				success: function(data) {

					$('#countnotif').text(data);
				}
			});

			setInterval(function() {

				$.ajax({
					url: "{{url('/')}}" + "/notif",
					success: function(data) {

						$('#countnotif').text(data);
					}
				});

			}, 3000);

			@endif

			function forgotpassword() {
				$('#modal_forgot').modal('show');


			}

			$('#simpanforgot').click(function() {
				$.ajax({
					type: "get",
					url: "{{url('/')}}" + "/forgot?" + $('.table_modal :input').serialize(),
					processData: false, //important
					contentType: false,
					cache: false,
					success: function(data) {
						$('.table_modal :input').val("");
						$('#modal_forgot').modal('hide');
						if (data.status == 1) {
							swal(
								'Successful Forget Password :)',
								'Congratulations, The password has been successfully updated',
								'success'
							)
						} else {
							swal(
								'Failed Forgot Password :(',
								'Check your data again, and please try again later',
								'info'
							)
						}
					}
				});
			})
		</script>

	</body>

</html>