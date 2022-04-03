<?php

  session_start();
  require('fonctions.php');
  $_SESSION['captcha'] = strtoupper(genererMdp (6));
  
 $img = imagecreatetruecolor(120, 30);
 
 $fill_color=imagecolorallocate($img,255,255,255);
 imagefilledrectangle($img, 0, 0, 120, 30, $fill_color);
 $text_color=imagecolorallocate($img,10,10,10);
 $font = './28DaysLater.ttf';
 imagettftext($img, 23, 0, 5,30, $text_color, $font, $_SESSION['captcha']);
 
 header("Content-type: image/jpeg");
 imagejpeg($img);
 imagedestroy($img);
?>