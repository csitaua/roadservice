<?php
	include 'dbc.php';
	date_default_timezone_set('America/Aruba');
	page_protect();
	include "support/connect.php";
	include "support/function.php";
	if(checkAdmin() && $_REQUEST['activate']==1){
		$id = $_REQUEST['id'];
		$sql = "UPDATE `polices` SET `active`=1 WHERE `id`='$id'";
	}
	else{
		$id = $_REQUEST['id'];
		$sql = "UPDATE `polices` SET `active`=0 WHERE `id`='$id'";	
	}
	mysql_query($sql);
	echo '<script language=javascript>window.history.go(-1);</script>';
	
?>
