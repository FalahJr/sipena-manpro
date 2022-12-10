<!DOCTYPE html>
<html>
<head>
 <title>Kartu Nama</title>
 <style type="text/css">
  body {
   font-family: Arial;
  }
  td {
   padding: 10px;
  }
  table {
   margin: auto;
   background:#eff5fa9d;
   padding: 20px;
   border-radius: 10px;
  }
  table .head td{
    background-color: rgba(168, 225, 255, 0.297);
    border-radius: 10px !important;
    font-weight: bold;
    font-size: 24px;
  }

  .button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
  }

 </style>
</head>
<body>
 <table border="0" width="600" cellpadding="0" cellspacing="0" id="pdf">
  <tr class="head">
    <td colspan="3" align="center">Kartu Pelajar</td>
  </tr>
  <tr>
   <td width="150" style="padding-top: 15px;">ID Siswa</td>
   <td width="250" style="padding-top: 15px;">{{$data->id}}</td>
   <td rowspan="4" style="padding-top: 20px;">
      @if(isFIle($data->foto_profil))
        <img src="{{url('/')}}/{{$data->foto_profil}}" border="0" width="150" height="200">
      @else
        <img src="http://www.landscapingbydesign.com.au/wp-content/uploads/2018/11/img-person-placeholder.jpg" border="0" width="150" height="200">
      @endif
   </td>
  </tr>
  <tr>
   <td>Nama Lengkap</td>
   <td><b>{{$data->nama_lengkap}}</b></td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td><b>{{$data->alamat}}</b></td>
   </tr>
   <tr>
    <td>Kelas</td>
    <td>{{$data->nama}}</td>
   </tr>
  {{-- <tr>
    <td>
      Scan Dompet Digital
    </td>
    <td>
      {{SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate("linkDompetDigital")}}
    </td>
  </tr> --}}
 </table>
 <br>

 <center> <button type="button" class="button" name="button" onclick="cetakKartu()"> Cetak Kartu </button> </center>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script type="text/javascript">
  function cetakKartu() {
    window.jsPDF = window.jspdf.jsPDF;
    var doc = new jsPDF('l', 'mm', [610, 332]);

  	var pdfjs = document.querySelector('#pdf');

  	// Convert HTML to PDF in JavaScript
  	doc.html(pdfjs, {
  		callback: function(doc) {
  			doc.save("{{$data->id}}-{{$data->nama_lengkap}}-kartudigital.pdf");
  		},
  		x: 5,
  		y: 5
  	});
  }
</script>
</html>
