@extends('layouts.homepage.app_home')

@section('content')

			<!-- mt main slider start here -->
			<div class="mt-main-slider">
				<!-- slider banner-slider start here -->
				<div class="slider banner-slider">
					<!-- holder start here -->
					<div class="holder text-center" style="background-image: url({{url('/')}}/{{$backgroundheader->image}}); height:60rem; ">
						<div class="container">
							<div class="row">
								<div class="col-xs-12">
									<div class="text centerize">
										{{-- <strong class="title">FURNITURE DESIGNS IDEAS</strong>
										<h1>LIGHTING</h1>
										<h2>PENDANT LAMPS</h2>
										<div class="txt">
											<p>Consectetur adipisicing elit. Beatae accusamus, optio, repellendus inventore</p>
										</div>
										<a href="product-detail.html" class="shop">shop now</a> --}}
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- holder end here -->

					<!-- holder start here -->
					{{-- <div class="holder text-center" style="background-image: url(http://placehold.it/1920x585);">
						<div class="container">
							<div class="row">
								<div class="col-xs-12">
									<div class="text right">
										<strong class="title">FURNITURE DESIGNS IDEAS</strong>
										<h1>LOUNGE CHAIRS</h1>
										<h2>SW DAYBED</h2>
										<div class="txt">
											<p>Consectetur adipisicing elit. Beatae accusamus, optio, repellendus inventore</p>
										</div>
										<a href="product-detail.html" class="shop">shop now</a>
									</div>
								</div>
							</div>
						</div>
					</div> --}}
					<!-- holder end here -->

					<!-- holder start here -->
					{{-- <div class="holder text-center" style="background-image: url(http://placehold.it/1920x585);">
						<div class="container">
							<div class="row">
								<div class="col-xs-12">
									<div class="text">
										<strong class="title">FURNITURE DESIGNS IDEAS</strong>
										<h1>CARDBOARD</h1>
										<h2> Sofas and Armchairs</h2>
										<div class="txt">
											<p>Consectetur adipisicing elit. Beatae accusamus, optio, repellendus inventore</p>
										</div>
										<a href="product-detail.html" class="shop">shop now</a>
									</div>
								</div>
							</div>
						</div>
					</div> --}}
					<!-- holder end here -->
				</div>
				<!-- slider regular end here -->
			</div><!-- mt main slider end here -->
			<!-- mt main start here -->
			<main id="mt-main">
				<div class="container">
					<div class="row">
						<div class="col-xs-12">

							<!-- mt producttabs start here -->
							<div class="mt-producttabs wow fadeInUp" data-wow-delay="0.4s">
								<!-- producttabs start here -->
								<ul class="producttabs">
									<li><a href="#tab1" class="active">LATEST PRODUCT</a></li>
									<li><a href="#tab2">LATEST AUCTION</a></li>
								</ul>
								<!-- producttabs end here -->
								<div class="tab-content text-center">
									<div id="tab1">
										<!-- tabs slider start here -->
										<div class="tabs-slider">
											<!-- slide start here -->
											@foreach ($latest as $key => $value)
											<div class="slide">
												<!-- mt product1 center start here -->

													<div class="mt-product1 mt-paddingbottom20">
														<div class="box">
															<div class="b1">
																<div class="b2">
																	<a href="{{route('detailproduct', $value->url_segment)}}"><img src="{{url('/')}}/{{$value->image}}" class="imageproduk" style="width:215px; height:215px" alt="{{$value->name}}"></a>
																	<span class="caption">
																		@if ($value->isdiskon == "Y")
																			<span class="off">{{$value->diskon}}% off</span>
																		@endif
																		<span class="new">NEW</span>
																	</span>
																	<ul class="links">
																		<li><a onclick="addtocard({{$value->id_produk}})"><i class="icon-handbag"></i><span>Add to Cart</span></a></li>
																	</ul>
																</div>
															</div>
														</div>
														<div class="txt">
															<strong class="title"><a href="{{route('detailproduct', $value->url_segment)}}">{{$value->name}}</a></strong>
															@if ($value->isdiskon == "Y")
																<?php
																$diskonval = ($value->diskon/100)*$value->price;
																$diskonval = $value->price * $value->diskon / 100;
																$res = $value->price - $diskonval;
																?>
																<span class="price">{{FormatRupiahFront($res)}}</span> <del>{{$value->price}}</del>
															@else
																<span class="price">{{FormatRupiahFront($value->price)}}</span>
														  @endif
															<ul class="mt-stars">
																@for ($i=0; $i < $value->starproduk; $i++)
																	<li><i class="fa fa-star"></i></li>
																@endfor
																@for ($i=0; $i < (5 - $value->starproduk); $i++)
																	<li><i class="fa fa-star-o"></i></li>
																@endfor
															</ul>
															<?php
															$string = $value->address;
															$output = explode(" ",$string);
															?>
															<strong class="title"><span class="fa fa-map-marker"></span> {{end($output)}}</strong>
															<strong class="title"><a href="{{url('/')}}/toko/{{$value->id_account}}"> <span class="fa fa-store"></span> {{$value->namatoko}}</a></strong>


														</div>
													</div>


												<!-- mt product1 center end here -->
											</div>
											@endforeach
											<!-- slide end here -->
										</div>
										<!-- tabs slider end here -->
									</div>
									<div id="tab2">
										<!-- tabs slider start here -->
										<div class="tabs-slider">
											@foreach ($forauction as $key => $value)
											<div class="slide">
												<!-- mt product1 center start here -->

													<div class="mt-product1 mt-paddingbottom20">
														<div class="box">
															<div class="b1">
																<div class="b2">
																	<a href="{{route('detaillelang', $value->url_segment)}}"><img src="{{url('/')}}/{{$value->image}}" class="imageproduk" style="width:215px; height:215px" alt="{{$value->name}}"></a>
																	<span class="caption">
																		<span class="new">AUCTION</span>
																	</span>
																</div>
															</div>
														</div>
														<div class="txt">
															<strong class="title"><a href="product-detail.html">{{$value->name}}</a></strong>
															<span class="price" id="lelang{{$value->id_lelang}}">{{FormatRupiahFront($value->price)}}</span>
															<ul class="mt-stars">
																@for ($i=0; $i < $value->starproduk; $i++)
																	<li><i class="fa fa-star"></i></li>
																@endfor
																@for ($i=0; $i < (5 - $value->starproduk); $i++)
																	<li><i class="fa fa-star-o"></i></li>
																@endfor
															</ul>
															<?php
															$string = $value->address;
															$output = explode(" ",$string);
															?>
															<strong class="title"><a href="product-detail.html"> <span class="fa fa-map-marker"></span> {{end($output)}}</a></strong>
															<strong class="title"><a href="{{url('/')}}/toko/{{$value->id_account}}"> <span class="fa fa-store"></span> {{$value->namatoko}}</a></strong>

														</div>
													</div>


												<!-- mt product1 center end here -->
											</div>
											@endforeach
										</div>
										<!-- tabs slider end here -->
									</div>

								</div>
							</div>
							<!-- mt producttabs end here -->
						</div>
					</div>
				</div>
				<!-- mt bestseller start here -->
				<div class="mt-bestseller bg-grey text-center wow fadeInUp" data-wow-delay="0.4s">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 mt-heading text-uppercase">
								<h2 class="heading">PROMO</h2>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="bestseller-slider">
									@foreach ($promo as $key => $value)
										<div class="slide">
											<!-- mt product1 center start here -->
											<div class="mt-product1 large">
												<div class="box">
													<div class="b1">
														<div class="b2">
															<a href="{{route('detailproduct', $value->url_segment)}}"><img src="{{url('/')}}/{{$value->image}}" class="imageproduk" style="width:275px; height:285px" alt="{{$value->name}}"></a>
															<span class="caption">
																@if ($value->isdiskon == "Y")
																	<span class="off">{{$value->diskon}}% off</span>
																@endif
															</span>
															<ul class="links">
																<li><a onclick="addtocard({{$value->id_produk}})"><i class="icon-handbag"></i><span>Add to Cart</span></a></li>
															</ul>
														</div>
													</div>
												</div>
												<div class="txt">
													<strong class="title"><a href="{{route('detailproduct', $value->url_segment)}}">{{$value->name}}</a></strong>
													<span class="price">{{FormatRupiahFront($value->price)}}</span>
													<ul class="mt-stars">
														@for ($i=0; $i < $value->starproduk; $i++)
															<li><i class="fa fa-star"></i></li>
														@endfor
														@for ($i=0; $i < (5 - $value->starproduk); $i++)
															<li><i class="fa fa-star-o"></i></li>
														@endfor
													</ul>
													<?php
													$string = $value->address;
													$output = explode(" ",$string);
													?>
													<strong class="title"> <span class="fa fa-map-marker"></span> {{end($output)}}</strong>
													<strong class="title"><a href="{{url('/')}}/toko/{{$value->id_account}}"> <span class="fa fa-store"></span> {{$value->namatoko}}</a></strong>

											</div><!-- mt product1 center end here -->
										</div>
										</div>
									@endforeach



							</div>
						</div>

					</div>
				</div>
			</div>

			</main><!-- mt main end here -->

@endsection

@section('extra_script')
<script type="text/javascript">

		let datalelang = {!! json_encode($forauction) !!};

		if (datalelang.length != 0) {
			setInterval(function(){ reloadlelang(); }, 5000);
		}

		function reloadlelang() {
				var arrid = [];

				for (var i = 0; i < datalelang.length; i++) {
					 arrid[i] = datalelang[i].id_lelang;
				}

		    $.ajax({
		      url:"{{url('/')}}" + '/lelangupdate',
		      data:{arrid},
		      dataType:'json',
		      success:function(response){
						for (var i = 0; i < response.length; i++) {
							$('#lelang'+response[i].id_lelang).text("Rp. " + accounting.formatMoney(response[i].price,"",0,'.',','))
						}
		      }
		    });
		}

</script>

@endsection
