<?php
	include "dbc.php";
	$year = $_POST[vyear];
	$make = $_POST[vmake];
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($db->connect_errno > 0){
  		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$sql = "SELECT DISTINCT(model) model FROM vehicles WHERE make='$make' ORDER BY model";
	$rs = $db->query($sql);
	$rs->data_seek(0);
	while($row = $rs->fetch_assoc()){;
		$min = trim($row['begin']);
		$max = trim($row['end']);
		if(trim($yeart[1]) === ''){ //no max set
			$max = date('Y')+1;	
		}
		if($row["year"]==1){
			echo '<option value="'.$row["model"].'">'.$row["model"].'</option>';
		}
		else if($row[year]==$year){
			echo '<option value="'.$row["model"].'">'.$row["model"].'</option>';
		}
		else if($year >= $min && $year <= $max){
			echo '<option value="'.$row["model"].'">'.$row["model"].'</option>';			
		}
	}
	//echo '<option value="Other">Other</option>';
?>