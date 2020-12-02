<?php
	include "dbc.php";
	$year = $_POST[vyear];
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($db->connect_errno > 0){
  		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$sql = "SELECT make FROM vehicles GROUP BY make";
	$rs = $db->query($sql);
	$rs->data_seek(0);
	while($row = $rs->fetch_assoc()){
		echo '<option value="'.$row[make].'">'.$row[make].'</option>';
	}
	//echo '<option value="Other">Other</option>'
?>