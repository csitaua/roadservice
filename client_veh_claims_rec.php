<?php

date_default_timezone_set('America/Aruba');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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

$car = $_REQUEST['car'];
$num = $_REQUEST['num'];
$location = addslashes($_REQUEST['loc']);
$job = 15;
$notes = mysql_real_escape_string($_REQUEST['client_note']);
$status = 7;
$time = date('n-j-Y G:i:s');
$pol = $_REQUEST['pol'];
$opendt = $_REQUEST['opendt'];
$addphone = $_REQUEST['addphone'];
$vin = $_REQUEST['vin'];
$requestedBy = mysql_real_escape_string($_REQUEST['requestedBy']);
$veh_park_loc=$_REQUEST['loc'];
$veh_year=$_REQUEST['veh_year'];
$insured = 1;	
$contact_info_email=$_REQUEST['contact_info_email'];
$other_contact_phone=$_REQUEST['other_contact_phone'];


if(trim($veh_year)===''){
	$veh_year=0;	
}

$present = -1;
$rspresent = 0;





	
$sql = "INSERT INTO service_req (`car`, `a_number`, `location`, `job`, `insured`, `notes`, `status`, `timestamp`, `pol`,`opendt`, `user_id`,  `AddPhone` , `vin`, `requestedBy`, `veh_year`) VALUES ('$car', '$num', '$location', '$job', '$insured', '$notes', '$status', '$time', '$pol', '$opendt', '158', '".$addphone.' \ '.$other_contact_phone."', '$vin', '$requestedBy', '$veh_year')";
	mysql_query($sql);	
	$lastid = mysql_insert_id();
	
	//echo mysql_error().'<br/>'.$sql;
	//header("location: /roadservice/edit_sc.php?sc=".$lastid);
	
	if($ter==='Curacao'){
		define ("FOLDER","cur/");
	}
	else if($ter==='Sint Maarten'){
		define ("FOLDER","sxm/");
	}
	else{
		define ("FOLDER","");
	}
	$id=$lastid;
	$num_files=count($_FILES["uploadfiles"]['name']);
	include_once('support/simpleimage.php');
	for($i=0 ; $i<=$num_files ; $i++){
	$ext = end(explode('.', $_FILES["uploadfiles"]["name"][$i]));
	if(strcmp($ext,'pdf')==0){
		mkdir(FOLDER.'rrdocs/'.$id);
		mkdir(FOLDER.'rrdocsthumbs/'.$id);
		$path = FOLDER."rrdocs/".$id.'/';
		$tu = FOLDER.'rrdocsthumbs'.$id.'/';
		
		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = FOLDER."rrdocs/".$id.'/';
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
			$im->readImage('D:\\web\\roadservice\\'.FOLDER.'\\rrdocs\\'.$id.'\\'.$name.'[0]');
			$im->setCompressionQuality(80);
			$im->resizeImage(400,400,Imagick::FILTER_LANCZOS,1, TRUE);
			$im->setImageFormat('jpeg');
			$im->writeImage('D:\\web\\roadservice\\'.FOLDER.'\\rrdocsthumbs\\'.$id.'\\'.substr($name,0,-4).'.jpeg');
			$im->clear();
			$im->destroy();
		}
	}
	//**********************Image****************************
	else if (($_FILES["uploadfiles"]["type"][$i] == "image/jpeg" || $_FILES["uploadfiles"]["type"][$i] == "image/pjpeg" || $_FILES["uploadfiles"]["type"] [$i] == "image/gif" || $_FILES["uploadfiles"]["type"][$i] == "image/x-png" || $_FILES["uploadfiles"]["type"][$i] == "image/png") && ($_FILES["uploadfiles"]["size"][$i] < 5000000))
	{
		
		mkdir(FOLDER.'rrimage/'.$id);
		mkdir(FOLDER.'rrthumbs/'.$id);
		
		$ext = end(explode('.', $_FILES["uploadfiles"]["name"][$i]));
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
		$image->load($_FILES["uploadfiles"]["tmp_name"][$i]);
		$image->resizeToWidth(2600);
		$image->Save($remote_file);
		$thumb = new SimpleImage();
		$thumb->load($_FILES["uploadfiles"]["tmp_name"][$i]);
		$thumb->resizeToWidth(200);
		$thumb->Save($remote_file_t);

	}	
	}// END FOR LOOP UPLOAD
	
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

//Prepare claim form
$pol=$_REQUEST['pol'];
mkdir($path_e.'rranotification/'.$id);
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
$pdf->Output('d:\web\roadservice\\'.$path_e.'rranotification\\'.$id.'\\'.$pol.'_Claim_Notification.pdf', 'F');

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
$mail->AddAttachment('d:\web\roadservice\\'.$path_e.'rranotification\\'.$id.'\\'.$pol.'_Claim_Notification.pdf');

		
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
	echo 'success';
}



header("location: client_claims.php?su=1");
//echo mysql_error().'<br/>'.$sql;
exit;


?>