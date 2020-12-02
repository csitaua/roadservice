<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

if($_SESSION['user_level'] < ADMIN_LEVEL){
	header("Location: rental_list.php");
	exit();
}
else{
	
	$id = $_REQUEST['id'];
	$user= 
	
	$sql = "UPDATE `rental` set `active`=0, `delete_by`=".$_SESSION['user_id'].", `delete_stamp`=NOW() WHERE `id`='$id'";
	mysql_query($sql);
	
	header("location: rental_list.php");
}

?>
