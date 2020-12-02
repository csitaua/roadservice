<?php
include 'dbc.php';
page_protect();
$license = $_GET['license'];

$sql = "SELECT * FROM drivers_license where id='$license'";
$rs = mysql_query($sql);
if($row = mysql_fetch_array($rs)){
	header("Content-type: image/jpeg");	
	echo $row['content'];
}

?>