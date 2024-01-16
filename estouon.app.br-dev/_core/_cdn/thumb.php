<?php

include('../_includes/config.php');
$getimg = $_GET['img'];
$w = $_GET['w'];
$h = $_GET['h'];

if( !$getimg ) {
 global $favicon;
 global $faviconw;
 if( $favicon ) {
    $w = $faviconw;
    $getimg = $favicon;
 }
}

$file = base64_decode( $getimg );

if( !$w ) {
   $w = 300;
}
if( !$h ) {
   $h = $w;
}

$file_tmp = $rootpath."/_core/_uploads/".$file;
$file_ext = explode('.',$file);
$file_ext = strtolower(end($file_ext));

switch( $file_extension ) {
    case "gif": $ctype="image/gif"; break;
    case "png": $ctype="image/png"; break;
    case "jpeg":
    case "jpg": $ctype="image/jpeg"; break;
    default:
}

header('Content-type: ' . $ctype);

if( $file_ext == "jpg" ) {
   $newimage = imagecreatefromjpeg($file_tmp);
} 
if( $file_ext == "jpeg" ) {
   $newimage = imagecreatefromjpeg($file_tmp);
} 
if( $file_ext == "png" ) {
   $newimage = imagecreatefrompng($file_tmp);
} 
if( $file_ext == "gif" ) {
   $newimage = imagecreatefromgif($file_tmp);
}

// Target dimensions
$max_width = $w;
$max_height = $h;

// Get current dimensions
$old_width  = imagesx($newimage);
$old_height = imagesy($newimage);

if($old_width > $max_height) {

   // Calculate the scaling we need to do to fit the image inside our frame
   $scale = min($max_width/$old_width, $max_height/$old_height);

   // Get the new dimensions
   $new_width  = ceil($scale*$old_width);
   $new_height = ceil($scale*$old_height);

   // Create new empty image
   $new = imagecreatetruecolor($new_width, $new_height);

   if( $file_ext == "png" ) {
      imagealphablending($new, false);
      imagesavealpha($new,true);
      $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
      imagefilledrectangle($new, 0, 0, $new_width, $new_height, $transparent); 
   }

   // Resize old image into new
   imagecopyresampled($new, $newimage, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

} else {

   $new = $newimage;

   if( $file_ext == "png" ) {
      imagealphablending($new, false);
      imagesavealpha($new,true);
      $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
      imagefilledrectangle($new, 0, 0, $new_width, $new_height, $transparent); 
   }

}

if( $file_ext == "jpg" ) {
   imagejpeg( $new );
} 

if( $file_ext == "jpeg" ) {
   imagejpeg( $new );
} 

if( $file_ext == "png" ) {
   imagepng( $new );
} 

if( $file_ext == "gif" ) {
   imagegif( $new );
}

imagedestroy( $new );  

?>