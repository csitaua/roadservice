<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);


require 'Controllers/S3RSObjectController.php';


use Controllers\S3RSObject;
$s3_img = new S3RSObject();
$thumbs = $s3_img->getThumbs('156969');

foreach ($thumbs as $thumb_key){
  //echo $thumb_key.' '.$s3_img->getImageFromThumbKey($thumb_key).'</br>';
  echo '<a href="'.$s3_img->getS3PresignedURL($s3_img->getImageFromThumbKey($thumb_key),1,1).'"><img src="'.$s3_img->getS3PresignedURL($thumb_key,1,0).'" /></a></br>';
}
?>
