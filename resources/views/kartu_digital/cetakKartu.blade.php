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
   margin-top: 90px;
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
 </style>
</head>
<body>
 <table border="0" width="600" cellpadding="0" cellspacing="0">
  <tr class="head">
    <td colspan="3" align="center">Kartu Pelajar</td>
  </tr>
  <tr>
   <td width="150" style="padding-top: 15px;">Nama Lengkap</td>
   <td width="250" style="padding-top: 15px;">Muhammad Tajuddin</td>
   <td rowspan="4" style="padding-top: 20px;"><img src="https://i.ibb.co/Ld5fqm9/apin-black1.png" alt="apin-black1" border="0" width="150" height="200"></td>
  </tr>
  <tr>
   <td>Alamat</td>
   <td>Jl. Pagesangan 19 No.23</td>
  </tr>
  <tr>
    <td>NISN</td>
    <td>20128222</td>
   </tr>
   <tr>
    <td>Kelas</td>
    <td>10 IPA 1</td>
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
</body>
</html>