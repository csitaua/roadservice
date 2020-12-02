<?php


date_default_timezone_set('America/Aruba');

$ter="Sint Maarten";
$host="192.168.5.24"; // Host name
$db_username="web"; // Mysql username
$db_password="O&8Bd0&iq;A-"; // Mysql password
if($ter==='Sint Maarten'){
	define ("DB_NAME","roadservice_sxm"); // set database name
	define ("HOST_INSPRO", "192.168.5.103");
	define ("FOLDER", "sxm/");
	$serverName = "192.168.5.103"; 
}
include ('db-info.inc');
$db1 = mysql_connect("$host", "$db_username", "$db_password")or die("cannot connect");
mysql_select_db(DB_NAME, $db1)or die("unable to access database");

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
$pol = $_REQUEST['pol']; //
$opendt = $_REQUEST['date_of_loss']; //
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
$contact_info_email=$_REQUEST['contact_info_email'];
$other_dam=$_REQUEST['other_dam'];
$mit_risk_info=$_REQUEST['mit_risk_info'];
$direction=$_REQUEST['direction'];
$client_note=$_REQUEST['client_note'];
$roof_dam_info=$_REQUEST['roof_dam_info'];
$wall_dam_info=$_REQUEST['wall_dam_info'];
$window_dam_info=$_REQUEST['window_dam_info'];
$content_dam_info=$_REQUEST['content_dam_info'];
$other_ins_info=$_REQUEST['other_ins_info'];
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
$roof_dam=0;
$content_dam=0;
$wall_dam=0;
$window_dam=0;
$mit_risk=0;
if(isset($_REQUEST['mit_risk'])){
	$mit_risk = 1;	
}
if(isset($_REQUEST['roof_dam'])){
	$roof_dam = 1;	
}
if(isset($_REQUEST['content_dam'])){
	$content_dam = 1;	
}
if(isset($_REQUEST['wall_dam'])){
	$wall_dam = 1;	
}
if(isset($_REQUEST['window_dam'])){
	$window_dam = 1;	
}
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
	
	
	$sql = "INSERT INTO service_gc (`location`, `job`, `insured`, `notes`, `status`, `timestamp`,`pol`,`opendt`, `user_id`, `master_sc`, `AddPhone`, `ClientNo`, `vin`,`to_location`, `insured_at`, `requestedBy`, `roof_dam`, `wall_dam`, `window_dam`, `content_dam`, `other_dam`, `mit_risk`, `mit_risk_info`, `direction`, `client_note`, `other_contact_phone`, `contact_info_email`, `roof_dam_info`, `wall_dam_info`, `content_dam_info`, `window_dam_info`, `other_ins_info`) VALUES ('$location', '$job','$insured', '$notes', '1', '$time', '$pol', '$opendt', '158', '$idm', '$addphone', '$clientno', '$vin', '$to_location', '$_REQUEST[insured_at]', '$requestedBy', '$roof_dam', '$wall_dam', '$window_dam', '$content_dam', '$other_dam', '$mit_risk', '$mit_risk_info', '$direction', '$client_note', '$other_contact_phone' ,'$contact_info_email', '$roof_dam_info', '$wall_dam_info', '$content_dam_info', '$content_dam_info', '$other_ins_info')";
	mysql_query($sql);	
	$lastid = mysql_insert_id();	
	
	if($ter==='Curacao'){
		$path_e='cur/';
		$path_e2='cur//';
	}
	else if($ter==='Sint Maarten'){
		$path_e='sxm/';
		$path_e2='sxm//';
	}
	else{
		$path_e='';
		$path_e2='';
	}
	$id=$lastid;
	
	$num_files=count($_FILES["uploadfiles"]['name']);
	include_once('support/simpleimage.php');
	for($i=0 ; $i<=$num_files ; $i++){
	$ext = end(explode('.', $_FILES["uploadfiles"]["name"][$i]));
	if(strcmp($ext,'pdf')==0){
		mkdir($path_e.'gcdocs/'.$id);
		mkdir($path_e.'gcdocsthumbs/'.$id);
		$path = $path_e."gcdocs/".$id.'/';
		$tu = $path_e.'gcdocsthumbs'.$id.'/';
		
		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = $path_e."gcdocs/".$id.'/';
		$name = $_FILES["uploadfiles"]["name"][$i];
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
			move_uploaded_file($_FILES['uploadfiles']['tmp_name'][$i], $remote_file);
			
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
	//**********************Image****************************
	else if (($_FILES["uploadfiles"]["type"][$i] == "image/jpeg" || $_FILES["uploadfiles"]["type"][$i] == "image/pjpeg" || $_FILES["uploadfiles"]["type"][$i] == "image/gif" || $_FILES["uploadfiles"]["type"][$i] == "image/x-png" || $_FILES["uploadfiles"]["type"][$i] == "image/png") && ($_FILES["uploadfiles"]["size"][$i] < 5000000))
	{
		
		mkdir($path_e.'gcimage/'.$id);
		mkdir($path_e.'gcthumbs/'.$id);
		
		$ext = end(explode('.', $_FILES["uploadfiles"]["name"][$i]));
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
		
		$image = new SimpleImage();
		$image->load($_FILES["uploadfiles"]["tmp_name"][$i]);
		$image->resizeToWidth(2600);
		$image->Save($remote_file);
		$thumb = new SimpleImage();
		$thumb->load($_FILES["uploadfiles"]["tmp_name"][$i]);
		$thumb->resizeToWidth(200);
		$thumb->Save($remote_file_t);

	}	
	} //end for loop images
	
//Prepare claim form
$pol=$_REQUEST['pol'];
mkdir($path_e.'rrgnotification/'.$id);
require_once('tcpdf/tcpdf.php');
		
$pagelayout = array(216, 279.4);
$pdf = new TCPDF('P', 'mm', $pagelayout, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nagico Insurances');
$pdf->SetTitle('Nagico Claim Notification');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->setAutoPageBreak(false,0);	

$dl=substr($opendt,0,10);
list($m,$d,$y)=explode("-",$dl);
		
$pdf->AddPage();
$pdf->Image('images/C_Notification_FrontPage.jpg',0,0,216,279.4,'','','',true,200);	
$pdf->SetFont('myriad', '', 18);	
$pdf->Ln(120);
//$pdf->Cell(0,$height,'Accident Date',0,1,'C');
$pdf->writeHTML('<span style="color:#808080">Date Of Loss</span>',true,false,true,false,'C');
$pdf->writeHTML('<span style="color:#000000">'.date("F j, Y", mktime(0, 0, 0, $m, $d, $y)).'</span>',true,false,true,false,'C');
$pdf->Ln(10);
$pdf->writeHTML('<span style="color:#808080">Policy Number</span>',true,false,true,false,'C');
$pdf->writeHTML('<span style="color:#000000">'.$pol.'</span>',true,false,true,false,'C');
$pdf->Ln(10);
$pdf->writeHTML('<span style="color:#808080">Name</span>',true,false,true,false,'C');
$pdf->writeHTML('<span style="color:#000000">'.$_REQUEST['requestedBy'].'</span>',true,false,true,false,'C');

$pdf->SetFont('myriad', '', 10);
$pdf->AddPage();

$sql2 = "SELECT * FROM jobs_gc WHERE id='".$_REQUEST['job']."'";
$rs2 = mysql_query($sql2);
$row2 = mysql_fetch_array($rs2);
$dtype=$row2['description'];

$height=10;
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Insured Name: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$requestedBy.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Insured Phone: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$addphone.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Insured Email: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$contact_info_email.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Insured Second Phone: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$other_contact_phone.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Location/Address: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$_REQUEST['loc'].'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Is the property covered by any other insurance?: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$_REQUEST['other_ins_info'].'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Disaster Type: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$dtype.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Damage Type: </span>');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Roof '.$roof_dam_info.'</span>',0,0);$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Walls '.$wall_dam_info.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080"></span>');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Window '.$window_dam_info.'</span>',0,0);$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Content '.$content_dam_info.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080"></span>');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Other '.$other_dam.'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Action taken to mitigate damages: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$_REQUEST['mit_risk_info'].'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Direction to location: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$_REQUEST['direction'].'</span>',0,1);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(50,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Provide additional details of the damages: </span>');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">'.$_REQUEST['client_note'].'</span>',0,1);
$pdf->Ln(10);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080"><b>VERY IMPORTANT - FRAUDULENT AND EXAGGERATED CLAIMS</b><br/><br/>The above answers to our questions will be the basis of consideration of your claim. You must ensure that all information is true and correct to the best of your knowledge and belief, and that all material facts have been disclosed. A material fact is one that is likely to influence us in the assessment or acceptance of this claim, or one that is likely to influence our consideration of cover under the terms of your policy. If you are in any doubt as to whether a fact is material, you must disclose it.<br/><br/>FAILURE TO DO THIS MAY MEAN THAT YOUR POLICY BECOMES INVALID AND A CLAIM PAYMENT WILL NOT BE MADE.<br/><br/><b>DECLARATION</b><br/><br/> '.$requestedBy.' declares the foregoing particulars to be correct according to my information and belief. '.$requestedBy.' understand that you may seek information from other insurers to check the answers I/we have provided.</span>',0,1);

list($m,$d,$y)=explode("-",$time);
$y=substr($y,0,4);
$tt=substr($time,-8);
list($h,$m,$s)=explode(":",$tt);
$pdf->Ln(10);
$pdf->Cell(10,$height,'');$pdf->writeHTMLCell(0,$height,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Signed by '.$requestedBy.'<br/>Date: '.date('F j,Y G:i:s').'</span>',0,1);


$pdf->SetXY(0,-7);
$pdf->Cell(0,$height,'Page - 1',0,0,'R');

$pol=str_replace('/','-',$pol);
$pdf->Output('d:\web\roadservice\\'.$path_e.'rrgnotification\\'.$id.'\\'.$pol.'_Claim_Notification.pdf', 'F');

//Send email notification
require_once('phpmailer/PHPMailerAutoload.php');
define ("email_password","dn8tYqX3j8hHkjye");
define ("email_port","26");
define ("email_user","claims@nagico-abc.com");
$mail = new PHPMailer();
$mail->IsSMTP(); // we are going to use SMTP
$mail->Host       = 'mail.nagico-abc.com';      // setting GMail as our SMTP server
$mail->Port		  = email_port;
$mail->SMTPAuth   = true; // enabled SMTP authentication
$mail->Username   = email_user;  // user email address
$mail->Password   = email_password;            // password in GMail
$mail->SetFrom('claims@nagico-abc.com','Nagico Aruba Claims'); 
$mail->AddReplyTo('claims-abc@nagico.com','Nagico ABC Claims');  //email address that receives the response
$mail->Subject    = 'Confirmation Claim Notification';
$mail->IsHTML(true);
$mail->AddAttachment('d:\web\roadservice\forms\claim_next_step_v1.pdf');
$mail->AddAttachment('d:\web\roadservice\\'.$path_e.'rrgnotification\\'.$id.'\\'.$pol.'_Claim_Notification.pdf');
		
$bt='Dear '.$requestedBy.',
						</br>
						</br>
						We have received your claim notification. <br>
						<br>
						Please review attached documents for additional information.<br>
						<br>
						Best Regards,<br>
		<br>
						NAGICO Aruba and Curacao claims team
						';
$mail->Body       = $bt;
$mail->AddAddress($contact_info_email,$requestedBy);
if($mail->Send()){
	//email send
}



//header("location: /roadservice/edit_gc.php?sc=".$id);
//echo mysql_error().'<br/>'.$sql;
header("location: client_claims.php");
exit;
}



?>