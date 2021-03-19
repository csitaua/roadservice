<?php

namespace Controllers;
require 'aws/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;
use Imagick;

class S3RSObject{

  public $s3, $tmp_file;
  private $ini;

  public function __construct(){
    $this->ini = parse_ini_file('../htmldev-ini/app.ini');
    $options=[
        'region'            => $this->ini['S3_region'],
        'version'           => $this->ini['S3_version'],
        'signature_version' => $this->ini['S3_signature_version']
    ];
		$this->s3= new S3Client($options);
	}

  public function getObjects($key){
    $objects = $this->s3->listObjects([
    'Bucket' => $this->ini['S3_Bucket'],
    'Prefix' => $key
    ]);

    foreach ($objects['Contents']  as $object) {
      if(strpos($object['Key'],'.') !== false){
        $o_return[] = $object['Key'];
     }
    }
    return $o_return ;
  }

  public function getObject($key){
    try {
      $result = $this->s3->listObjectsV2([
        'Bucket' => $this->ini['S3_Bucket'],
        'Prefix' =>$key
      ]);
      if($result['Contents']){
        return true;
      }
      else{
        return false;
      }

    } catch (S3Exception $e) {
      return false;
    }


  }

  public function getImageFromThumbKey($key){
    return str_replace("thumbs", "image", $key);
  }

  public function getThumbFromImageKey($key){
    return str_replace("image", "thumbs", $key);
  }

  public function getThumbFromDocKey($key){
    $t = str_replace("rrdocs", "rrdocsthumbs", $key);
    return substr($t,0,-4).'.jpeg';
  }

  public function getS3PresignedURL($key,$expire_min,$image_donwload){
    if($image_donwload){
      $cmd = $this->s3->getCommand('GetObject', [
       'Bucket' => $this->ini['S3_Bucket'],
       'Key' => $key,
       'ResponseContentType' => 'binary/octet-stream'
      ]);
    }
    else{
      $cmd = $this->s3->getCommand('GetObject', [
       'Bucket' => $this->ini['S3_Bucket'],
       'Key' => $key,
       'ResponseContentType' => 'binary/octet-stream'
      ]);
    }
    $request = $this->s3->createPresignedRequest($cmd, '+'.$expire_min.' minutes');
    return (string)$request->getUri();
  }

  public function putS3Object($file,$key,$image_resize,$thumb,$quality,$is_image){
    $temp = tmpfile();
    $temp_path = stream_get_meta_data($temp)['uri'];
    fwrite($temp, file_get_contents($file));
    if($is_image){
      if($image_resize){
        //resize image at the val
        $image = new Imagick( $file);
        if($thumb) {  $image->thumbnailImage($image_resize, 0); }
        else{
          $image->readImage($file);
          $image->resizeImage($image_resize,$image_resize,Imagick::FILTER_CATROM ,1, TRUE);
        }
        $image->stripImage();
        $image->setInterlaceScheme(Imagick::INTERLACE_JPEG);
        $image->setColorspace(Imagick::COLORSPACE_SRGB);
        $image->setImageFormat('jpeg');
        $image->setImageCompressionQuality($quality);
        $image->writeImage($temp_path);
      }
    } //end if is image
    try {
      $result = $this->s3->putObject([
          'Bucket' => $this->ini['S3_Bucket'],
          'Key' => $key,
          'Body'   => fopen($temp_path, 'r+')
      ]);
      fclose($temp);
    }catch (S3Exception $e) {
      return $e->getMessage() . "\n";
    }
  }

  public function deleteFile($key){
    $this->s3->deleteObjects([
        'Bucket'  => $this->ini['S3_Bucket'],
        'Delete' => [
            'Objects' => [
                [
                    'Key' => $key
                ]
            ]
        ]
    ]);
  }

}
?>
