@extends('main')

@section('content')
<!-- partial -->
<div class="content-wrapper">
    <div class="col-lg-12">
           <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-info text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Sales Turnover</h4>
                  <h2 class="font-weight-normal mb-5" id="jumlahuser"> Rp. {{number_format($omset)}}</h2>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Unpaid Order Amount</h4>
                  <h2 class="font-weight-normal mb-5"> {{number_format($pesananbelomterbayar)}}</h2>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-success text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Order Amount Paid</h4>
                  <h2 class="font-weight-normal mb-5"> {{number_format($pesanansudahterbayar)}} </h2>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-danger text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Number of Undelivered Orders</h4>
                  <h2 class="font-weight-normal mb-5"> {{number_format($pesananbelomterkirim)}} </h2>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 stretch-card grid-margin">
              <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                  <h4 class="font-weight-normal mb-3">Number of Feedback</h4>
                  <h2 class="font-weight-normal mb-5"> {{number_format($feedback)}} </h2>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

@endsection

@section('extra_script')
<script type="text/javascript">

</script>
@endsection
