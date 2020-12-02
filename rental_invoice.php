<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

if($_SESSION['user_level'] < POWER_LEVEL && !checkView()) {
header("Location: index.php");
exit();
}
else{

	require_once('tcpdf/tcpdf.php');
	$id = $_REQUEST['id'];
	$height=6;

	$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Rental Invoice');
	$pdf->SetSubject('Rental Invoice');

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(true,10);

	$pdf->AddPage();
	$pdf->SetMargins(10,10,10,true);
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
	$pdf->Ln(15);
	$sql = "SELECT * FROM rental WHERE `id`='$id'";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$sql2 = "SELECT * FROM rental_vehicle WHERE id='".$row['rental_vehicle_id']."'";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->SetFont('helvetica', 'B', 12);
	$pdf->Cell(120,$height,'Nagico Road & Claims Service N.V.');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(35,$height,'Statement Date:',0,0,'R');
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,date('j F, Y'),'',1);
	$pdf->Ln(2);
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0,$height,'Avenida Milio Croes #27',0,1);
	$pdf->Cell(0,$height,'Oranjestad, Aruba',0,1);
	$pdf->Cell(0,$height,'588-7000/ 191',0,1);
	$pdf->Ln(3);

	$pdf->SetLineWidth(0.5);
	$pdf->SetFillColor(0,0,0,9);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,'Bill To','LTBR',1,'',1);

	if($row['bill_to']==1){
		$sql3 = "SELECT * FROM country_info";
		$rs3 = mysql_query($sql3);
		$row3 = mysql_fetch_array($rs3);

		$name = 'Nagico '.$row3['country'];
		$address = $row3['address1'];
		$city = $row3['state'].', '.$row3['country'];
	}
	else{
		$sql3 = "SELECT * FROM drivers_license WHERE id='".$row['extra_drv']."'";
		$rs3 = mysql_query($sql3);
		$row3 = mysql_fetch_array($rs3);

		$name = $row3['firstName'].' '.$row3['lastName'].' ('.$row['extra_drv'].')';
		$address = $row3['address'];
		$city = '';

	}

	$pdf->SetLineWidth(0.3);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,$name,'LR',1);
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,$address,'LR',1);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,$city,'LRB',1);



	$pdf->Ln(12);
	$pdf->SetLineWidth(0.5);
	$pdf->Cell(97,$height,'Invoice: '.$id,'LTR',0,'',1);
	$pdf->Cell(0,$height,'Payment Terms: Net 15 Days','LTR',1,'',1);
	$pdf->Cell(97,$height,'Claim No: '.$row['claimNo'],'LBR',0,'',1);
	$pdf->Cell(0,$height,'Requeted By: Nagico','LBR',0,'',1);
	$pdf->Ln(12);

	$col1=16;
	$col2=28;
	$pdf->SetFont('helvetica', '', 9);
	$pdf->SetFillColor(20, 133, 64);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell($col2+5,$height,'Day/Time Out','LTB',0,'C',1);
	$pdf->Cell($col2+5,$height,'Day/Time In','TB',0,'C',1);
	$pdf->Cell($col2-3,$height,'License Plate','TB',0,'C',1);
	$pdf->Cell($col2,$height,'Vehicle Description','TB',0,'C',1);
	$pdf->Cell($col2,$height,'Rate','TB',0,'C',1);
	$pdf->Cell($col2,$height,'Days','TB',0,'C',1);
	$pdf->Cell(0,$height,'Total','TBR',1,'R',1);
	$pdf->Cell(0,$height,'','LR',1);

	//***************************
	$time_out = $row['time_out'];
	$time_in = $row['time_in'];
	list($month,$day,$year) = explode('-',substr($time_out,0,10));
	list($date,$time) = explode(' ',$time_out);
	$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
	$date2 = new DateTime(date("Y-m-d H:i"));
	if(strlen(trim($time_in))!=0){
		list($month,$day,$year) = explode('-',substr($time_in,0,10));
		list($date,$time) = explode(' ',$time_in);
		$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
	}
	$interval = $date1->diff($date2);
	if($interval->h==0){
		$total_charge = $row['rate']*$interval->days;
		$days_rent = $interval->days;
	}
	else{
		$total_charge = $row['rate']*($interval->days+1);
		$days_rent = ($interval->days+1);
	}

	//*******************************************

	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('helvetica', '', 8);
	$pdf->Cell($col2+5,$height,$row['time_out'],'L',0,'C');
	$pdf->Cell($col2+5,$height,$row['time_in'],'',0,'C');
	$pdf->Cell($col2-3,$height,$row2['licenseplate'],'',0,'C');
	$pdf->Cell($col2,$height,$row2['make'].' '.$row2['model'].' '.$row2['year'],'',0,'C');
	$pdf->Cell($col2,$height,number_format($row['rate'],2),'',0,'C');
	$pdf->Cell($col2,$height,$days_rent,'',0,'C');
	$pdf->Cell(0,$height,number_format($total_charge,2),'R',1,'R');
	$pdf->Cell(0,$height,'','LR',1,'C');

	$pdf->Cell(0,44,'','LR',1);
	$discount = $row['discount'];
	$tdisc  =$total_charge*$discount/100;
	if($discount != 0){
		$pdf->Cell(175,$height,'Discount '.$discount.'%','L',0,'R');
		$pdf->Cell(0,$height,'-'.number_format($tdisc,2),'R',1,'R');
	}
	/*else if($days_rent > 14 && $row['bill_to']==1){ //Rent more then 14 days discount for Nagico
		$pdf->Cell(175,$height,'','L',0,'R');
		$tdisc=(($row['rate']-45)*($days_rent-14));
		$pdf->Cell(0,$height,'-'.number_format($tdisc,2),'R',1,'R');
	}*/
	else{
		$pdf->Cell(0,4,'','LR',1);
	}
	//$pdf->Cell(175,$height,'Subtotal','L',0,'R');
	//$pdf->Cell(0,$height,number_format($total_charge-$tdisc,2),'R',1,'R');
	//$pdf->Cell(175,$height,'BBO 1.5%','L',0,'R');
	//$pdf->Cell(0,$height,number_format(($total_charge-$tdisc)*0.015,2),'R',1,'R');
	//$pdf->Cell(175,$height,'Health Tax 3.0%','L',0,'R');
//$pdf->Cell(0,$height,number_format(($total_charge-$tdisc)*0.03,2),'R',1,'R');
	//$pdf->Cell(175,$height,'BAVP 1.5%','L',0,'R');
	//$pdf->Cell(0,$height,number_format(($total_charge-$tdisc)*0.015,2),'R',1,'R');
	//$pdf->Cell(0,6,'','LR',1);
	$pdf->Cell(175,$height,'Total','L',0,'R');
	$pdf->Cell(0,$height,'Afl. '.number_format(($total_charge-$tdisc),2),'R',1,'R');
	$pdf->Cell(0,6,'','LRB',1);
	$pdf->Ln(18);
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,'Please remit payment to Nagico Road & Claims Service N.V. Referance/ Inv #','',1,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,$height,'Aruba Bank: 2614670190      KvK: 40068.0','',1);

	$pdf->Ln(6);
	$pdf->SetLeftMargin(40);
	$pdf->SetRightMargin(40);
	$pdf->Cell(0,$height,'Thank you for your business','T',1,'C');
	$pdf->Output('NR'."/&".'CS Rental Inv'.$id.' '.str_replace('/','',$row['claimNo']).'.pdf', 'D');
}

?>
