<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include 'dbc.php';

date_default_timezone_set('America/Aruba');

page_protect();

include "support/connect.php";

include "support/function.php";

session_start();



if(0) {

header("Location: index.php");

exit();

}

else{



	$sql = "SELECT * FROM `rental` WHERE `id`='$_REQUEST[id]'";

	$rs = mysql_query($sql);

	$row = mysql_fetch_array($rs);

	$vehicle = $row['rental_vehicle_id'];

	$requestedBy = $row['requested_by'];

	$requestedByid = 0;

	$odo_out = $row['odo_out'];

	$extra_name = $row['extra_name'];

	$fuel_out = $row['fuel_out'];

	$extra_drv = $row['extra_drv'];

	$claimNo=$row['claimNo'];

	$requestedBy = $row['requested_by'];

	$requestedByid = 0;

	$time_out = $row['time_out'];

	$time_in = $row['time_in'];
	$time_in_exp = $row['time_in_exp'];
	$odo_in = $row['odo_in'];

	$odo_out = $row['odo_out'];

	$attendee = $row['attendee'];

	$attendee_in = $row['attendee_in'];

	$isTPRental=isThirdPartyRental($row['rental_company_id']);



	if(is_numeric($requestedBy)){

		$sql2 = "SELECT * FROM rental_request WHERE id='$requestedBy'";

		$rs2 = mysql_query($sql2);

		$row2 = mysql_fetch_array($rs2);

		$requestedByid = $requestedBy;

		$requestedBy = $row2['name'];

	}



	$sql11 = "SELECT * FROM service_req WHERE id = '".$row['service_req_id']."'";

	$rs11 = mysql_query($sql11);

	$row11 = mysql_fetch_array($rs11);



	$sql2 = "SELECT * FROM rental_attendee WHERE id=$attendee";

	$rs2 = mysql_query($sql2);

	$row2=mysql_fetch_array($rs2);

	$a_out=$row2['name'];



	$sql2 = "SELECT * FROM rental_attendee WHERE id=$attendee_in";

	$rs2 = mysql_query($sql2);

	$row2=mysql_fetch_array($rs2);

	$a_in=$row2['name'];



	$sql2 = "SELECT * FROM `rental_vehicle` WHERE `id`='$vehicle'";

	$rs2 = mysql_query($sql2);

	$row2=mysql_fetch_array($rs2);

	$rlicensePlate=$row2['licenseplate'];

	$rmake=$row2['make'];

	$rmodel=$row2['model'];



	$sql3 = "SELECT * FROM drivers_license where id = '$extra_drv'";

	$rs3 = mysql_query($sql3);

	$row3 = mysql_fetch_array($rs3);



	$claimNo=$row['claimNo'];

	$sql13 = "SELECT * FROM VW_CLAIMS WHERE ClaimNo='$claimNo'";

	//$rs13= sqlsrv_query($conn,$sql13);

	//$row13 = sqlsrv_fetch_array($rs13);

	$rs13= mssql_query($sql13);
	$row13 = mssql_fetch_array($rs13);



	$ddate= new datetime(substr($row13['Date_Loss'],0,10));







	require_once('tcpdf/tcpdf.php');

	$id = $_REQUEST['id'];

	$height=6;



	$pagelayout = array(216, 279.4);

	$pdf = new TCPDF('P', 'mm', $pagelayout, true, 'UTF-8', false);



	$pdf->SetCreator(PDF_CREATOR);

	$pdf->SetAuthor('Nagico Road Service');

	$pdf->SetTitle('Rental Form');

	$pdf->SetSubject('Rental Form');



	$pdf->setPrintHeader(false);

	$pdf->setPrintFooter(false);

	$pdf->setAutoPageBreak(true,10);



	$pdf->AddPage();

	$pdf->SetMargins(10,10,10,true);

	$pdf->Image('images/nagico-rr-court.jpg',130.5,10,75.5,0,'','','',true);

	$sql = "SELECT * FROM rental WHERE `id`='$id'";

	$rs = mysql_query($sql);

	$row = mysql_fetch_array($rs);

	$sql2 = "SELECT * FROM rental_vehicle WHERE id='".$row['rental_vehicle_id']."'";

	$rs2 = mysql_query($sql2);

	$row2 = mysql_fetch_array($rs2);



	$x=$pdf->GetX();

	$y=$pdf->GetY();

	$pdf->Sety(5);

	$pdf->SetFont('myriad', 'b', 12);

	$pdf->Cell(0,5,'Rental ID: '.$_REQUEST['id'],0,0,'R');

	$pdf->SetX($x);

	$pdf->SetY($y);





	$pdf->SetFont('myriad', 'B', 16);

	$pdf->SetFillColor(2,132,67);

	$pdf->SetTextColor(255,255,255);

	$pdf->Cell(95.5,$height,'Courtesy Car Inspection','',1,'C',1);

	$pdf->SetFillColor(255,255,255);

	$pdf->SetTextColor(0,0,0);

	$pdf->SetFont('myriad', 'B', 14);

	$pdf->Cell(95.5,$height,'Customer Details','',1);



	$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Customer Name','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$row3['firstName'].' '.$row3['lastNamec'],'',1);$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Address','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$row3['address'],'',1);$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Phone','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$row3['mobile'],'',1);$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Email','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$row3['email'],'',1);$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Driver License','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$extra_drv,'',1);$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Claims Attendee','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$requestedBy,'',1);$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Claims Number','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(50.5,$height,$claimNo,'',1);$pdf->SetFont('myriad', '', 10);



	$pdf->Ln(4);



	$pdf->Cell(25,$height,'Plate Number: ','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(40,$height,$rlicensePlate,'',0);$pdf->SetFont('myriad', '', 10);$pdf->Cell(25,$height,'Make:','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(40,$height,$rmake,'',0);$pdf->SetFont('myriad', '', 10);$pdf->Cell(25,$height,'Plate Number: ','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(40,$height,$rmodel,'',1);$pdf->SetFont('myriad', '', 10);



	$pdf->Ln(4);



	$pdf->SetFont('myriad', 'B', 16);

	$pdf->Cell(45,$height,'Car Out','B',0);$pdf->Cell(45.5,$height,'','B',0);$pdf->Cell(5,$height,'','',0);$pdf->Cell(45,$height,'Car In','B',0);$pdf->Cell(50.5,$height,'','B',1);

	$pdf->Ln(5);

	$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(20,$height,'Date','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(27.75,$height,substr($time_out,0,10),'',0);$pdf->SetFont('myriad', '', 10);$pdf->Cell(20,$height,'Time','',0);$pdf->SetFont('myriad', 'B', 10);$pdf->Cell(27.75,$height,substr($time_out,-5),'',0);$pdf->SetFont('myriad', '', 10);$pdf->Cell(20,$height,'Date','',0);$pdf->Cell(27.75,$height,substr($time_in,0,10),'B',0);$pdf->Cell(20,$height,'Time','',0);$pdf->Cell(27.75,$height,substr($time_in,-5),'B',1);



	$pdf->Cell(20,$height,'Mileage','',0);$pdf->Cell(27.75,$height,'','B',0);$pdf->Cell(20,$height,'Fuel Out','',0);$pdf->Cell(27.75,$height,'','B',0);$pdf->Cell(20,$height,'Mileage','',0);$pdf->Cell(27.75,$height,'','B',0);$pdf->Cell(20,$height,'Fuel In','',0);$pdf->Cell(27.75,$height,'','B',1);

	$pdf->Ln(15);

	$pdf->Cell(45,$height,'Checked By','',0);$pdf->Cell(50.5,$height,$a_out,'T',0);$pdf->Cell(45,$height,'Checked By','',0);$pdf->Cell(50.5,$height,$a_in,'T',1);

	$pdf->Ln(5);

	$x=$pdf->GetX();

	$y=$pdf->GetY();



	$pdf->SetFont('myriad', 'B', 16);

	$pdf->Cell(95.5,5,'Car Out',0,0);$pdf->Cell(0,5,'Car In',0,1);

	$pdf->SetFont('myriad', '', 9);

	$pdf->Cell(95.5,0,'S - Scratch',0,0);$pdf->Cell(0,0,'S - Scratch',0,1);

	$pdf->Cell(95.5,0,'D - Dent',0,0);$pdf->Cell(0,0,'D - Dent',0,1);

	$pdf->Cell(95.5,0,'R - Rust',0,0);$pdf->Cell(0,0,'R - Rust',0,1);

	$pdf->Cell(95.5,0,'M - Missing',0,0);$pdf->Cell(0,0,'M - Missing',0,1);

	$pdf->Image('images/vehins.jpg',$x+20,$y,75.5,0,'','','',true);

	$pdf->Image('images/vehins.jpg',110.5+20,$y,75.5,0,'','','',true);

	$pdf->Ln(35);



	$pdf->SetFont('myriad', 'B', 16);

	$pdf->Cell(22.15,1.5*$height,'Status','B',0);$pdf->SetFont('myriad', '', 12);$pdf->Cell(10.8,1.5*$height,'out','B',0,0,0,0,0,0,'','B');$pdf->Cell(2,1.5*$height,'','B',0,0);$pdf->Cell(10.8,1.5*$height,'in','B', 0,0,0,0,0,0,'','B');$pdf->Cell(2,1.5*$height,'','B',0,0);$pdf->Cell(22.15,1.5*$height,'','B',0);$pdf->Cell(10.8,1.5*$height,'out','B',0,0,0,0,0,0,'','B');$pdf->Cell(2,1.5*$height,'','B',0,0);$pdf->Cell(8.8,1.5*$height,'in','B',0,0,0,0,0,0,'','B');$pdf->Cell(4,1.5*$height,'','',0,0);$pdf->SetFont('myriad', 'B', 16);$pdf->Cell(91.5,1.5*$height,'Notes','B',0);$pdf->Cell(4,1.5*$height,'','',1);

	$pdf->SetFont('myriad', '', 9);

	$pdf->Ln(2);

	$height=4;

	$pdf->Cell(22.15,$height,'Radio',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(22.15,$height,'Tool Kit & Jack',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(25,$height,'Date Accident:','',0);$pdf->SetFont('myriad', 'B', 9);$pdf->Cell(50.5,$height,date_format($ddate,"d M, Y"),'',1);

	$pdf->SetFont('myriad', '', 9);

	$pdf->Ln(2);

	$pdf->Cell(22.15,$height,'Tires - Front',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(22.15,$height,'Fuel Cap & Key',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(25,$height,'Car Number:','',0);$pdf->SetFont('myriad', 'B', 9);$pdf->Cell(50.5,$height,$row11['a_number'],'',1);

	$pdf->SetFont('myriad', '', 9);

	$pdf->Ln(2);

	$pdf->Cell(22.15,$height,'Tires - Rear',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(22.15,$height,'Handbook/Doc',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(25,$height,'Additional Driver:','',0);$pdf->SetFont('myriad', 'B', 9);$pdf->Cell(50.5,$height,'','',1);

	$pdf->SetFont('myriad', '', 9);

	$pdf->Ln(2);

	$pdf->Cell(22.15,$height,'Tire - Pressure',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(22.15,$height,'Interior Clean',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(25,$height,'Driver License:','',0);$pdf->SetFont('myriad', 'B', 9);$pdf->Cell(50.5,$height,'','',1);

	$pdf->SetFont('myriad', '', 9);

	$pdf->Ln(2);

	$pdf->Cell(22.15,$height,'Spare Tire',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(22.15,$height,'Exterior Clean',0,0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(6.8,$height,'','TLRB',0);$pdf->Cell(6,$height,'','',0);$pdf->Cell(25,$height,'New Car Damage:','',0);$pdf->SetFont('myriad', 'B', 9);$pdf->Cell(50.5,$height,'','',1);



	$height=0;

	$pdf->Ln(10);

	$pdf->SetFont('myriad', '', 8);

	$pdf->Cell(45,$height,'I accept that the vehicle is in the condition indicated on this form','',0);$pdf->Cell(45.5,$height,'','',0);$pdf->Cell(5,$height,'','',0);$pdf->Cell(45,$height,'The car had been returned and checked. I agree that the damage on this car','',0);$pdf->Cell(50.5,$height,'','',1);

	$pdf->Cell(45,$height,'','',0);$pdf->Cell(45.5,$height,'','',0);$pdf->Cell(5,$height,'','',0);$pdf->Cell(45,$height,'is as stated and accept that the vehicle is in the condition indicated on this form.','',0);$pdf->Cell(50.5,$height,'','',1);

	$height=5;

	$pdf->Ln(10);

	$pdf->SetFont('myriad', '', 10);

	$pdf->Cell(45,$height,'Sign','',0);$pdf->Cell(45.5,$height,'','',0);$pdf->Cell(5,$height,'','',0);$pdf->Cell(45,$height,'Sign','',0);$pdf->Cell(50.5,$height,'','',1);

	$pdf->Cell(45,$height,'Print Name','',0);$pdf->Cell(45.5,$height,'','',0);$pdf->Cell(5,$height,'','',0);$pdf->Cell(45,$height,'Print Name','',0);$pdf->Cell(50.5,$height,'','',1);



	$pdf->AddPage();

	$pdf->SetFont('myriad', 'B', 16);

	$pdf->Cell(0,0,'PURCHASE ORDER AGREEMENT',0,1,'C');

	$pdf->Ln(10);

	$pdf->SetFont('myriad', '', 9);

	if($isTPRental){

		$pdf->Cell(5,0,'1.');$pdf->MultiCell(0,0,'I, the undersigned, the claimant (or any authorized legal representative of mine) am fully aware that in addition to this purchase order agreement (p.o.), the vehicle being provided herewith is also under the arrangement of a separate rental agreement to be issued by the Rental Agency.',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'1.');$pdf->MultiCell(0,0,'I fully and irrevocably agree to pay any and all charges incurred by the usage of the vehicle beyond the date specified on the p.o. unless the right to such usage is explicitly extended by Nagico both in writing and in advance (no exceptions possible whatsoever).',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'2.');$pdf->MultiCell(0,0,'I fully and irrevocably agree to pay any and all charges incurred by the usage of the vehicle beyond the date specified on the p.o. unless the right to such usage is explicitly extended by Nagico both in writing and in advance (no exceptions possible whatsoever). In connection with such an extension I shall have to execute in advance all proper documents at the Rental Agency. ',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'2.');$pdf->MultiCell(0,0,'I fully and irrevocably agree that all expenses for gas/diesel are for my sole account and that I am obliged to return the vehicle with an equivalent amount of fuel as was provided to me upon delivery.',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'3.');$pdf->MultiCell(0,0,'I fully and irrevocably agree that all expenses for gas/diesel are for my sole account and that I am obliged to return the vehicle with an equivalent amount of fuel as was provided to me upon delivery.',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'3.');$pdf->MultiCell(0,0,'I fully and irrevocably agree that all expenses in case of a flat tire are for my sole account and that I am obliged to return the vehicle with the tire condition as was provided to me upon delivery. ',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'4.');$pdf->MultiCell(0,0,'I fully and irrevocably agree that all expenses in case of a flat tire are for my sole account and that I am obliged to return the vehicle with the tire condition as was provided to me upon delivery. ',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'4.');$pdf->MultiCell(0,0,'I am conscious of the fact that smoking is not permitted in the vehicle at any time and that it should be returned in the same condition it was provided to me. In this regard I agree to return the vehicle clean and to pay a cleaning fee in the case the interior and exterior are dirty.',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'5.');$pdf->MultiCell(0,0,'I am conscious of the fact that smoking is not permitted in the vehicle at any time and that it should be returned in the same condition it was provided to me. In this regard I agree to return the vehicle clean and to pay a cleaning fee in the case the interior and exterior are dirty.',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'5.');$pdf->MultiCell(0,0,'I am aware that the vehicle is covered against third party liability only and that I am directly responsible for any fines, defects, loss or damage to the vehicle regardless of fault (including joyriding, theft and vandalism), arisen and/or caused while the vehicle is in my possession and/or under my care. I understand that there is a deductible that applies in the sum of AWG. 750 that is fully for my account.',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'6.');$pdf->MultiCell(0,0,'I am aware that the vehicle is covered against third party liability only and that by declining the additional comprehensive insurance at the Rental Agency, I am directly responsible for any fines, defects, loss or damage to the vehicle regardless of fault (including joyriding, theft and vandalism), arisen and/or caused while the vehicle is in my possession and/or under my care. I understand that there is a deductible that applies in the sum of AWG. 750 that is fully for my account. In line with the aforementioned awareness I understand that acceptance of the additional comprehensive insurance is fully on my account and that it shall be paid in advance.',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'6.');$pdf->MultiCell(0,0,'I agree to have the obligation to always report in advance to Nagico all additional drivers. Nagico shall approve these drivers in advance. In connection with this approval process their driving licenses shall be submitted in advance. Upon approval by Nagico the p.o. shall apply towards the additional drivers.',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'7.');$pdf->MultiCell(0,0,'I agree that all terms and conditions of the rental agreement apply in conjunction with this p.o. Under these terms and conditions fall, amongst others, the obligation to always report in advance both to Nagico and the Rental Agency all additional drivers. Nagico and the Rental Agency shall approve these drivers in advance. In connection with this approval process their driving licenses shall be submitted in advance. Upon approval by both Nagico and the Rental Agency both the p.o. and the rental agreement shall apply towards the additional drivers.',0,'',0,2);

	}

	else{

		$pdf->Cell(5,0,'7.');$pdf->MultiCell(0,0,'I am fully aware that coverage shall not exist in the following circumstances (not a limited list):',0,'',0,2);

		$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used for purposes other than that for which it is destined according to its nature;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to transport persons and or goods against payment;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to give driving lessons (either paid or unpaid);',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to tow other vehicles;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to participate in matches, rallies and similar activities; ',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used for “off road” purposes;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is driven under the influence of alcohol and/or any other substance generally known to even slightly negatively affect the ability to properly drive it regardless of the level of consumption of such substance. In line herewith it is also not allowed, under any circumstances whatsoever, to permit someone else to drive the vehicle who is under such influence, or who is grossly negligent in operation of the vehicle.',0,'',0,2);

	}

	$pdf->Ln(5);

	if($isTPRental){

		$pdf->Cell(5,0,'7.');$pdf->MultiCell(0,0,'I am fully aware that coverage shall not exist in the following circumstances (not a limited list):',0,'',0,2);

		$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used for purposes other than that for which it is destined according to its nature;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to transport persons and or goods against payment;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to give driving lessons (either paid or unpaid);',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to tow other vehicles;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used to participate in matches, rallies and similar activities; ',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is being used or has been used for “off road” purposes;',0,'',0,2);$pdf->ln(2);

		$pdf->Cell(8,0,'');$pdf->MultiCell(0,0,'• if the vehicle is driven under the influence of alcohol and/or any other substance generally known to even slightly negatively affect the ability to properly drive it regardless of the level of consumption of such substance. In line herewith it is also not allowed, under any circumstances whatsoever, to permit someone else to drive the vehicle who is under such influence, or who is grossly negligent in operation of the vehicle.',0,'',0,2);

		$pdf->Ln(5);

	}

	else{

		$pdf->Ln(5);

	}





	$pdf->Ln(15);

	$pdf->SetFont('myriad', '', 12);

	$pdf->Cell(45,0,'Vehicle Return Date: ',0,0);$pdf->SetFont('myriad', 'B', 12);$pdf->Cell(0,0,date("F j, Y ", mktime(0,0,0,substr($time_in_exp,0,2),substr($time_in_exp,3,2),substr($time_in_exp,6,4)))." ".substr($time_in_exp,-5),0,1);$pdf->SetFont('myriad', '', 12);

	$pdf->Cell(45,0,'Date: ',0,0);$pdf->SetFont('myriad', 'B', 12);$pdf->Cell(0,0,date("F j, Y ", mktime(0,0,0,substr($time_out,0,2),substr($time_out,3,2),substr($time_out,6,4))));$pdf->SetFont('myriad', '', 12);

	$pdf->Ln(15);

	$pdf->Cell(15,0,'Sign',0,1);

	$pdf->Cell(15,0,'Print Name',0,1);





	$pdf->Output('NR'."/&".'CS Rental Form'.$id.'.pdf', 'D');

}



?>
