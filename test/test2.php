<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);


require '../Controllers/ImagesController.php';
use Controllers\ImagesController;
$s3_img = new ImagesController();
$thumbs = $s3_img->getThumbs('156969');

foreach ($thumbs as $thumb_key){
  //echo $thumb_key.' '.$s3_img->getImageFromThumbKey($thumb_key).'</br>';
  echo '<a href="'.$s3_img->getS3PresignedURL($s3_img->getImageFromThumbKey($thumb_key),1).'"><img src="'.$s3_img->getS3PresignedURL($thumb_key,1).'" /></a></br>';
}
?>
