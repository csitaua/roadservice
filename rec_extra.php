<?php
include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

$id = $_REQUEST['sc'];
$status = $_REQUEST['status'];
$pols = '';
foreach($_POST['police'] as $pol){
	if(strcmp($pols,'')==0){
		$pols = $pol;
	}
	else{
		$pols = $pols.','.$pol;	
	}
}

$specs = '';
foreach($_POST['specialist'] as $spec){
	if(strcmp($specs,'')==0){
		$specs = $spec;
	}
	else{
		$specs = $specs.','.$spec;	
	}
}

if(trim($_REQUEST['max_speed'])===''){
	$_REQUEST['max_speed']=0;
}
if(trim($_REQUEST['vehicle_speed'])===''){
	$_REQUEST['vehicle_speed']=0;
}

$sql="SELECT * FROM `service_req_extra` WHERE `sc_id`='$id'";
$rs = mysql_query($sql);

if(mysql_num_rows($rs)!=0 && !isset($_REQUEST['ph'])){
	$sql = "UPDATE `service_req_extra` SET `police`='$pols', `specialist`='$specs', `status`='$status',`ph`=0,`fname`='".mysql_real_escape_string($_REQUEST['fname'])."', `lname`='".mysql_real_escape_string($_REQUEST['lname'])."', `email`='".mysql_real_escape_string($_REQUEST['email'])."', `address`='".mysql_real_escape_string($_REQUEST['address'])."', `bday`='".mysql_real_escape_string($_REQUEST['bday'])."', `bplace`='".mysql_real_escape_string($_REQUEST['bplace'])."', `gender`='".mysql_real_escape_string($_REQUEST['gender'])."', `phone`='".mysql_real_escape_string($_REQUEST['phone'])."', `mobile`='".mysql_real_escape_string($_REQUEST['mobile'])."', `dr_license`='".mysql_real_escape_string($_REQUEST['dr_license'])."', `dr_exp`='".mysql_real_escape_string($_REQUEST['exp_date'])."', `type_incident`='".$_REQUEST['type_incident']."', `vehicle_doing`='".$_REQUEST['vehicle_doing']."', `manner_col`='".mysql_real_escape_string($_REQUEST['manner_col'])."', `col_with`='".$_REQUEST['col_with']."', `col_non`='".$_REQUEST['col_non']."', `airbag_status`='".$_REQUEST['airbag_status']."', `car_driveable`='".$_REQUEST['car_driveable']."', `visibility`='".$_REQUEST['visibility']."', `light_con`='".$_REQUEST['light_con']."', `type_pref_road`='".$_REQUEST['type_pref_road']."', `road_con`='".$_REQUEST['road_con']."', `intersection_type`='".$_REQUEST['intersection_type']."', `trafficway`='".$_REQUEST['trafficway']."', `direction_indicator`='".$_REQUEST['direction_indicator']."', `driving_side`='".$_REQUEST['driving_side']."', `vehicle_speed`='".$_REQUEST['vehicle_speed']."', `max_speed`='".$_REQUEST['max_speed']."', `driver_safety`='".$_REQUEST['driver_safety']."', `passenger_safety`='".$_REQUEST['passenger_safety']."', `substance`='".$_REQUEST['substance']."', `tired`='".$_REQUEST['tired']."', `tired_reason`='".$_REQUEST['tired_reason']."', `sick`='".$_REQUEST['sick']."', `sick_type`='".$_REQUEST['sick_type']."', `ejected`='".$_REQUEST['ejected']."', `trapped`='".$_REQUEST['trapped']."', `injured`='".$_REQUEST['injured']."', `med_care`='".$_REQUEST['med_care']."', `reported_police`='".$_REQUEST['reported_police']."', `pending_police`='".$_REQUEST['pending_police']."', `rep_impact`='".$_REQUEST['rep_impact']."', `rep_damage`='".$_REQUEST['rep_damage']."', `remarks_a`='".mysql_real_escape_string($_REQUEST['remarks_a'])."', `otherrs`=".$_REQUEST['otherrs'].", `remarks_general`='".mysql_real_escape_string($_REQUEST['remarks_general'])."' WHERE `sc_id`='$id'";
}
else if(mysql_num_rows($rs)!=0){
	$sql = "UPDATE `service_req_extra` SET `police`='$pols', `specialist`='$specs', `status`='$status',`ph`=1,`fname`='', `lname`='', `email`='', `address`='', `bday`='', `bplace`='', `gender`='', `phone`='', `mobile`='', `dr_license`='', `dr_exp`='', `otherrs`=".$_REQUEST['otherrs']." WHERE `sc_id`='$id'";
}
else if(!isset($_REQUEST['ph'])){ //Accident driver is not policy holder
	$sql = "INSERT INTO `service_req_extra` (`sc_id`,`police`, `specialist`, `status`,`ph`,`fname`, `lname`, `email`, `address`, `bday`, `bplace`, `gender`, `phone`, `mobile`, `dr_license`, `dr_exp`, `type_incident`,`vehicle_doing`, `manner_col`, `col_with`, `col_non`, `airbag_status`, `car_driveable`, `visibility`, `light_con`, `type_pref_road`, `road_con`, `intersection_type`, `trafficway`, `direction_indicator`, `driving_side`, `vehicle_speed`, `max_speed`, `driver_safety`, `passenger_safety`, `substance`, `tired`, `tired_reason`, `sick`, `sick_type`, `ejected`, `trapped`, `injured`, `med_care`, `reported_police`, `pending_police`, `rep_impact`, `rep_damage`, `remarks_a`, `remarks_general`, `otherrs`) VALUES ('$id','$pols', '$specs', '$status',0, '".mysql_real_escape_string($_REQUEST['fname'])."', '".mysql_real_escape_string($_REQUEST['lname'])."', '".mysql_real_escape_string($_REQUEST['email'])."', '".mysql_real_escape_string($_REQUEST['address'])."', '".mysql_real_escape_string($_REQUEST['bday'])."', '".mysql_real_escape_string($_REQUEST['bplace'])."', '".mysql_real_escape_string($_REQUEST['gender'])."', '".mysql_real_escape_string($_REQUEST['phone'])."', '".mysql_real_escape_string($_REQUEST['mobile'])."', '".mysql_real_escape_string($_REQUEST['dr_license'])."', '".mysql_real_escape_string($_REQUEST['exp_date'])."', '".$_REQUEST['type_incident']."', '".$_REQUEST['vehicle_doing']."', '".$_REQUEST['manner_col']."', '".$_REQUEST['col_with']."', '".$_REQUEST['col_non']."', '".$_REQUEST['airbag_status']."', '".$_REQUEST['car_driveable']."', '".$_REQUEST['visibility']."', '".$_REQUEST['light_con']."', '".$_REQUEST['type_pref_road']."', '".$_REQUEST['road_con']."', '".$_REQUEST['intersection_type']."', '".$_REQUEST['trafficway']."', '".$_REQUEST['direction_indicator']."', '".$_REQUEST['driving_side']."', ".$_REQUEST['vehicle_speed'].", ".$_REQUEST['max_speed'].", '".$_REQUEST['driver_safety']."', '".$_REQUEST['passenger_safety']."', '".$_REQUEST['substance']."', '".$_REQUEST['tired']."', '".$_REQUEST['tired_reason']."', '".$_REQUEST['sick']."', '".$_REQUEST['sick_type']."', '".$_REQUEST['ejected']."', '".$_REQUEST['trapped']."', '".$_REQUEST['injured']."', '".$_REQUEST['med_care']."', '".$_REQUEST['reported_police']."', '".$_REQUEST['pending_police']."', '".$_REQUEST['rep_impact']."', '".$_REQUEST['rep_damage']."', '".mysql_real_escape_string($_REQUEST['remarks_a'])."', '".mysql_real_escape_string($_REQUEST['remarks_general'])."', ".$_REQUEST['otherrs'].")";
}
else{	
	$sql = "INSERT INTO `service_req_extra` (`sc_id`,`police`, `specialist`, `status`,`ph`, `type_incident`,`vehicle_doing`, `manner_col`, `col_with`, `col_non`, `airbag_status`, `car_driveable`, `visibility`, `light_con`, `type_pref_road`, `road_con`, `intersection_type`, `trafficway`, `direction_indicator`, `driving_side`, `vehicle_speed`, `max_speed`, `driver_safety`, `passenger_safety`, `substance`, `tired`, `tired_reason`, `sick`, `sick_type`, `ejected`, `trapped`, `injured`, `med_care`, `reported_police`, `pending_police`, `rep_impact`, `rep_damage`, `remarks_a`, `remarks_general`, `otherrs`) VALUES ('$id','$pols', '$specs', '$status',1, '".$_REQUEST['type_incident']."', '".$_REQUEST['vehicle_doing']."', '".$_REQUEST['manner_col']."', '".$_REQUEST['col_with']."', '".$_REQUEST['col_non']."', '".$_REQUEST['airbag_status']."', '".$_REQUEST['car_driveable']."', '".$_REQUEST['visibility']."', '".$_REQUEST['light_con']."', '".$_REQUEST['type_pref_road']."', '".$_REQUEST['road_con']."', '".$_REQUEST['intersection_type']."', '".$_REQUEST['trafficway']."', '".$_REQUEST['direction_indicator']."', '".$_REQUEST['driving_side']."', ".$_REQUEST['vehicle_speed'].", ".$_REQUEST['max_speed'].", '".$_REQUEST['driver_safety']."', '".$_REQUEST['passenger_safety']."', '".$_REQUEST['substance']."', '".$_REQUEST['tired']."', '".$_REQUEST['tired_reason']."', '".$_REQUEST['sick']."', '".$_REQUEST['sick_type']."', '".$_REQUEST['ejected']."', '".$_REQUEST['trapped']."', '".$_REQUEST['injured']."', '".$_REQUEST['med_care']."', '".$_REQUEST['reported_police']."', '".$_REQUEST['pending_police']."', '".$_REQUEST['rep_impact']."', '".$_REQUEST['rep_damage']."', '".mysql_real_escape_string($_REQUEST['remarks_a'])."', '".mysql_real_escape_string($_REQUEST['remarks_general'])."', ".$_REQUEST['otherrs'].")";
}

mysql_query($sql);
//echo mysql_error();
//echo $sql.'<br/>'.mysql_error();

echo '<script type="text/javascript">window.history.go(-1);</script>';

?>