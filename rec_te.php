<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

$name = $_REQUEST['name'];
$address1 = $_REQUEST['address1'];
$address2 = $_REQUEST['address2'];
$city = $_REQUEST['city'];
$state = $_REQUEST['state'];
$country = $_REQUEST['country'];
$zip = $_REQUEST['zip'];
$phone1 = $_REQUEST['phone1'];
$time = date('n-j-Y G:i:s');
$phone2 = $_REQUEST['phone2'];
$em = $_REQUEST['em'];
$datetime = $_REQUEST['datetime'];
$id = $_REQUEST['te'];


if(!$id){
	$sql = "INSERT INTO travel_req (`name`, `address1`, `address2`, `city`, `state`, `country`, `zip`, `phone1`, `timestamp`, `phone2`,`em`,`datetime`, `user_id`) VALUES ('$name', '$address1', '$address2', '$city', '$state', '$country', '$zip', '$phone1', '$time', '$phone2', '$em', '$datetime', '".$_SESSION['user_id']."')";
	mysql_query($sql);	
	header("location: /roadservice/list_te.php");
}
else{ //edit id passed
	$sql = "UPDATE travel_req SET `name`='$name', `address1`='$address1', `address2`='$address2', `city`= '$city', `state`='$state', `country`='$country', `zip`='$zip', `phone1`='$phone1', `phone2`='$phone2',`em`='$em' WHERE `id`='$id'";
	mysql_query($sql);	
	header("location: /roadservice/list_te.php");
}

?>