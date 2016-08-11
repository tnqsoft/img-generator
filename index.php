<?php
$w = null;
if(isset($_GET['w'])) {
    $w = intval($_GET['w']);
}
$h = null;
if(isset($_GET['h'])) {
    $h = intval($_GET['h']);
}
$size = 16;
$angle = 0;
$font = './fonts/Roboto-Thin.ttf';
$text = $w.'x'.$h;

if($w > 2000 || $h > 2000) {
    echo ("Width or Height must be less than 2000px.");die;
} elseif($w === null && $h === null) {
    $w = 400;
    $h = 300;
    $text = 'Image Generator © '.date('Y').' TNQSoft.com';
}

if($w <= 50) {
    $size = 4;
} elseif($w > 50 && $w <= 80) {
    $size = 8;
} elseif($w > 80 && $w <= 200) {
    $size = 11;
} elseif($w > 200 && $w <= 600) {
    $size = 16;
} else {
    $size = 40;
}

$image = imagecreatetruecolor($w, $h);

$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);

$grey = imagecolorallocate($image, 224, 224, 224);
$greyLight = imagecolorallocate($image, 200, 200, 200);
$greyDark = imagecolorallocate($image, 178, 178, 178);

imagefilledrectangle($image, 0, 0, $w-1, $h-1, $grey);

imageline($image, 0, 0, $w-1, $h-1, $greyLight);
imageline($image, $w-1, 0, 0, $h-1, $greyLight);

$textBox = imagettfbbox($size, $angle, $font, $text);
$textWidth = $textBox[2]-$textBox[0];
$textHeight = $textBox[7]-$textBox[1];
$x = ($w/2) - ($textWidth/2) + 1;
$y = ($h/2) - ($textHeight/2) - 3;

imagefilledrectangle($image, $x-5, $y+5, $x+$textWidth+5, $y+$textHeight-5, $grey);
imagettftext($image, $size, $angle, $x, $y, $black, $font, $text);

header('Pragma: public');
header('Cache-control: max-age='.(60*60*24*365));
header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
   header('HTTP/1.1 304 Not Modified');
   die;
}
Header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
