<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
ini_set('max_execution_time', 240);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$page='rec_survey.php';
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
$market_value=$_POST['market_value'];
$est_rep_cost=$_POST['est_rep_cost'];
$est_parts_cost=$_POST['est_parts_cost'];
$unf_rep_cost=$_POST['unf_rep_cost'];
$unf_parts_cost=$_POST['unf_parts_cost'];
$wreck_value=$_POST['wreck_value'];
$manu_date=$_POST['manu_date'];
$mileage=$_POST['mileage'];
$bodyshop_id=$_POST['bodyshop_id'];
$cond_ext=$_POST['cond_ext'];
$cond_int=$_POST['cond_int'];
$modification=$_POST['modification'];
$status_id=$_POST['status_id'];
$closeddt=$_POST['closeddt'];
$notes=$_POST['notes'];
$transmission=$_POST['transmission'];
$adjuster_id=$_POST['adjuster'];
$survey_type_id=$_POST['survey_type_id'];
$bodyshop_app_date=$_POST['bodyshop_app_date'];
$damage_calculation_a=$_POST['damage_calculation_a'];
$parts_status=$_POST['parts_status'];
$conv_client=$_POST['conv_client'];
$client_status=$_POST['client_status'];
$s_rep_date=$_POST['srepdate'];
$parts_loc=$_POST['part_local'];

if(isset($_POST['opendt'])){
	$opendt=$_POST['opendt'];
}
else{
	$opendt='';
}

$days_rep=$_POST['days_rep'];

$extra='';

if($market_value){
	//$extra.=" ,`market_value`='$market_value'";
}

else{
	$extra.=" ,`market_value`=NULL";
}

if($est_rep_cost !== NULL ){
	$extra.=" ,`est_rep_cost`='$est_rep_cost'";
}

if($est_parts_cost !== NULL ){
	$extra.=" ,`est_parts_cost`='$est_parts_cost'";
}

if($unf_rep_cost !== NULL ){
	$extra.=" ,`unf_rep_cost`='$unf_rep_cost'";
}

if($est_parts_cost !== NULL ){
	$extra.=" ,`est_parts_cost`='$est_parts_cost'";
}

if($unf_parts_cost !== NULL ){
	$extra.=" ,`unf_parts_cost`='$unf_parts_cost'";
}

if($wreck_value !== NULL ){
	$extra.=" ,`wreck_value`='$wreck_value'";
}

if($mileage){
	$extra.=" ,`mileage`='$mileage'";
}

if($opendt!==''){
	$extra.=" , `open_time`='$opendt'";
}



if($status_id==11){
	// CLosed Pending Review
	$sql="SELECT * FROM survey WHERE id='$id'";
	$rs=mysql_query($sql);
	$row=mysql_fetch_array($rs);
	$current_status=$row['status_id'];
	$current_close_time=$row['close_time'];
//	if($current_status!=11 && trim($current_close_time)===''){
	if(trim($current_close_time)===''){
		$closeddt = date('m-d-Y G:i');
	}
}



$sql="SELECT * FROM survey WHERE id='$id'";

$rs=mysql_query($sql);

$row=mysql_fetch_array($rs);

$calculation=0;

$calculation_t='';

$approved_status=$row['approved'];



$call_client=0;

$call_client_t='';

if(isset($_POST['call_client']) && $row['call_client']==0){

	$call_client=1;

	$call_client_t=date("n-j-Y G:i");

	$extra = $extra." , `call_client`=$call_client , `call_client_t`='$call_client_t' ";

}

else if(!isset($_POST['call_client'])){

	$extra = $extra." , `call_client`=0 , `call_client_t`='' ";

}



$parts_list=0;

$parts_list_t='';

if(isset($_POST['parts_list']) && $row['parts_list']==0){

	$parts_list=1;

	$parts_list_t=date("n-j-Y G:i");

	$extra = $extra." , `parts_list`=$parts_list , `parts_list_t`='$parts_list_t' ";

}

else if(!isset($_POST['parts_list'])){

	$extra = $extra." , `parts_list`=0 , `parts_list_t`='' ";

}



$quotation=0;

$quotation_t='';

if(isset($_POST['quotation']) && $row['quotation']==0){

	$quotation=1;

	$quotation_t=date("n-j-Y G:i");

	$extra = $extra." , `quotation`=$quotation , `quotation_t`='$quotation_t' ";



	//Mail notification



	$sql2 = "SELECT * FROM service_req WHERE id = '".$row['service_req_id']."'";

	$rs2 = mysql_query($sql2);

	$row2 = mysql_fetch_array($rs2);



	$sql11="SELECT * FROM `adjuster` WHERE `id`='".$row['adjuster_id']."'";

	$rs11=mysql_query($sql11);

	$row11=mysql_fetch_array($rs11);



	$sql12 = "SELECT * FROM rental_request WHERE id=".$row2['claimsAttId'];

	$rs12=mysql_query($sql12);

	$row12=mysql_fetch_array($rs12);


	$to=get_country_email($_SESSION['country']);
	require_once('phpmailer/PHPMailerAutoload.php');

	$mail = new PHPMailer();
  $mail->IsSMTP(); // we are going to use SMTP
	$mail->Host       = 'cscentral102.accountservergroup.com';      // setting GMail as our SMTP server
	$mail->Port		  = email_port;
	$mail->SMTPAuth   = true; // enabled SMTP authentication
	$mail->Username   = email_user;  // user email address
	$mail->Password   = email_password;            // password in GMail
	$mail->SetFrom($from,'Nagico '.$_SESSION['country'].' Claims');
	$mail->AddReplyTo($to,'Nagico '.$_SESSION['country'].' Claims');  //email address that receives the response

  $mail->Subject    = 'Quotation Ready Notification Survey# '.$id;
	$mail->AddEmbeddedImage("images/e-layout/dg.png", "dg", "dg.png");
	$mail->AddEmbeddedImage("images/e-layout/head.png", "head", "head.png");
	$mail->AddEmbeddedImage("images/e-layout/lside.png", "lside", "lside.png");
	$mail->AddEmbeddedImage("images/e-layout/bcontact.PNG", "bcontact", "bcontact.png");
	$mail->AddEmbeddedImage("images/e-layout/bbar.png", "bbar", "bbar.png");
 	$mail->Body = '<table width="800" cellspacing="0" cellpadding="0">



    <tr valign="top">

    	<td><img src="cid:dg" width="180" height="96">

        </td>

    	<td colspan="2"><img src="cid:head" width="620" height="96"></td>

   	</tr>

    <tr>

    	<td rowspan="4">&nbsp;</td>

   	</tr>

    <tr><td colspan="2" style="font-family:Verdana;font-size:12px">

	<br/>

	Dear Team,<br>

	<br>

	Please be advice that the quotation for survey# <a href="https://roadservice.nagico-abc.com/roadservice/survey.php?sid='.$id.'">'.$id.'</a> is ready<br>

	<br>

	Adjuster: '.$row11['name'].'<br>

	Claim Handler: '.$row12['name'].'<br>

	Claim Number: '.$row2['claimNo'].'<br>

	<br>

	Regards,<br>

	Adjuster Department

	</td></tr>

    <tr valign="bottom"><td colspan="2"><img src="cid:bcontact" width="620"/></td></tr>

</table>

<img src="cid:bbar" width="800" style="position:absolute; left:50px; top:450px"/>';

    $mail->AltBody    = '123';

	$sqlt = "SELECT * FROM survey_notification";
	$rst = mysql_query($sqlt);
	while ($rowt = mysql_fetch_array($rst)){
			$mail->AddAddress($rowt['email'],$rowt['name']);
	}

  //$mail->AddAttachment($file_path);      // some attached files/

	$mail->Send();



}

else if(!isset($_POST['quotation'])){

	$extra = $extra." , `quotation`=0 , `quotation_t`='' ";

}



if(isset($_POST['est_report']) && $row['est_report_t']==0){

	$t=date("n-j-Y G:i");

	$extra .= " , `est_report_t`='$t' ";

}

else if(!isset($_POST['est_report'])){

	$extra .= " , `est_report_t`='' ";

}



if(isset($_POST['neg_review']) && $row['neg_review_t']==0){

	$t=date("n-j-Y G:i");

	$extra .= " , `neg_review_t`='$t' ";

}

else if(!isset($_POST['neg_review'])){

	$extra .= " , `neg_review_t`='' ";

}



if(isset($_POST['survey_vehicle']) && $row['survey_vehicle_t']==0){

	$t=date("n-j-Y G:i");

	$extra .= " , `survey_vehicle_t`='$t' ";

}

else if(!isset($_POST['survey_vehicle'])){

	$extra .= " , `survey_vehicle_t`='' ";

}



if(isset($_POST['calculation2']) && $row['calculation_t']==0){

	$t=date("n-j-Y G:i");

	$extra = $extra." ,  `calculation_t`='$t' ";

}

else if(!isset($_POST['calculation2'])){

	$extra = $extra." , `calculation_t`='' ";

}



if(isset($_POST['parts_prices']) && $row['parts_prices_t']==0){

	$t=date("n-j-Y G:i");

	$extra = $extra." ,  `parts_prices_t`='$t' ";

}

else if(!isset($_POST['parts_prices'])){

	$extra = $extra." , `parts_prices_t`='' ";

}



if($_POST['est_nrs_cost']!=='' && $_POST['est_nrs_cost']!==NULL){

	$extra .= " , `est_nrs_cost`=".$_POST['est_nrs_cost'];

}



if($_POST['est_cl_cost']!=='' && $_POST['est_cl_cost']!==NULL){

	$extra .= " , `est_cl_cost`=".$_POST['est_cl_cost'];

}





$extra .=" , `days_rep`=$days_rep";









$bodyshop_app=0;

$bodyshop_app_t='';

if(isset($_POST['bodyshop_app']) && $row['bodyshop_app']==0){

	$bodyshop_app=1;

	$bodyshop_app_t=date("n-j-Y G:i");

	$extra = $extra." , `bodyshop_app`=$bodyshop_app , `bodyshop_app_t`='$bodyshop_app_t' ";

}

else if(!isset($_POST['bodyshop_app'])){

	$extra = $extra." , `bodyshop_app`=0 , `bodyshop_app_t`='' ";

	$bodyshop_app_date='';

}



if($_POST['delete']==='Delete' && $_SESSION['user_level']>=4){

	$sql="UPDATE survey SET `active`=0 WHERE id='$id'";
	recordLog($page,$id,$sql);
	mysql_query($sql);

	header("location: /roadservice/survey_list.php");

	exit;

}

else{

	$sql="SELECT * FROM `survey` WHERE id='$id'";

	$rs=mysql_query($sql);

	$row=mysql_fetch_array($rs);



	if($status_id==5){

	 //Close Pending Review

		$sql="SELECT * FROM `survey` WHERE id='$id'";
		$rs=mysql_query($sql);
		$row=mysql_fetch_array($rs);
		if($row['status_id']!=5){
			$from='claims@nagico-abc.com';
			//to is actualy reply to address
			$to=get_country_email($_SESSION['country']);
			$subject="Status Change Survey# ".$id." to Pending Review by ".getUserFName();
			require_once('phpmailer/PHPMailerAutoload.php');
			$mail = new PHPMailer();
			$mail->IsSMTP(); // we are going to use SMTP
			//$mail->SMTPDebug  = 1;
			$mail->Host       = 'mail.nagico-abc.com';      // setting GMail as our SMTP server
			$mail->Port		  = email_port;
			//$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth   = true; // enabled SMTP authentication
			$mail->Username   = email_user;  // user email address
			$mail->Password   = email_password;            // password in GMail
			$mail->SetFrom($from,'Nagico '.$_SESSION['country'].' Claims');
			$mail->AddReplyTo($to,'Nagico '.$_SESSION['country'].' Claims');  //email address that receives the response
			$mail->Subject    = $subject;
			$mail->IsHTML(true);
			$bt='Dear Team Member,
				</br>
				</br>
				The following survey status has been changed the Closed Pending Review: <a href="https://roadservice.nagico-abc.com/roadservice/survey.php?sid='.$id.'">'.$id.'</a> By '.getUserFName().' at '.date('l jS \of F Y h:i:s A').'
				</br>
				</br>
				Road Service
				';
				$mail->Body= $bt;

				$sqlt = "SELECT * FROM survey_notification";
				$rst = mysql_query($sqlt);
				while ($rowt = mysql_fetch_array($rst)){
					$mail->AddAddress($rowt['email'],$rowt['name']);
				}

				$mail->Send();
		}
	}



	if(isset($_POST['approve'])){

		$atime=date('m-d-Y G:i');

		$extra = $extra." , `approved`=1 , `approve_by`=".$_SESSION['user_id'].", approve_date='".$atime."', `status_id`=4 ";

		//Add 12/13/2016 do email after approval
		$from='claims@nagico-abc.com';

		// 5/7/2020 added to retrieve from database
		$to=get_country_email($_SESSION['country']);
		// end 5/7/2020 add

		$subject="Status Change Survey# ".$id." Approved";
		require_once('phpmailer/PHPMailerAutoload.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); // we are going to use SMTP
		//$mail->SMTPDebug  = 3;
		$mail->Host       = 'cscentral102.accountservergroup.com';      // setting GMail as our SMTP server
		$mail->Port		  = email_port;
		$mail->SMTPAuth   = true; // enabled SMTP authentication
		$mail->Username   = email_user;  // user email address
		$mail->Password   = email_password;            // password in GMail
		$mail->SetFrom($from,'Nagico '.$_SESSION['country'].' Claims');
		$mail->AddReplyTo($to,'Nagico '.$_SESSION['country'].' Claims');  //email address that receives the response
		$mail->Subject    = $subject;
		$mail->IsHTML(true);
		$bt='Dear Team Member,
		</br>
		</br>
		The following survey has been Approved: <a href="https://roadservice.nagico-abc.com/roadservice/survey.php?sid='.$id.'">'.$id.'</a> By '.getUserFName().' at '.date('l jS \of F Y h:i:s A').'
				</br>
				</br>
				Road Service
				';
		$mail->Body       = $bt;
		$sqlt = "SELECT * FROM survey_notification";
		$rst = mysql_query($sqlt);
		while ($rowt = mysql_fetch_array($rst)){
			$mail->AddAddress($rowt['email'],$rowt['name']);
		}

		$mail->Send();

	} // end if approved email
	else{
		$extra = $extra." , `status_id`=$status_id " ;
	}

	if($s_rep_date!==''){
		$extra = $extra." , `s_rep_date`='$s_rep_date' " ;
	}



	$phone=$_POST['phone'];
	$contact_pers=$_POST['contact_pers'];
	$location=$_POST['location'];
	if($approved_status){
		$sql = "UPDATE survey SET `manu_date`='$manu_date', `cond_ext`='$cond_ext', `cond_int`='$cond_int', `modification`='$modification', `close_time`='$closeddt', `notes`='$notes', `transmission`='$transmission', `phone`='$phone', `contact_person`='$contact_pers', `location`='$location', `survey_type_id`='$survey_type_id', `bodyshop_app_date`='$bodyshop_app_date', `damage_calculation_a`='$damage_calculation_a', `parts_status`='$parts_status', `conv_client`='$conv_client', `parts_loc`='$parts_loc',  `client_status`='$client_status' ".$extra." WHERE id = '$id'";

	}
	else{
		$sql = "UPDATE survey SET `manu_date`='$manu_date', `bodyshop_id`=$bodyshop_id, `cond_ext`='$cond_ext', `cond_int`='$cond_int', `modification`='$modification', `close_time`='$closeddt', `notes`='$notes', `transmission`='$transmission', `adjuster_id`=$adjuster_id, `phone`='$phone', `contact_person`='$contact_pers', `location`='$location', `survey_type_id`='$survey_type_id', `bodyshop_app_date`='$bodyshop_app_date', `damage_calculation_a`='$damage_calculation_a', `parts_status`='$parts_status', `conv_client`='$conv_client', `parts_loc`='$parts_loc',  `client_status`='$client_status' ".$extra." WHERE id = '$id'";
	}

	recordLog($page,$id,$sql);
	if(!(mysql_query($sql))){

		$ctime=date('m-d-Y G:i');

		$sqle="INSERT INTO `error` (`time`, `user`, `table`, `query`, `error`) VALUES ('$ctime', '".$_SESSION['user_name']."', 'survey', '$sql', '".mysql_error()."')";

		mysql_query($sqle);

	}

	//echo mysql_error().'<br/>'.$sql;

	$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));

	if(strcmp($ext,'pdf')==0){

		mkdir(FOLDER.'sdocs/'.$id);

		mkdir(FOLDER.'sdocsthumbs/'.$id);

		$path = FOLDER."sdocs/".$id.'/';

		$tu = FOLDER.'sdocsthumbs'.$id.'/';



		$fn = 1;

		$file_name = $id.'_'.$fn.'.'.$ext;

		$path = FOLDER."sdocs/".$id.'/';

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
			$im->readImage(FOLDER.'sdocs/'.$id.'/'.$name.'[0]');
			//$im->setImageCompression(imagick::COMPRESSION_JPEG);
			$im->setImageCompressionQuality(80);
			$im = $im->flattenImages();
			$im->resizeImage(300,300,Imagick::FILTER_LANCZOS,1, TRUE);
			$im->setImageFormat('jpeg');

			$im->writeImage(FOLDER.'sdocsthumbs/'.$id.'/'.substr($name,0,-4).'.jpeg');

			$im->clear();

			$im->destroy();

		}

	} //end if pdf

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

					mkdir(FOLDER.'sdocs/'.$id);

					$path = FOLDER."sdocs/".$id.'/';



					$fn = 1;

					$file_name = $id.'_'.$fn.'.'.$ext;

					$path = FOLDER."sdocs/".$id.'/';

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

						mkdir(FOLDER.'sdocsthumbs/'.$id);

						$im = new imagick();

						$im->setResolution(300,300);

						$im->readImage(FOLDER.'sdocs/'.$id.'/'.$name.'[0]');
					//	$im->setImageCompression(imagick::COMPRESSION_JPEG);
						$im->setImageCompressionQuality(80);
						$im = $im->flattenImages();
						$im->resizeImage(300,300,Imagick::FILTER_LANCZOS,1, TRUE);
						$im->setImageFormat('jpeg');
						$im->writeImage(FOLDER.'sdocsthumbs/'.$id.'/'.substr($name,0,-4).'.jpeg');
						$im->clear();
						$im->destroy();

					}

				}

				else if (strcmp($mime_type,"image/jpeg; charset=binary")==0 || strcmp($mime_type,"image/pjpeg; charset=binary")==0 || strcmp($mime_type,"image/gif; charset=binary")==0 || strcmp($mime_type,"image/x-png; charset=binary")==0 || strcmp($mime_type,"image/png; charset=binary")==0)

				{

					mkdir(FOLDER.'simage/'.$id);

					mkdir(FOLDER.'sthumbs/'.$id);



					$ext = end(explode('.', $dir.$temp_dir."/".$object));

					$fn = 1;

					$file_name = $id.'_'.$fn.'.'.$ext;

					$path = FOLDER."simage/".$id.'/';

					$patht = FOLDER."sthumbs/".$id.'/'; //thumb

					$remote_file = $path.$file_name;

					$remote_file_t = $patht.$file_name; //thumbs

					while(file_exists($remote_file)){

						$fn++;

						$file_name = $id.'_'.$fn.'.'.$ext;

						$remote_file = $path.$file_name;

						$remote_file_t = $patht.$file_name; //thumbs

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
					$im->readImage($dir.$temp_dir."/".$object);
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



	} //end if zip

	//**********************Image****************************

	else if (($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg" || $_FILES["image_upload_box"]["type"] == "image/gif" || $_FILES["image_upload_box"]["type"] == "image/x-png" || $_FILES["image_upload_box"]["type"] == "image/png") && ($_FILES["image_upload_box"]["size"] < 5000000))

	{



		mkdir(FOLDER.'simage/'.$id);

		mkdir(FOLDER.'sthumbs/'.$id);



		$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));

		$fn = 1;

		$file_name = $id.'_'.$fn.'.'.$ext;

		$path = FOLDER."simage/".$id.'/';

		$patht = FOLDER."sthumbs/".$id.'/'; //thumb

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
		/*
		$thumb = new SimpleImage();

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



	} //end if image



	$ext = end(explode('.', $_FILES["parts_list"]["name"]));

	if(strcmp($ext,'lin')==0){



		mkdir(FOLDER.'rrparts/'.$id);

		$target_file = FOLDER.'rrparts/'.$id.'/'. basename($_FILES["parts_list"]["name"]);

		if(move_uploaded_file($_FILES["parts_list"]["tmp_name"], $target_file)){

			$st=file_get_contents($target_file);

			$st=substr($st,1473); //Remove header from file; Fixed length

			$i=1;

			while(substr($st,0,4)==$i){

				$line_id= substr($st,0,4);

				$description=substr($st,25,40);

				$type=substr($st,65,3);

				$part_number=substr($st,71,25);

				$price_us=substr($st,99,9);

				$price_ov=substr($st,109,8);

				$st=substr($st,295); //Fixed lengt 295 per item

				$i++;

				if($type==='PAN' || $type==='PAA'){

					$sql3="INSERT INTO `survey_parts` (`survey_id`,`line_id`,`description`,`type`,`part_number`,`price_us`,`price_ov`) VALUES (

					'$id','$line_id','$description','$type','$part_number',$price_us,$price_ov)";

					mysql_query($sql3);

				}

			}

		}

	}

header("location: survey.php?sid=".$id);

exit;

}







?>
