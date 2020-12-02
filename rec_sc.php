<?php



include 'dbc.php';

date_default_timezone_set('America/Aruba');
page_protect();

include "support/connect.php";
include "support/function.php";
include "support/simpleimage.php";
ini_set('max_execution_time', 240);

//error_reporting(E_ALL);
//ini_set('display_errors', '1');


$car = mysql_real_escape_string($_REQUEST['car']);
$num = mysql_real_escape_string($_REQUEST['num']);
$location = addslashes($_REQUEST['loc']);
$job = mysql_real_escape_string($_REQUEST['job']);
$attendee = mysql_real_escape_string($_REQUEST['attendee']);
$insured = mysql_real_escape_string($_REQUEST['insured']);
$notes = mysql_real_escape_string($_REQUEST['notes']);
$notes = mysql_real_escape_string($notes);
$status = mysql_real_escape_string($_REQUEST['status']);

$time = date('n-j-Y G:i:s');

$id = $_REQUEST['sc'];
$charged = $_REQUEST['charged'];
$pol = $_REQUEST['pol'];
$opendt = $_REQUEST['opendt'];
$closedt = $_REQUEST['closeddt'];
$idm = $_REQUEST['idm'];
$present = $_REQUEST['present'];
$money = $_REQUEST['money'];
$voucher = $_REQUEST['voucher'];
$receipt = $_REQUEST['receipt'];
$voucher_amount = $_REQUEST['voucher_amount'];
$addphone = $_REQUEST['addphone'];
$clientno = $_REQUEST['clientno'];
$vin = $_REQUEST['vin'];
$to_location = mysql_real_escape_string($_REQUEST['toloc']);
$district = $_REQUEST['district'];
$requestedBy = mysql_real_escape_string($_REQUEST['requestedBy']);
$acc_link = $_REQUEST['acc_link'];
$link_pos = $_REQUEST['link_pos'];
$tow_reason_id = $_REQUEST['tow_reason'];
$licenseNo = $_REQUEST['licenseNo'];
$claimNo = $_REQUEST['claimNo'];
$engine = $_REQUEST['engine'];
$licenseNo2 = $_REQUEST['licenseNo2'];
$fuel = $_REQUEST['fuel'];
$transmission2 = $_REQUEST['transmission2'];
$manu_date = $_REQUEST['manu_date'];
$veh_park_loc=$_REQUEST['veh_park_loc'];
$arrival_time=$_REQUEST['arrival_time'];
$veh_year=$_REQUEST['veh_year'];


$sql = "SELECT * FROM jobs where id='$job'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$job_group = $row['jobs_group_id'];


if(trim($spare)===''){
	$spare = 0;
}

if(trim($no_flat)===''){
	$no_flat = 0;
}

if(trim($tow_reason_id) ===''){
	$tow_reason_id=0;
}

if($insured){
	$insured = 1;
}
else{
	$insured=0;
}


if(trim($veh_year)===''){
	$veh_year=0;
}



$present = -1;
if(isset($_REQUEST['present'])){
	$present = 1;
}

$right = 0;
if(isset($_REQUEST['right'])){
	$right = 1;
}

if($money){
	$money = $_SESSION['user_id'];
}
else{
	$money = 0;
}



$over_time=0;
if(isset($_REQUEST['over_time'])){
	$over_time = 1;
}

$rspresent = 0;
if(isset($_REQUEST['rspresent'])){
	$rspresent = 1;
}

$towing_rating=$_REQUEST['tow_rating'];
$call_rating=$_REQUEST['call_rating'];
$sales_rating=$_REQUEST['sales_rating'];
$roadservice_rating=$_REQUEST['roadservice_rating'];
$claims_rating=$_REQUEST['claims_rating'];
$call=0;
$job_info=0;
$notes_checked=0;
$pictures=0;
$adm=0;
$claims_form=0;
$driver_license=0;
$couple_acc=0;
$vin_no=0;
$email=0;

if(isset($_REQUEST['call'])){
	$call = 1;
}

if(isset($_REQUEST['job_info'])){
	$job_info = 1;
}

if(isset($_REQUEST['notes_checked'])){
	$notes_checked = 1;
}

if(isset($_REQUEST['pictures'])){
	$pictures = 1;
}

if(isset($_REQUEST['adm'])){
	$adm = 1;
}

if(isset($_REQUEST['claims_form'])){
	$claims_form = 1;
}

if(isset($_REQUEST['driver_license'])){
	$driver_license = 1;
}

if(isset($_REQUEST['couple_acc'])){
	$couple_acc = 1;
}

if(isset($_REQUEST['vin_no'])){
	$vin_no = 1;
}

if(isset($_REQUEST['email'])){
	$email = 1;
}

if(strcmp($_POST['notify_broker'],'Notify Broker')==0){
	sendEmail('kenrick.kelly@nagico.com','claims@nagico-abc.com','test.','12345.');
	//header("location: /roadservice/edit_sc.php?sc=".$id);
	exit;
}

if(strcmp($_POST['tow'],'Tow')==0){
	header("location: new_sc.php?id=".$id."&job=3");
	exit;
}

if(strcmp($_POST['rental'],'Rental')==0){
	header("location: new_rental.php?id=".$id);
	exit;
}



if(strcmp($_POST['survey'],'Survey')==0){
	header("location: new_survey.php?sc=".$id);
	exit;
}

if(strcmp($_POST['acc_link'],'Couple Accident')==0 && strcmp(trim($_POST['accident_id']),'')==0){
	header("location: new_sc.php?id=".$id."&job=7&l=1");
	exit;
}

if(strcmp($_POST['acc_link2'],'Couple Accident 2')==0 && strcmp(trim($_POST['accident_id2']),'')==0){
	header("location: new_sc.php?id=".$id."&job=7&l=2");
	exit;
}



if(strcmp($_POST['acc_link3'],'Couple Accident 3')==0 && strcmp(trim($_POST['accident_id3']),'')==0){
	header("location: new_sc.php?id=".$id."&job=7&l=3");
	exit;
}



if(strcmp($_POST['delete'],'Delete') == 0 && checkAdmin()){
	$sql = "UPDATE service_req SET `delete` = '".$_SESSION['user_id']."' WHERE id = '$id' ";
	mysql_query($sql);
	header("location: /");
	exit;
}



if(strcmp($_POST['acc_link'],'Couple Accident') == 0){
	$other_id = mysql_real_escape_string($_POST['accident_id']);
	$sql = "SELECT * FROM service_req WHERE id='$other_id'";
	$rs = mysql_query($sql);
	if(mysql_num_rows($rs)==0){
		header("location: edit_sc.php?sc=".$id."&error=1");
		exit;
	}
	else{
		$row = mysql_fetch_array($rs);
			$sql2 = "UPDATE service_req SET accident_link='$id' WHERE id='$other_id'";
			mysql_query($sql2);

			$sql2 = "UPDATE service_req SET accident_link='$other_id' WHERE id='$id'";
			mysql_query($sql2);

			header("location: edit_sc.php?sc=".$id);
			exit;
	}
}

if(strcmp($_POST['acc_link2'],'Couple Accident 2') == 0 && trim($_POST['accident_id2']) !== ''){

	$other_id2 = mysql_real_escape_string($_POST['accident_id2']);

	$sql = "SELECT * FROM service_req WHERE id='$other_id2'";

	$rs = mysql_query($sql);

	if(mysql_num_rows($rs)==0){

		header("location: edit_sc.php?sc=".$id."&error=1");

		exit;

	}

	else{

		$row = mysql_fetch_array($rs);

		//if($row['job']!= 7){

		//	header("location: /roadservice/edit_sc.php?sc=".$id."&error=2");

		//	exit;

		//}

		//else{

			$sql2 = "UPDATE service_req SET accident_link='$id' WHERE id='$other_id2'";

			mysql_query($sql2);

			$sql2 = "UPDATE service_req SET accident_link2='$other_id2' WHERE id='$id'";

			mysql_query($sql2);

			header("location: edit_sc.php?sc=".$id);

			exit;

		//}

	}



}



if(strcmp($_POST['acc_link3'],'Couple Accident 3') == 0 && trim($_POST['accident_id3']) !== ''){

	$other_id3 = mysql_real_escape_string($_POST['accident_id3']);

	$sql = "SELECT * FROM service_req WHERE id='$other_id3'";

	$rs = mysql_query($sql);

	if(mysql_num_rows($rs)==0){

		header("location: edit_sc.php?sc=".$id."&error=1");

		exit;

	}

	else{

		$row = mysql_fetch_array($rs);

		//if($row['job']!= 7){

		//	header("location: edit_sc.php?sc=".$id."&error=2");

		//	exit;

		//}

		//else{

			$sql2 = "UPDATE service_req SET accident_link='$id' WHERE id='$other_id3'";

			mysql_query($sql2);

			$sql2 = "UPDATE service_req SET accident_link3='$other_id3' WHERE id='$id'";

			mysql_query($sql2);

			header("location: edit_sc.php?sc=".$id);

			exit;

		//}

	}



}


if($status==31){

	//Status to review accident

	$sql2="SELECT * FROM `service_req` WHERE `id`=".$id;

	$rs2=mysql_query($sql2);

	$row2=mysql_fetch_array($rs2);

	if($row2['pendingReviewID']==0){

		$sql3="UPDATE `service_req` SET `pendingReviewTime`='".$time."', pendingReviewID=".$_SESSION['user_id']." WHERE `id`=".$id;

		mysql_query($sql3);

	}

}


if(!$id){
	$bill_to = 0;
	if($charged != 0){
		$status = 6;
	}

	if($job_group == 7){
		$status = 8;
	}

	if($job == 11 || $job == 37){
		$status = 7;
	}

	$paymentType ='';

	if($job == 32){
		$bill_to = -10;
		$paymentType = 'Bank Transfer';
	}
	else if($job == 13){
		$bill_to = -5;
		$paymentType = 'Bank Transfer';
	}
	else if($job == 19){
		$bill_to = -4;
		$paymentType = 'Bank Transfer';
	}
	else if($job == 12){
		$bill_to = -6;
		$paymentType = 'Bank Transfer';
	}
	else if($job == 22){
		$bill_to = -2;
		$paymentType = 'Bank Transfer';
	}
	else if($job == 36){
		$bill_to = -15;
		$paymentType = 'Bank Transfer';
	}

	if(trim($pol)!==''){
		$sql = "SELECT * FROM license_policy WHERE policyNo='$pol'";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$licenseNo2='';
	}
	else { $licenseNo2='';}

	$sql = "INSERT INTO service_req (`car`, `a_number`, `location`, `job`, `attendee_id`, `insured`, `notes`, `status`, `timestamp`, `charged`,`pol`,`opendt`, `user_id`, `master_sc`, `AddPhone`, `ClientNo`, `vin`,`to_location`, `district`, `insured_at`, `requestedBy`, `accident_link`, `over_time`, `tow_reason_id`, `licenseNo`, `bill_to`, `paymentType`, `licenseNo2`, `veh_year`) VALUES ('$car', '$num', '$location', '$job', '$attendee', '$insured', '$notes', '$status', '$time', '$charged', '$pol', '$opendt', '".$_SESSION['user_id']."', '$idm', '$addphone', '$clientno', '$vin', '$to_location', '$district', '$_REQUEST[insured_at]', '$requestedBy', '$acc_link', '$over_time', '$tow_reason_id', '$licenseNo', '$bill_to', '$paymentType', '$licenseNo2', '$veh_year')";

	mysql_query($sql);
	$lastid = mysql_insert_id();

	if($acc_link){
		if($link_pos > 1){
			$sql2 = "UPDATE service_req SET accident_link".$link_pos."='$lastid' WHERE id='$acc_link'";
		}
		else{
			$sql2 = "UPDATE service_req SET accident_link='$lastid' WHERE id='$acc_link'";
		}
		mysql_query($sql2);
	}

	header("location: edit_sc.php?sc=".$lastid);

}

else if ($attendee!=0){



	//Check LicenseNo2

	if(strcmp(trim($licenseNo2),'')!=0 && $pol !== ''){

		//check license_policy table



		$sql = "select * from license_policy where licenseNo='$licenseNo2'";

		$rs = mysql_query($sql);

		if(mysql_num_rows($rs)!=0){ //Overwrite Additional Driver

			$sql = "update license_policy set licenseNo='$licenseNo2' WHERE policyNo='$pol";

			mysql_query($sql);

		}

		else{

			$sql = "insert into license_policy (licenseNo,policyNo,active) VALUES('$licenseNo2','$pol',1)";

			mysql_query($sql);

		}

	}



	$sql = "SELECT * FROM service_req WHERE `id`='$id'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);



	$tpCharged = mysql_real_escape_string($_POST['tpCharged']);
	$paymentType = mysql_real_escape_string($_POST['paymentType']);
	$invoice = mysql_real_escape_string($_POST['invoice']);

	$tpChargedReceived = 0;
	if(isset($_POST['tpChargedReceived'])){
		$tpChargedReceived = 1;
	}



	$extra = '';

	if($row['money_delivered'] == 0){ //money not delivered

		$extra = "`money_delivered`=".$money.", " ;

	}

	else if(!isset($_REQUEST['money'])){

		$extra = "`money_delivered`=0, " ;

	}

	$po_received = 0;

	if(isset($_REQUEST['po_received']) && $_REQUEST['po_received']){

		if($row['po_received']!= 0){

			$po_received = $row['po_received'];

		}

		else{

			$po_received = $_SESSION['user_id'];

		}

	}

	$extra2 = '';

	if($_SESSION['user_level'] >= 4){

		$extra2	= ", `over_time`='".$over_time."'";

	}

	if(trim($tpCharged) !== ''){

		$extra = $extra."`tpCharged`='$tpCharged',";

	}

	if(trim($tools)!==''){

		$extra = $extra."`tools`= ".$tools.",";

	}



	if(isset($_REQUEST['police_rep_req']) && $row['police_report_req']===''){

		$extra=$extra."`police_report_req`='$time',";

	}

	else if(!(isset($_REQUEST['police_rep_req']))){

		$extra=$extra."`police_report_req`='',";

	}



	if(isset($_REQUEST['police_rep_rec']) && $row['police_report_req']!==''){

		$extra=$extra."`police_report_rec`='$time', `police_report_rec_by`=".$_SESSION['user_id'].",";

	}

	else if(!(isset($_REQUEST['police_rep_rec']))){

		$extra=$extra."`police_report_rec`='', `police_report_rec_by`=0,";

	}



	$sponser = 0;

	if(isset($_POST['sponser'])){

		$sponser=1;

	}



	if(isset($_POST['spare'])){

		$extra2.= ', `spare`='.$_REQUEST['spare'];

	}

	if(isset($_REQUEST['tools'])){

		$extra2.= ', `tools`='.$_REQUEST['tools'];

	}

	if(isset($_REQUEST['no_flat'])){

		$extra2.= ', `no_flat`='.$_REQUEST['no_flat'];

	}

	if(isset($_REQUEST['ms_type'])){

		$extra2.= ', `ms_type`='.$_REQUEST['ms_type'];

	}

	if(isset($_REQUEST['km'])){

		$extra2.= ', `km`='.$_REQUEST['km'];

	}

	if(isset($_REQUEST['cost'])){

		$extra2.= ', `cost`='.$_REQUEST['cost'];

	}



	if($_POST[accident_id]!=''){

		$extra2.= ',`accident_link`='.$_POST[accident_id];

	}

	if(isset($_REQUEST['accfee']) && $row['accfee']==0){
		$sqlt="SELECT * FROM country_info";
		$rst=mysql_query($sqlt);
		$rowt=mysql_fetch_array($rst);

		$accfee = $_SESSION['user_id'];
		$tax = $rowt['tax'];
		$extra=$extra."`accfee`= $accfee, `accfee_amount`= ".$rowt['acc_fee'].", ";

		$cdate = date('Y-m-d H:i:s');
		$sqlt="INSERT INTO `transaction` (`area`,`subArea`,`areaId`,`transCode`,`amountDeb`,`tax`,`transDate`, `userId`) VALUES ('SR','ACC',".$id.",'NEW', ".$rowt['acc_fee'].",".($tax*$rowt['acc_fee']).",'$cdate', ".$_SESSION['user_id']." ) ";
		mysql_query($sqlt);
	}
	else if(!isset($_REQUEST['accfee'])){
		$extra=$extra."`accfee`= 0, `accfee_amount`= 0, ";

		$cdate = date('Y-m-d H:i:s');
		$sqlt="SELECT * FROM transaction WHERE `areaId`=".$id." AND `subArea`='ACC' and `transCode`='NEW' ORDER BY `transDate` DESC ";
		$rst=mysql_query($sqlt);
		$rowt=mysql_fetch_array($rst);
		$linkedidtrans=$rowt['idtrans'];
		$amountCred=$rowt['amountDeb'];
		$tax=$rowt['tax'];
		$sqlt="INSERT INTO `transaction` (`area`,`subArea`,`areaId`,`transCode`,`amountCred`,`tax`,`transDate`, `userId`, `linkedidtrans`) VALUES ('SR','ACC',".$id.",'RET', ".$amountCred.",".$tax.",'$cdate', ".$_SESSION['user_id'].", ".$linkedidtrans." ) ";
		mysql_query($sqlt);
	}

	if(isset($_REQUEST['adjfee']) && $row['adjfee']==0){

		$sqlt="SELECT * FROM country_info";
		$rst=mysql_query($sqlt);
		$rowt=mysql_fetch_array($rst);

		$adjfee = $_SESSION['user_id'];
		$tax = $rowt['tax'];
		$extra=$extra."`adjfee`= $adjfee, `adjfee_amount`= ".$rowt['adj_fee'].", ";

		$cdate = date('Y-m-d H:i:s');
		$sqlt="INSERT INTO `transaction` (`area`,`subArea`,`areaId`,`transCode`,`amountDeb`,`tax`,`transDate`, `userId`) VALUES ('SR','ADJ',".$id.",'NEW', ".$rowt['adj_fee'].",".($tax*$rowt['adj_fee']).",'$cdate', ".$_SESSION['user_id']." ) ";
		mysql_query($sqlt);
	}
	else if(!isset($_REQUEST['adjfee'])){
		$extra=$extra."`adjfee`= 0, `adjfee_amount`= 0, ";

		$cdate = date('Y-m-d H:i:s');
		$sqlt="SELECT * FROM transaction WHERE `areaId`=".$id." AND `subArea`='ADJ' and `transCode`='NEW' ORDER BY `transDate` DESC ";
		$rst=mysql_query($sqlt);
		$rowt=mysql_fetch_array($rst);
		$linkedidtrans=$rowt['idtrans'];
		$amountCred=$rowt['amountDeb'];
		$tax=$rowt['tax'];
		$sqlt="INSERT INTO `transaction` (`area`,`subArea`,`areaId`,`transCode`,`amountCred`,`tax`,`transDate`, `userId`, `linkedidtrans`) VALUES ('SR','ADJ',".$id.",'RET', ".$amountCred.",".$tax.",'$cdate', ".$_SESSION['user_id'].", ".$linkedidtrans." ) ";
		mysql_query($sqlt);
	}

	$mi=0;

	if(isset($_REQUEST['mi'])){

		$mi = 1;

	}

	$c_pers_no=0;

	if(isset($_REQUEST['c_pers_no'])){

		$c_pers_no=1;

	}

	$claimsId=0;

	if(isset($_REQUEST['claimsAtt'])){

		$claimsId=$_REQUEST['claimsAtt'];

	}





	$count_img=$_REQUEST['eam'];

	for($i=1;$i<=$count_img;$i++){

		if($_REQUEST['e-'.$i]!==''){

			$t=$_REQUEST['e-'.$i];

			$type=substr($t,0, strpos($t,'-'));

			$file=substr($t,strpos($t,'-')+1);

			if($type==="form"){

				$extra2.= ", `n_form`='".$file."'";

			}

			else if($type==="overview"){

				$extra2.= ", `n_overview`='".$file."'";

			}

			else if($type==="damage1"){

				$extra2.= ", `n_damage_1`='".$file."'";

			}

			else if($type==="damage2"){

				$extra2.= ", `n_damage_2`='".$file."'";

			}

			else if($type==="policy1"){

				$extra2.= ", `n_policy_1`='".$file."'";

			}

			else if($type==="license1"){

				$extra2.= ", `n_license_1`='".$file."'";

			}

			else if($type==="policy2"){

				$extra2.= ", `n_policy_2`='".$file."'";

			}

			else if($type==="license2"){

				$extra2.= ", `n_license_2`='".$file."'";

			}

			else if($type==="damage3"){

				$extra2.= ", `n_damage_3`='".$file."'";

			}

			else if($type==="vin1"){

				$extra2.= ", `n_vin_1`='".$file."'";

			}

			else if($type==="vin2"){

				$extra2.= ", `n_vin_2`='".$file."'";

			}

			else if($type==="vin3"){

				$extra2.= ", `n_vin_3`='".$file."'";

			}

			else if($type==="vin4"){

				$extra2.= ", `n_vin_4`='".$file."'";

			}

			else if($type==="license3"){

				$extra2.= ", `n_license_3`='".$file."'";

			}

			else if($type==="policy3"){

				$extra2.= ", `n_policy_3`='".$file."'";

			}

			else if($type==="damage4"){

				$extra2.= ", `n_damage_4`='".$file."'";

			}

			else if($type==="license4"){

				$extra2.= ", `n_license_4`='".$file."'";

			}

			else if($type==="policy4"){

				$extra2.= ", `n_policy_4`='".$file."'";

			}

			else if($type==="inside1"){

				$extra2.= ", `inside_1`='".$file."'";

			}

			else if($type==="inside2"){

				$extra2.= ", `inside_2`='".$file."'";

			}

			else if($type==="inside3"){

				$extra2.= ", `inside_3`='".$file."'";

			}

		}

	}


	$sql = "UPDATE service_req SET `car`='$car', `a_number`='$num', `location`='$location', `job`='$job', `arrival_time`='$arrival_time' , `attendee_id`='$attendee', `insured`='$insured', `notes`='$notes', `status`='$status', `charged`='$charged', `pol`='$pol', `closedt`='$closedt', `present`='$present', `opendt`='$opendt', ".$extra." `receipt`='$receipt', `AddPhone` = '$addphone', `vin`='$vin', `to_location`='$to_location', `district`='$district', `po_number`='$_REQUEST[po_number]', `insured_at`='$_REQUEST[insured_at]', `po_received`='$po_received', `bill_to`='$_REQUEST[bill_to]', `requestedBy`='$requestedBy' , `tpChargedReceived`='$tpChargedReceived', `paymentType`='$paymentType', `invoice`='$invoice', `sponser`='$sponser', `tow_reason_id`= $tow_reason_id, `licenseNo`='$licenseNo', `claimNo`='$claimNo', `RightHandDrive`='$right', `engine`='$engine', `rspresent`='$rspresent', `licenseNo2`='$licenseNo2', `fuel`='$fuel', `mi`='$mi', `claimsAttId`='$claimsId', `manu_date`='$manu_date', `transmission`='$transmission2', `veh_park_loc`='$veh_park_loc' ".$extra2."  WHERE id = '$id'";

	mysql_query($sql);

	//echo mysql_error().'<br/>'.$sql;



	if( trim($claimNo)!==''){

		$sql3="SELECT * FROM service_req WHERE `id` = '$id'";

		$rs3=mysql_query($sql3);

		$row3=mysql_fetch_array($rs3);

		$al1=$row3['accident_link'];

		$al2=$row3['accident_link2'];

		$al3=$row3['accident_link3'];

		$ms=$row3['master_sc'];

		if($al1!=0 && $ms==0){

			$sqlt="UPDATE service_req SET `claimNo`='$claimNo' WHERE id='$al1'";

			mysql_query($sqlt);

		}

		if($al2!=0 && $ms==0){

			$sqlt="UPDATE service_req SET `claimNo`='$claimNo' WHERE id='$al2'";

			mysql_query($sqlt);

		}

		if($al3!=0 && $ms==0){

			$sqlt="UPDATE service_req SET `claimNo`='$claimNo' WHERE id='$al3'";

			mysql_query($sqlt);

		}

	}



	//Check Rating

	//echo mysql_error();

	$sql = "SELECT * FROM service_req_rating WHERE service_req_id='$id'";

	$rs = mysql_query($sql);

	if($_SESSION['user_level'] > POWER_LEVEL){

		if(mysql_num_rows($rs)!=0){

			//update

			$sql="UPDATE service_req_rating SET `call`='$call' ,`job_info`='$job_info' ,`notes`='$notes_checked' ,`pictures`='$pictures' ,`adm`='$adm',`claims_form`='$claims_form' ,`towing_rating`='$towing_rating' ,`driver_license`='$driver_license',`couple_acc`='$couple_acc' ,`call_rating`='$call_rating',`sales_rating`='$sales_rating' ,`roadservice_rating`='$roadservice_rating' ,`claims_rating`='$claims_rating', `pers_no`='$c_pers_no', `vin_no`='$vin_no', `email`='$email' WHERE `service_req_id`=$id";

			mysql_query($sql);

			//echo mysql_error().'<br/>'.$sql;

		}

		else{

			$sql="INSERT INTO service_req_rating (`service_req_id`,`call`,`job_info`,`notes`,`pictures`,`adm`,`claims_form`,`towing_rating`,`driver_license`,`couple_acc`,`call_rating`,`sales_rating`,`roadservice_rating`,`claims_rating`,`pers_no`, `vin_no`, `email`) VALUES ('$id','$call','$job_info','$notes_checked','$pictures','$adm','$claims_form','$towing_rating','$driver_license','$couple_acc','$call_rating','$sales_rating','$roadservice_rating','$claims_rating', '$c_pers_no', '$vin_no', '$email')";

			mysql_query($sql);

			//echo mysql_error();

		}

	}



	//echo mysql_error();

	/*if($acc_link){

		$acc_link2 = mysql_insert_id();

		$sql2 = "UPDATE service_req SET accident_link='$acc_link2' WHERE id='$acc_link'";

		mysql_query($sql2);

	}*/
	$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));
	//echo 'extension '.$ext;
	if(strcmp($ext,'mov')==0 || strcmp($ext,'MOV')==0 || strcmp($ext,'mp4')==0 || strcmp($ext,'MP4')==0){

		mkdir(FOLDER.'rrmov/'.$id);
		$path = FOLDER."rrmov/".$id.'/';



		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = FOLDER."rrmov/".$id.'/';
		$name = $_FILES["image_upload_box"]["name"];
		$length = 10;

		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

		$remote_file = $path.$randomString.".".$ext;

		while(file_exists($remote_file )){

			$length = 10;

			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

			$remote_file = $path.$randomString.".".$ext;

		}

		$name=$randomString.".".$ext;



		move_uploaded_file($_FILES['image_upload_box']['tmp_name'], $remote_file);

	}

	//audio mp3
	else if(strcmp($ext,'mp3')==0 || strcmp($ext,'MP3')==0 || strcmp($ext,'m4a')==0 || strcmp($ext,'M4A')==0){

		mkdir(FOLDER.'rraudio/'.$id);
		$path = FOLDER."rraudio/".$id.'/';

		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		//$path = FOLDER."rrmov/".$id.'/';
		$name = $_FILES["image_upload_box"]["name"];
		$length = 10;

		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

		$remote_file = $path.$randomString.".".$ext;

		while(file_exists($remote_file )){

			$length = 10;

			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

			$remote_file = $path.$randomString.".".$ext;

		}

		$name=$randomString.".".$ext;



		move_uploaded_file($_FILES['image_upload_box']['tmp_name'], $remote_file);

	} //end mp3

	else if(strcmp($ext,'pdf')==0){

		mkdir(FOLDER.'rrdocs/'.$id);

		mkdir(FOLDER.'rrdocsthumbs/'.$id);

		$path = FOLDER."rrdocs/".$id.'/';

		$tu = FOLDER.'rrdocsthumbs'.$id.'/';



		$fn = 1;

		$file_name = $id.'_'.$fn.'.'.$ext;

		$path = FOLDER."rrdocs/".$id.'/';

		$name = $_FILES["image_upload_box"]["name"];

		$length = 10;

		$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

		$remote_file = $path.$randomString.".pdf";

		while(file_exists($remote_file )){

			$length = 10;

			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);

			$remote_file = $path.$randomString.".pdf";

		}

		$name=$randomString.".pdf";



		if(file_exists($remote_file)){

            ?>

            	<script type="text/javascript">

					alert("Filename already exist, please change name and try again.");

					window.location = "edit_sc.php?sc="+<?php echo $id;?>;

				</script>

            <?php

		}

		else{

			move_uploaded_file($_FILES['image_upload_box']['tmp_name'], $remote_file);


			//include $_SERVER["DOCUMENT_ROOT"]."/support/simpleimage.php";
			$im = new imagick();
			$im->setResolution(300,300);
			$im->readImage(FOLDER.'rrdocs/'.$id.'/'.$name.'[0]');
		//	$im->setImageCompression(imagick::COMPRESSION_JPEG);
			$im->setImageCompressionQuality(80);
			$im->resizeImage(400,400,Imagick::FILTER_LANCZOS,1, TRUE);
			$im = $im->flattenImages();
			$im->setImageFormat('jpeg');
			$im->writeImage(FOLDER.'rrdocsthumbs/'.$id.'/'.substr($name,0,-4).'.jpeg');
			$im->clear();
			$im->destroy();

		}

	}

	else if(strcmp($ext,'zip')==0){

		$temp_z=1;
		//echo 'here';
		while(file_exists('temp/'.$temp_z.'.zip')){

			$temp_z++;

		}

		$temp_z_file='temp/'.$temp_z.'.zip';

		move_uploaded_file($_FILES['image_upload_box']['tmp_name'], $temp_z_file);

		$dir = 'temp/';

		$zip = new ZipArchive;

		$res = $zip->open($temp_z_file);



		$temp_dir = 1;

		while(is_dir($dir.$temp_dir)){

			$temp_dir++;

		}

		mkdir($dir.$temp_dir);

		if ($res === TRUE) {

		  $zip->extractTo($dir.$temp_dir);

		  $zip->close();

		}



		$objects = scandir($dir.$temp_dir);



		foreach ($objects as $object) {

		   	if ($object != "." && $object != "..") {



				$ext = end(explode($dir.$temp_dir."/".$object));
				$ext2 = end(explode(".",$dir.$temp_dir."/".$object));
				$fhandle = finfo_open(FILEINFO_MIME);
				$mime_type = finfo_file($fhandle,$dir.$temp_dir."/".$object);

			 echo $mime_type.'/<br/>';

				if(strcmp($mime_type,'application/pdf; charset=binary')==0){

					mkdir(FOLDER.'rrdocs/'.$id);

					$path = FOLDER."rrdocs/".$id.'/';



					$fn = 1;

					$file_name = $id.'_'.$fn.'.'.$ext;

					$path = FOLDER."rrdocs/".$id.'/';

					$remote_file = $path.$object;

					$length = 10;

					$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

					$remote_file = $path.$randomString.".pdf";

					while(file_exists($remote_file )){

						$length = 10;

						$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);

						$remote_file = $path.$randomString.".pdf";

					}

					$name=$randomString.".pdf";
					if(file_exists($remote_file)){
					}


					else{

						copy($dir.$temp_dir."/".$object, $remote_file);

						mkdir(FOLDER.'rrdocsthumbs/'.$id);
						$im = new imagick();
						$im->setResolution(300,300);
						$im->readImage(FOLDER.'rrdocs/'.$id.'/'.$name.'[0]');
						//$im->setImageCompression(imagick::COMPRESSION_JPEG);
						$im->setImageCompressionQuality(80);
						$im->resizeImage(400,400,Imagick::FILTER_LANCZOS,1, TRUE);
						$im = $im->flattenImages();
						$im->setImageFormat('jpeg');
						$im->writeImage(FOLDER.'rrdocsthumbs/'.$id.'/'.substr($name,0,-4).'.jpeg');
						$im->clear();
						$im->destroy();


					}

				}
				//audio mp3
				else if(strcmp($mime_type,'audio/mpeg; charset=binary')==0 || strcmp($ext2,'m4a')==0 || strcmp($ext2,'M4A')==0 ){
					mkdir(FOLDER.'rraudio/'.$id);
					$path = FOLDER."rraudio/".$id.'/';
					$fn = 1;
					$file_name = $id.'_'.$fn.'.'.$ext;
					//$path = FOLDER."rrmov/".$id.'/';
					//$name = $_FILES["image_upload_box"]["name"];

					$length = 10;
					$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
					$remote_file = $path.$randomString.".".$ext2;
					while(file_exists($remote_file )){
						$length = 10;
						$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length);
						$remote_file = $path.$randomString.".".$ext2;
					}
					//$name=$randomString.".".$ext;
					//echo $remote_file;
					copy($dir.$temp_dir."/".$object, $remote_file);
				} //end mp3

				else if (strcmp($mime_type,"image/jpeg; charset=binary")==0 || strcmp($mime_type,"image/pjpeg; charset=binary")==0 || strcmp($mime_type,"image/gif; charset=binary")==0 || strcmp($mime_type,"image/x-png; charset=binary")==0 || strcmp($mime_type,"image/png; charset=binary")==0)

				{

					mkdir(FOLDER.'rrimage/'.$id);

					mkdir(FOLDER.'rrthumbs/'.$id);

					mkdir(FOLDER."rrgen/".$id);



					$ext = end(explode('.', $dir.$temp_dir."/".$object));

					$fn = 1;

					$file_name = $id.'_'.$fn.'.'.$ext;

					$path = FOLDER."rrimage/".$id.'/';

					$patht = FOLDER."rrthumbs/".$id.'/'; //thumb

					$pathgen = FOLDER."rrgen/".$id.'/';

					$remote_file = $path.$file_name;

					$remote_file_t = $patht.$file_name; //thumbs

					while(file_exists($remote_file)){

						$fn++;

						$file_name = $id.'_'.$fn.'.'.$ext;

						$remote_file = $path.$file_name;

						$remote_file_t = $patht.$file_name; //thumbs

					}



					if(substr($object,0,17)==='General Situation'){



						$pos=substr($object,19,1);

						$t = $pos.'.'.$ext;

						$t = $pathgen.$t;

						$sql = "UPDATE service_req SET `img".$pos."`='$t' WHERE `id`=$id";

						mysql_query($sql);



						$gen = new SimpleImage();

						$gen->load($dir.$temp_dir."/".$object);

						$gen->resizeToWidth(800);

						$gen->Save($t);



					}

					$image = new SimpleImage();

					$image->load($dir.$temp_dir."/".$object);

					$image->resizeToWidth(2600);

					$image->Save($remote_file);

					/*$thumb = new SimpleImage();

					$thumb->load($dir.$temp_dir."/".$object);

					$thumb->resizeToWidth(200);

					$thumb->Save($remote_file_t);*/

					$im = new imagick();
					$im->readimage($dir.$temp_dir."/".$object);
					$im->thumbnailImage(300,300,true);
					$im->setImageFormat('jpeg');
					$im->writeImage($remote_file_t);
					$im->clear();
					$im->destroy();



				}

				unlink($dir.$temp_dir."/".$object);

				unlink($temp_z_file);

		   	}

		}

		reset($objects);

		rmdir($dir.$temp_dir);



	}

	//**********************Image****************************

	else if (($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg" || $_FILES["image_upload_box"]["type"] == "image/gif" || $_FILES["image_upload_box"]["type"] == "image/x-png" || $_FILES["image_upload_box"]["type"] == "image/png") && ($_FILES["image_upload_box"]["size"] < 5000000))

	{



		mkdir(FOLDER.'rrimage/'.$id);

		mkdir(FOLDER.'rrthumbs/'.$id);



		$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));

		$fn = 1;

		$file_name = $id.'_'.$fn.'.'.$ext;

		$path = FOLDER."rrimage/".$id.'/';

		$patht = FOLDER."rrthumbs/".$id.'/'; //thumb

		$remote_file = $path.$file_name;

		$remote_file_t = $patht.$file_name; //thumbs

		while(file_exists($remote_file)){

			$fn++;

			$file_name = $id.'_'.$fn.'.'.$ext;

			$remote_file = $path.$file_name;

			$remote_file_t = $patht.$file_name; //thumbs

		}



		$image = new SimpleImage();
		$image->load($_FILES["image_upload_box"]["tmp_name"]);
		$image->resizeToWidth(2600);
		$image->Save($remote_file);
		/*$thumb = new SimpleImage();
		$thumb->load($_FILES["image_upload_box"]["tmp_name"]);
		$thumb->resizeToWidth(200);
		$thumb->Save($remote_file_t);*/

		$im = new imagick();
		$im->readImage($_FILES["image_upload_box"]["tmp_name"]);
		$im->thumbnailImage(300,300,true);
		$im->setImageFormat('jpeg');
		$im->writeImage($remote_file_t);
		$im->clear();
		$im->destroy();



	}

//echo  '<br/>'.mysql_error().'<br/>'.$sql.'<br/>'.$spare;

$sql="SELECT * FROM `service_req` WHERE id=$id";

$rs=mysql_query($sql);

$row=mysql_fetch_array($rs);

if($row['broker_notify']==0 && $row['img1']!==''){

	//email broker

	//sendEmail('kenrick.kelly@nagico.com','claims.aruba@nagico.com','test.','12345.');

}

genAgentPDFNotification($id);

header("location: edit_sc.php?sc=".$id);

exit;

}







?>
