<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();
$rental_id=$_REQUEST['id'];
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//if($_SESSION['user_level'] < POWER_LEVEL && !checkView()) {
//header("Location: index.php");
//exit();
//}

$sql = "SELECT * FROM `rental` WHERE `id`='$rental_id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$vehicle = $row['rental_vehicle_id'];
$requestedBy = $row['requested_by'];
$requestedByid = 0;
$odo_out = $row['odo_out'];
$extra_name = $row['extra_name'];
$fuel_out = $row['fuel_out'];
$extra_drv = $row['extra_drv'];
$time_out = $row['time_out'];
$rate = $row['rate'];
$time_in = '';
$status = $row['status'];
$odo_in = $row['odo_in'];
$fuel_in = $row['fuel_in'];
$time_in = $row['time_in'];
$policy = $row['policy_no'];
$claimNo = $row['claimNo'];
$service_req_id = $row['service_req_id'];
$issued_location = $row['issued_location'];
$returned_location = $row['returned_location'];
$attendee = $row['attendee'];
$attendee_in = $row['attendee_in'];
$time_in_exp=$row['time_in_exp'];
$rental_company_id=$row['rental_company_id'];

$sql2="SELECT * FROM rental_company WHERE id=$rental_company_id";
$rs2=mysql_query($sql2);
$row2=mysql_fetch_array($rs2);

$sql3 = "SELECT * FROM drivers_license where id = '$extra_drv'";
$rs3 = mysql_query($sql3);
$row3 = mysql_fetch_array($rs3);
$r_name=$row3['firstName'].' '.$row3['lastName'];

$country=$_SESSION['country'];
$sql4="SELECT * FROM `country_info` WHERE country='$country'";
$rs4=mysql_query($sql4);
$row4=mysql_fetch_array($rs4);
$c1=$row4['rental_contact_1'];
$c1_phone=$row4['rental_contact_1_phone'];
$c2=$row4['rental_contact_2'];
$c2_phone=$row4['rental_contact_2_phone'];

$sql5="SELECT * FROM `rental_vehicle` WHERE `id`='$vehicle'";
$rs5=mysql_query($sql5);
$row5=mysql_fetch_array($rs5);

$sql6 = "SELECT * FROM rental_request WHERE id=".$requestedBy;
$rs6=mysql_query($sql6);
$row6=mysql_fetch_array($rs6);


list($month,$day,$year) = explode('-',substr($time_out,0,10));
list($date,$time) = explode(' ',$time_out);
$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
$pdate= date("F j, Y", mktime(0, 0, 0, $month, $day, $year));

if(strlen(trim($time_in_exp))!=0){
	list($month,$day,$year) = explode('-',substr($time_in_exp,0,10));
	list($date,$time) = explode(' ',$time_in_exp);
	$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
	$rdate= date("F j, Y", mktime(0, 0, 0, $month, $day, $year));
}
$interval = $date1->diff($date2);
if($interval->h==0){
	$r_days=$interval->days;
}
else{
	$r_days=$interval->days+1;
}
//$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);



$height=5;
	require_once('tcpdf/tcpdf.php');

	$pdf = new TCPDF('P', 'mm', 'letter', true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Insurances');
	$pdf->SetTitle('Nagico Quotation');
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(false,0);

	$pdf->SetFont('myriad', 'B', 26);
	$pdf->AddPage();

	$pdf->Image('images/nagico-logo.jpg',10,10,69,0,'','','',true);

	$pdf->Ln(20);
	$pdf->SetFont('myriad', 'B', 12);
	$pdf->Cell(120,$height,'');$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(0,$height,'Date','',1);
	$pdf->SetFont('myriad', '', 12);$pdf->Cell(30,$height,'');$pdf->Cell(90,$height,$row2['name']);$pdf->SetFont('myriad', '', 10);$pdf->Cell(0,$height,date('j F, Y'),'',1);
	$pdf->SetFont('myriad', '', 10);$pdf->Cell(3,$height,'');$pdf->Cell(27,$height,'Directed To:');$pdf->SetFont('myriad', '', 12);$pdf->Cell(90,$height,$row2['address1']);$pdf->Cell(0,$height,'','',1);
	$pdf->SetFont('myriad', '', 12);$pdf->Cell(30,$height,'');$pdf->Cell(90,$height,$row2['address2']);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(0,$height,'Address:','',1);
	$pdf->SetFont('myriad', '', 10);$pdf->Cell(30,$height,'');$pdf->Cell(90,$height,'');$pdf->Cell(0,$height,$row4['address1'],'',1);
	$pdf->Cell(30,$height,'');$pdf->Cell(90,$height,'');$pdf->Cell(0,$height,$row4['state'].', '.$row4['country'],'',1);
	$pdf->Ln($height);
	$pdf->SetFont('myriad', '', 10);
	$pdf->Cell(120,$height,'');$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(0,$height,'Contact Person:','',1);
	$pdf->Cell(30,$height,'Our Reference:');$pdf->SetFont('myriad', '', 10);$pdf->Cell(90,$height,'Purchase Order # '.$rental_id);$pdf->Cell(0,$height,$c1,'',1);
	$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(30,$height,'Claim#:');$pdf->SetFont('myriad', '', 10);$pdf->Cell(90,$height,$claimNo);$pdf->Cell(0,$height,$c2,'',1);
	$pdf->Ln($height);
	$pdf->Cell(121,$height,'');$pdf->writeHTML('<b>T:</b> '.$c1_phone,1);
	$pdf->Cell(121,$height,'');$pdf->writeHTML('<b>T:</b> '.$c2_phone,1);
	$pdf->Ln(3*$height);
	$pdf->SetFont('myriad', '', 12);
	/*$pdf->MultiCell(0,$height,'Dear Madam, Sir,
<br/>
<br/>
<br/>
Can you please provide '.$r_name.' with a rental vehicle for a period of '.$r_days.' days.
<br/>
<br/>
If extension is needed we will contact you and make a new purchase order.
<br/>
<br/>
Trust to have informed you accordingly.',0,'L',0,1,'','','','',1); */
$pdf->writeHTML('Dear Madam, Sir,
<br/>
<br/>
<br/>
Kindly provide a rental vehicle to:
<br/>
<br/>
<table cellpadding="3">
	<tr>
		<td width="20">&nbsp;</td> <td width="100"><b>&#8226; Driver:</b></td> <td>'.$r_name.'</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td> <td width="100"><b>&#8226; Vehicle Type:</b></td> <td>'.$row5['make'].' '.$row5['model'].'</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td> <td width="100"><b>&#8226; Rental Period:</b></td> <td>'.$r_days.' Day(s)</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td> <td width="100"><b>&#8226; Pick-up Date:</b></td> <td>'.$pdate.'</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td> <td width="100"><b>&#8226; Return Date:</b></td> <td>'.$rdate.'</td>
	</tr>
	<tr>
		<td width="20">&nbsp;</td> <td colspan="2" style="font-size:8px; font-style:italic;">* Rental vehicle should be returned on or before the <b>return date.</b></td>
	</tr>
</table>
<br/>
<br/>
May you have any question in this regard, please advise. Your usual kind cooperation is much appreciated.',true,false,true,false,'');

	$pdf->Ln(8*$height);
	$x=$pdf->GetX();
	$y=$pdf->GetY();
	$pdf->SetFont('myriad', '', 11);
	$pdf->Image('signature/detlef.gif',$x+12,$y-37,22,0,'','','',true);
	$pdf->Image('signature/STAMP.png',$x+5,$y-37,40,0,'','','',true);
	$pdf->Cell(45,$height,'Detlef J. G. Hooyboer','T',0);$pdf->Cell(85,$height,'');$pdf->Cell(0,$height,'Prepared By: '.$row6['name'],'',1);
	$pdf->SetFont('myriad', 'B', 11);
	$pdf->Cell(45,$height,'Managing Director','',1);
	$pdf->SetFont('myriad', 'B', 12);
	$pdf->Cell(45,$height,'NAGICO '.$country,'',1);
	$pdf->SetY(-5);
	$pdf->SetFont('myriad', '', 8);
	$pdf->Cell(0,$height,date('YmdHi'),'',0,'R');

	if($row3['loc']!==''){
		$pdf->AddPage();
		$img = file_get_contents($row3['loc']);
		$pdf->Image('@'.$img,10,10,0,80);
	}

		mkdir(FOLDER.'rentalpo/'.$rental_id);
	$path=FOLDER.'rentalpo/'.$rental_id.'/';
		ob_clean();
	$pdf->Output(__DIR__.'/'.$path.$rental_id.'_Rental_PO.pdf', 'F');

	require_once('phpmailer/PHPMailerAutoload.php');
	$mail = new PHPMailer();
	$mail->IsSMTP(); // we are going to use SMTP
	$mail->Host       = 'mail.nagico-abc.com';      // setting GMail as our SMTP server
	$mail->Port		  = email_port;
	//$mail->SMTPSecure = 'tls';
	//$mail->SMTPDebug  = 1;
	$mail->SMTPAuth   = true; // enabled SMTP authentication
	$mail->Username   = email_user;  // user email address
	$mail->Password   = email_password;            // password in GMail
	$mail->SetFrom('claims@nagico-abc.com','Nagico '.$country.' Claims');
	$mail->AddReplyTo(getClaimsEmail(),'Nagico '.$country.' Claims');  //email address that receives the response
	$mail->Subject    = 'Rental Purchase Order '.$rental_id;
	$mail->IsHTML(true);
	$mail->AddAttachment($path.$rental_id.'_Rental_PO.pdf',$rental_id.'_Rental_PO.pdf');

	$bt='Dear Madam, Sir,
				</br>
				</br>
				Please find attached purchase order '.$rental_id.' for a rental of a vehicle.
				<br>
				<br>
				Regards,<br>
<br>
				NAGICO '.$country.' (Claims Department)
				';

	$mail->Body       = $bt;
	//$mail->AddAddress($to, "Claims Aruba");
	$mail->AddAddress($row2['email1'],$row2['email1']);
	if($row2['email2']!==''){
		$mail->AddAddress($row2['email2'],$row2['email2']);
	}
	if($row2['email3']!==''){
		$mail->AddAddress($row2['email3'],$row2['email3']);
	}
	$mail->AddAddress(getClaimsEmail(),'Claims '.$country);
	if($country==='Aruba'){
		$mail->AddAddress('garienne.cham@nagico.com','Garienne Cham');
	}

	$mail->Send();
	header("Location: rental_detail.php?id=".$rental_id);


?>
