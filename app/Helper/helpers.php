<?php

function FormatRupiah($angka) {
  $number = "Rp " . number_format($angka,2,',','.');

  return $number;
}

function FormatRupiahFront($angka) {
  $number = "Rp" . number_format($angka,0,',','.');

  return $number;
}


function getsingkatan($s) {
  if(preg_match_all('/\b(\w)/',strtoupper($s),$m)) {
      $v = implode('',$m[1]); // $v is now SOQTU
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
