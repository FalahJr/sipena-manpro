<?php
function isFIle($filePath) {
  return is_file($filePath) && file_exists($filePath);
}

function FormatRupiah($angka) {
  $number = "Rp " . number_format($angka,2,',','.');

  return $number;
}

function FormatRupiahFront($angka) {
  $number = "Rp " . number_format($angka,0,',','.');

  return $number;
}



function getsingkatan($s) {
  if(preg_match_all('/\b(\w)/',strtoupper($s),$m)) {
      $v = implode('', $m[1]); // $v is now SOQTU
  }

  return $v;
  // die();
}

function stringlimit($string, $limit, $end) {
  return str_limit($string, $limit, $end);
}

function compressImage($type,$source, $destination, $quality) {

   $info = getimagesize($source);

   if ($type == 'image/jpeg')
     $image = imagecreatefromjpeg($source);

   elseif ($type == 'image/gif')
     $image = imagecreatefromgif($source);

   elseif ($type == 'image/png')
     $image = imagecreatefrompng($source);

   elseif ($type == 'image/jpg')
     $image = imagecreatefromjpeg($source);

   elseif ($type == 'gif')
     $image = imagecreatefromgif($source);

   elseif ($type == 'png')
     $image = imagecreatefrompng($source);

   elseif ($type == 'jpg')
     $image = imagecreatefromjpeg($source);

   elseif ($type == 'jpeg')
     $image = imagecreatefromjpeg($source);

   imagejpeg($image, $destination, $quality);

 }

 function unique_id($l = 3) {
     return substr(md5(uniqid(mt_rand(), true)), 0, $l);
 }

 function convertNameDayIdn($date) {
   $date = str_replace("Monday","Senin",$date);
   $date = str_replace("Tuesday","Selasa",$date);
   $date = str_replace("Wednesday","Rabu",$date);
   $date = str_replace("Thursday","Kamis",$date);
   $date = str_replace("Friday","Jumat",$date);
   $date = str_replace("Saturday","Sabtu",$date);
   $date = str_replace("Sunday","Minggu",$date);

   return $date;
 }
