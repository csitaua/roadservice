<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php"; 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$sid=$_REQUEST['sid'];
$transport=$_REQUEST['transport'];
$supplier_id=$_REQUEST['supplier'];

$dbi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$sql="SELECT * FROM parts_supplier WHERE id=$supplier_id";
$rs=$dbi->query($sql);
$row=$rs->fetch_assoc();
if($transport==='s'){
	$f=$row['factor_sea'];	
}
else{
	$f=$row['factor_air'];
}

$sql2="SELECT * FROM `survey` WHERE `id`=$sid";
$rs2=$dbi->query($sql2);
$row2=$rs2->fetch_assoc();
$service_req_id=$row2['service_req_id'];

$sql2="SELECT * FROM `service_req` WHERE `id`=$service_req_id";
$rs2=$dbi->query($sql2);
$row2=$rs2->fetch_assoc();
$find=stripos($row2['car'], "hyundai");
if($find!== FALSE){
	if($transport==='s'){
		$f=$row['factor_hyundai_sea'];	
	}
	else{
		$f=$row['factor_hyundai_air'];
	}
}

$find=stripos($row2['car'], "kia");
if($find!== FALSE){
	if($transport==='s'){
		$f=$row['factor_kia_sea'];	
	}
	else{
		$f=$row['factor_kia_air'];
	}
}


$sql="UPDATE `survey_parts` SET `parts_supplier_id`=$supplier_id, `transportation`='".$transport."' , `price`=CEILING(`price_us`*$f) WHERE `survey_id`=$sid";
$dbi->query($sql);

//echo  '<br/>'.$dbi->error.'<br/>'.$sql.'<br/>';
$dbi->close();
header("location: /roadservice/survey_parts.php?sid=".$sid);
exit;




?>