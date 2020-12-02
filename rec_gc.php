<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php"; 
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$car = mysql_real_escape_string($_REQUEST['car']);
$num = mysql_real_escape_string($_REQUEST['num']);
$location = addslashes($_REQUEST['loc']);
$job = mysql_real_escape_string($_REQUEST['job']);
$attendee = mysql_real_escape_string($_REQUEST['attendee']);
$insured = 1;
$notes = mysql_real_escape_string($_REQUEST['notes']);
$notes = mysql_real_escape_string($notes);
$status = mysql_real_escape_string($_REQUEST['status']);
$time = date('n-j-Y G:i:s');
$id = $_REQUEST['gc'];
$charged = 0;
$pol = $_REQUEST['pol'];
$opendt = $_REQUEST['opendt'];
$closedt = $_REQUEST['closeddt'];
$idm = 0;
$present = 1;
$money = $_REQUEST['money'];
$voucher = $_REQUEST['voucher'];
$receipt = $_REQUEST['receipt'];
$voucher_amount = $_REQUEST['voucher_amount'];
$addphone = $_REQUEST['addphone'];
$clientno = $_REQUEST['clientno'];
$vin = '';
$to_location = mysql_real_escape_string($_REQUEST['toloc']);
$district = $_REQUEST['district'];
$requestedBy = mysql_real_escape_string($_REQUEST['requestedBy']);
$acc_link = 0;
$link_pos = $_REQUEST['link_pos'];
$tow_reason_id = 0;
$licenseNo = $_REQUEST['licenseNo'];
$claimNo = $_REQUEST['claimNo'];
$engine = $_REQUEST['engine'];
$licenseNo2 = $_REQUEST['licenseNo2'];
$fuel = $_REQUEST['fuel'];
$transmission2 = $_REQUEST['transmission2'];
$manu_date = $_REQUEST['manu_date'];
$veh_park_loc=$_REQUEST['veh_park_loc'];
$arrival_time=$_REQUEST['arrival_time'];
$other_ins_info=$_REQUEST['other_ins_info'];
$other_contact=$_REQUEST['other_contact'];
$other_contact_phone=$_REQUEST['other_contact_phone'];
$roof_dam_info=$_REQUEST['roof_dam_info'];
$wall_dam_info=$_REQUEST['wall_dam_info'];
$window_dam_info=$_REQUEST['window_dam_info'];
$content_dam_info=$_REQUEST['content_dam_info'];
$other_dam=$_REQUEST['other_dam'];
$veh_year=0;
$over_time=0;

$_REQUEST['insured_at']=0;

/*
$sql = "SELECT * FROM jobs where id='$job'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$job_group = $row['jobs_group_id'];

*/

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
$other_ins=0;
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
if(isset($_REQUEST['other_ins'])){
	$other_ins=1;
}


if(!$id){
	$bill_to = 0;	
	if($charged != 0){
		$status = 6;	
	}
	
	$paymentType ='';
	$licenseNo2='';
	
	
	$sql = "INSERT INTO service_gc (`car`, `a_number`, `location`, `job`, `attendee_id`, `insured`, `notes`, `status`, `timestamp`, `charged`,`pol`,`opendt`, `user_id`, `master_sc`, `AddPhone`, `ClientNo`, `vin`,`to_location`, `district`, `insured_at`, `requestedBy`, `accident_link`, `over_time`, `tow_reason_id`, `licenseNo`, `bill_to`, `paymentType`, `licenseNo2`, `veh_year`) VALUES ('$car', '$num', '$location', '$job', '$attendee', '$insured', '$notes', '$status', '$time', '$charged', '$pol', '$opendt', '".$_SESSION['user_id']."', '$idm', '$addphone', '$clientno', '$vin', '$to_location', '$district', '$_REQUEST[insured_at]', '$requestedBy', '$acc_link', '$over_time', '$tow_reason_id', '$licenseNo', '$bill_to', '$paymentType', '$licenseNo2', '$veh_year')";
	mysql_query($sql);	
	$lastid = mysql_insert_id();

	//mysql_error().'<br/>'.$sql;
	header("location: /roadservice/edit_gc.php?sc=".$lastid);
	
}
else{
	
	
	$sql = "SELECT * FROM service_gc WHERE `id`='$id'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	/*
	if(strcmp($row['notes'],'')!=0 && strcmp($notes,'')==0){
		$notes = $row['notes'];
	}
	else if(strcmp($row['notes'],'')!=0){
		$notes = $row['notes'].' - '.$notes;
	}
	Remove Gibi wanted to be able to change this including remove!!
	*/
	
	$tpCharged = mysql_real_escape_string($_POST['tpCharged']);
	$paymentType = mysql_real_escape_string($_POST['paymentType']);
	$invoice = mysql_real_escape_string($_POST['invoice']);
	
	$tpChargedReceived = 0;
	if(isset($_POST['tpChargedReceived'])){
		$tpChargedReceived = 1;	
	}
	
	$extra = '';
	
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
		}
	}
	
	$sql = "UPDATE service_gc SET `other_dam`='$other_dam', `roof_dam_info`='$roof_dam_info', `wall_dam_info`='$wall_dam_info', `content_dam_info`='$content_dam_info', `window_dam_info`='$window_dam_info' ,`other_ins`='$other_ins', `other_ins_info`='$other_ins_info', `other_contact`='$other_contact', `other_contact_phone`='$other_contact_phone', `location`='$location', `job`='$job', `arrival_time`='$arrival_time' , `attendee_id`='$attendee', `insured`='$insured', `notes`='$notes', `status`='$status', `pol`='$pol', `closedt`='$closedt', `opendt`='$opendt', ".$extra."  `AddPhone` = '$addphone', `vin`='$vin', `to_location`='$to_location', `district`='$district', `insured_at`='$_REQUEST[insured_at]', `requestedBy`='$requestedBy', `claimNo`='$claimNo', `licenseNo2`='$licenseNo2', `claimsAttId`='$claimsId' ".$extra2."  WHERE id = '$id'";
	mysql_query($sql);
	echo '<br/>'.mysql_error().'<br/>'.$sql.'<br/>';
	

	$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));
	
	if($_SESSION['country']==='Curacao'){
		$path_e='cur/';
		$path_e2='cur//';
	}
	else if($_SESSION['country']==='Sint Maarten'){
		$path_e='sxm/';
		$path_e2='sxm//';
	}
	else{
		$path_e='';
		$path_e2='';
	}
	if(strcmp($ext,'mov')==0 || strcmp($ext,'MOV')==0){
		mkdir($path_e.'gcmov/'.$id);
		$path = $path_e."gcmov/".$id.'/';
		
		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = $path_e."gcmov/".$id.'/';
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
	else if(strcmp($ext,'pdf')==0){
		mkdir($path_e.'gcdocs/'.$id);
		mkdir($path_e.'gcdocsthumbs/'.$id);
		$path = $path_e."gcdocs/".$id.'/';
		$tu = $path_e.'gcdocsthumbs'.$id.'/';
		
		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = $path_e."gcdocs/".$id.'/';
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
					window.location = "/roadservice/edit_sc.php?sc="+<?php echo $id;?>;
				</script>
            <?php
		}
		else{
			move_uploaded_file($_FILES['image_upload_box']['tmp_name'], $remote_file);
			
			$im = new imagick();
			$im->setResolution(300,300);
			$im->readImage('D:\\web\\roadservice\\'.$path_e2.'gcdocs\\'.$id.'\\'.$name.'[0]');
			$im->setCompressionQuality(80);
			$im->resizeImage(400,400,Imagick::FILTER_LANCZOS,1, TRUE);
			$im->setImageFormat('jpeg');
			$im->writeImage('D:\\web\\roadservice\\'.$path_e2.'gcdocsthumbs\\'.$id.'\\'.substr($name,0,-4).'.jpeg');
			$im->clear();
			$im->destroy();
		}
	}
	else if(strcmp($ext,'zip')==0){
		$temp_z=1;
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
		include('support/simpleimage.php');
		foreach ($objects as $object) {
		   	if ($object != "." && $object != "..") {
				
				$ext = end(explode($dir.$temp_dir."/".$object));
				$fhandle = finfo_open(FILEINFO_MIME);
				$mime_type = finfo_file($fhandle,$dir.$temp_dir."/".$object); 
				//echo $mime_type.'<br/>';
				if(strcmp($mime_type,'application/pdf; charset=binary')==0){
					mkdir($path_e.'gcdocs/'.$id);
					$path = $path_e."gcdocs/".$id.'/';
					
					$fn = 1;
					$file_name = $id.'_'.$fn.'.'.$ext;
					$path = $path_e."gcdocs/".$id.'/';
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
						mkdir($path_e.'gcdocsthumbs/'.$id);
						$im = new imagick();
						$im->setResolution(300,300);
						$im->readImage('D:\\web\\roadservice\\'.$path_e2.'gcdocs\\'.$id.'\\'.$name.'[0]');
						$im->setCompressionQuality(80);
						$im->resizeImage(400,400,Imagick::FILTER_LANCZOS,1, TRUE);
						$im->setImageFormat('jpeg');
						$im->writeImage('D:\\web\\roadservice\\'.$path_e2.'gcdocsthumbs\\'.$id.'\\'.substr($name,0,-4).'.jpeg');
						$im->clear();
						$im->destroy();
					}
				}
				else if (strcmp($mime_type,"image/jpeg; charset=binary")==0 || strcmp($mime_type,"image/pjpeg; charset=binary")==0 || strcmp($mime_type,"image/gif; charset=binary")==0 || strcmp($mime_type,"image/x-png; charset=binary")==0 || strcmp($mime_type,"image/png; charset=binary")==0)
				{
					mkdir($path_e.'gcimage/'.$id);
					mkdir($path_e.'gcthumbs/'.$id);
					mkdir($path_e."gcgen/".$id);
					
					$ext = end(explode('.', $dir.$temp_dir."/".$object));
					$fn = 1;
					$file_name = $id.'_'.$fn.'.'.$ext;
					$path = $path_e."gcimage/".$id.'/';
					$patht = $path_e."gcthumbs/".$id.'/'; //thumb
					$pathgen = $path_e."gcgen/".$id.'/';
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
						$sql = "UPDATE service_gc SET `img".$pos."`='$t' WHERE `id`=$id";
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
					$thumb = new SimpleImage();
					$thumb->load($dir.$temp_dir."/".$object);
					$thumb->resizeToWidth(200);
					$thumb->Save($remote_file_t);
			
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
		
		mkdir($path_e.'gcimage/'.$id);
		mkdir($path_e.'gcthumbs/'.$id);
		
		$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));
		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = $path_e."gcimage/".$id.'/';
		$patht = $path_e."gcthumbs/".$id.'/'; //thumb
		$remote_file = $path.$file_name;
		$remote_file_t = $patht.$file_name; //thumbs
		while(file_exists($remote_file)){
			$fn++;
			$file_name = $id.'_'.$fn.'.'.$ext;
			$remote_file = $path.$file_name;	
			$remote_file_t = $patht.$file_name; //thumbs
		}
		
		include('support/simpleimage.php');
		$image = new SimpleImage();
		$image->load($_FILES["image_upload_box"]["tmp_name"]);
		$image->resizeToWidth(2600);
		$image->Save($remote_file);
		$thumb = new SimpleImage();
		$thumb->load($_FILES["image_upload_box"]["tmp_name"]);
		$thumb->resizeToWidth(200);
		$thumb->Save($remote_file_t);

	}	



}

header("location:  edit_gc.php?sc=".$id);
exit;


?>