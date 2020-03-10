<?php
error_reporting (0);

if (is_numeric($_GET['n']))
   $text = $_GET['n'];
else
   exit();
$fontSize = 52;
$angle = 0;
$color = imagecolorallocate($img, 0, 0, 0);
$font = '10623.ttf';

$textWidth = 1 / strlen ($text) * 20;

$file = 'moon.png'; // path to png image
$img = imagecreatefrompng($file); // open image
imagealphablending($img, true); // setting alpha blending on
imagesavealpha($img, true); // save alphablending setting (important)

imagettftext($img, $fontSize, $angle, $textWidth, 85, $color, $font, $text . '%');
/* Output image to browser */
header("Content-type: image/png");
imagePng($img);
?>