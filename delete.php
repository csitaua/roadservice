<?php
include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();

if(!checkAdmin()) {
header("Location:index.php");
exit();
}

include "support/connect.php";
include "support/function.php";
session_start();

$file = $_REQUEST['file'];
unlink($file);

/*
$loc = strrpos($file,'/',-1);
$folder = substr($file,0,$loc);

if (count(scandir($folder)) == 2){
	rmdir($folder);
}*/
if($_REQUEST['s']==1){
	header("Location:survey.php?sid=".$_REQUEST['sc']);
	exit;
}
else{
	header("Location:edit_sc.php?sc=".$_REQUEST['sc']);
	exit;
}

?>