<?php
//ini_set('display_errors', '1');
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();

if(!checkAdmin() && !checkPower()) {
	header("Location:index.php");
	exit();
}

include "support/connect.php";
include "support/function.php";
session_start();
require 'Controllers/S3RSObjectController.php';
require 'Controllers/BaseController.php';
use Controllers\S3RSObject;
use Controllers\Base;
$s3_ob = new S3RSObject();
$base = new Base();

$key = $base->decrypt($_REQUEST['file']);
$s3_ob->deleteFile($key);

if($_REQUEST['s']==1){
	header("Location:survey.php?sid=".$_REQUEST['sc']);
	exit;
}
else{
	header("Location:edit_sc.php?sc=".$_REQUEST['sc']);
	exit;
}

?>
