<?php

if (isset($_GET['u']) && isset($_GET['w']) && isset($_GET['h'])) {
  $src = urldecode($_GET['u']);

  $curl = curl_init($src);
  curl_setopt($curl, CURLOPT_NOBODY, true);
  curl_exec($curl);

  $ret = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);

  if ($ret == 200) {
    $height = $_GET['h'];
    $width = $_GET['w'];
    $size = getimagesize($src);
    $orig_width = $size[0];
    $orig_height = $size[1];  
    $mime = $size['mime'];
    header('Content-type: ' . $mime);
    $ratio = $orig_width / $orig_height;

    if ($width / $height > $ratio)
      $width = $height * $ratio;
    else
      $height = $width / $ratio;

    $image = imagecreatetruecolor($width, $height);

    switch ($mime) {
      case 'image/png':
        $img = imagecreatefrompng($src);
        break;
      case 'image/jpeg':
      case 'image/jpg':
        $img = imagecreatefromjpeg($src);
        break;
      default:
        break;
    }  
    
    imagecolortransparent($image, imagecolorallocatealpha($image, 0, 0, 0, 127));
    imagealphablending($image, false);
    imagesavealpha($image, true);
    imagecopyresampled($image, $img, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
    
    switch ($mime) {
      case 'image/png':
        imagepng($image);
        break;
      case 'image/jpeg':
      case 'image/jpg':
        imagejpeg($image);
        break;
      default:
        break;
    }
  } else {
    http_response_code(404);
    exit;
  }
} else {
	http_response_code(404);
	exit;
}