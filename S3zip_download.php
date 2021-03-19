<?php
ini_set('display_errors', '1');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();

if(!checkAdmin() && !checkPower() &&  !checkRR()) {
	header("Location:index.php");
	exit();
}
include "support/connect.php";
include "support/function.php";
require 'Controllers/S3RSObjectController.php';
require 'Controllers/BaseController.php';
use Controllers\S3RSObject;
use Controllers\Base;
$s3_ob = new S3RSObject();
$base = new Base();
$zip = new ZipArchive;
$tmp_zip = tempnam ("tmp", "tempname") . ".zip";
$zip->open($tmp_zip, ZipArchive::CREATE);

$key= $base->decrypt($_GET['dir']);

$objects = $s3_ob->getObjects($key);


foreach ($objects as $object) {
    $contents = file_get_contents($s3_ob->getS3PresignedURL($object,1,true)); // get file
    $zip->addFromString($object, $contents); // add file contents in zip
}

$zip->close();

// Download de zip file
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=file.zip");
readfile ($tmp_zip);
unlink($tmp_zip);

?>
