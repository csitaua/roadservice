<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

if($_SESSION['user_level'] < ADMIN_LEVEL){
	header("Location: index.php");
	exit();
}

$id = $_REQUEST['id'];
$lic = $_REQUEST['lic'];

$sql = "DELETE FROM vehicle_com WHERE `vehicle_com`.`id` = '$id'";
mysql_query($sql);

header("location: check_ins.php?lic=".$lic);

?>
