<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

$fname = $_REQUEST['fname'];
$lname = $_REQUEST['lname'];
$llname = $lname.'/'.$fname;

$sql = "INSERT INTO attendee (`s_name`, `f_name`) VALUES ('$fname', '$llname')";
mysql_query($sql);

header("location: /roadservice");


?>