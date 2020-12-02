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
	$filter = decrypt($_REQUEST['id'], enc_key);
	$height=6;
	
	$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Statement');
	$pdf->SetSubject('Statement');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(true,10);
	
	$pdf->AddPage();
	$pdf->SetMargins(10,10,10,true);
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
	$pdf->Ln(15);
	
	
	$sql = "SELECT * FROM service_req WHERE `charged` > 0 AND `delete` =0 ".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$pdf->SetFont('helvetica', 'B', 12);
	$pdf->Cell(120,$height,'Nagico Road & Claims Services N.V.');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(35,$height,'Statement Date:',0,0,'R');
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,date('j F, Y'),'',1);
	$pdf->Ln(2);
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0,$height,'Avenida Milio Croes # 27',0,1);
	$pdf->Cell(0,$height,'Oranjestad, Aruba',0,1);
	$pdf->Cell(0,$height,'588-7000/ 191',0,1);
	$pdf->Ln(3);
	
	$pdf->SetLineWidth(0.5);
	$pdf->SetFillColor(0,0,0,9);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,'Bill To','LTBR',1,'',1);
	
	if($row['bill_to']!=0){
		if($row['bill_to'] > 0){
			$sql2 = "SELECT * FROM insurance_company WHERE id = '$row[bill_to]'";
			$rs2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($rs2);
		}
		else{
			$id = -1*$row['bill_to'];
			$sql2 = "SELECT * FROM clients WHERE id = '$id'";
			$rs2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($rs2);
		}
		$name = $row2['name'];
		$address = $row2['address'];
		$city = $row2['city'];
	}
	else{
		$sql3 = "SELECT * FROM vehicles_2 WHERE PolicyNo='".$row['pol']."'";
		$rs3 = mysql_query($sql3);
		$row3 = mysql_fetch_array($rs3);
		$name = $row3['Full_Name'];
		$address = $row3['Address1'];	
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
	$pdf->Cell(97,$height,'Statement','LTBR',0,'',1);
	$pdf->Cell(0,$height,'Payment Terms: Net 15 Days','LTBR',0,'',1);
	$pdf->Ln(12);
	
	$col1=16;
	$col2=28;
	$pdf->SetFont('helvetica', '', 9);
	$pdf->SetFillColor(20, 133, 64);
	$pdf->SetTextColor(255,255,255);
	$pdf->Cell($col1+10,$height,'Date','LTB',0,'C',1);
	$pdf->Cell($col1,$height,'Receipt','TB',0,'C',1);
	$pdf->Cell($col1,$height,'PO #','TB',0,'C',1);
	$pdf->Cell($col2-3,$height,'License Plate','TB',0,'C',1);
	$pdf->Cell($col1,$height,'Claims #','TB',0,'C',1);
	$pdf->Cell($col2,$height,'Vehicle Description','TB',0,'C',1);
	$pdf->Cell($col2,$height,'Towed From','TB',0,'C',1);
	$pdf->Cell($col2,$height,'Towed To','TB',0,'C',1);
	$pdf->Cell(0,$height,'Charges','TBR',1,'C',1);
	$pdf->Cell(0,$height,'','LR',1);
	
	$subtotal = $row['charged'];
	$total_height = 0;
	$count = 1;
	$page = 1;
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('helvetica', '', 8);
	$pdf->Cell($col1+10,$height,$row['opendt'],'L',0,'C');
	$pdf->Cell($col1,$height,$row['receipt'],'',0,'C');
	$pdf->Cell($col1,$height,$row['po_number'],'',0,'C');
	$pdf->Cell($col2-3,$height,$row['a_number'],'',0,'C');
	$pdf->Cell($col1,$height,'','',0,'C');
	$pdf->Cell($col2,$height,$row['car'],'',0,'C');
	$pdf->Cell($col2,$height,substr($row['location'],0,20),'',0,'C');
	$pdf->Cell($col2,$height,substr($row['to_location'],0,20),'',0,'C');
	$pdf->Cell(0,$height,number_format($row['charged'],2),'R',1,'R');
	
	while($row = mysql_fetch_array($rs)){
		if(($count == 14 && $page==1) || $count == 26){
			$pdf->Cell(0,6,'','LRB',1);
			$pdf->sety(-50);
			$pdf->SetFont('helvetica', '', 10);
			$pdf->Cell(0,$height,'Please remit payment to Nagico Road & Claims Service N.V. Referance/ Inv #','',1,'C');
			$pdf->Ln(6);
			$pdf->Cell(0,$height,'Aruba Bank: 2614670190      KvK: 40068.0','',1);
			$pdf->Ln(12);
			$pdf->SetLeftMargin(40);
			$pdf->SetRightMargin(40);
			$pdf->Cell(0,$height,'Thank you for your business','T',1,'C');
			$pdf->AddPage();
			$pdf->SetMargins(10,10,10,true);
			$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
			$pdf->Ln(15);
			$pdf->SetFont('helvetica', '', 9);
			$pdf->SetFillColor(20, 133, 64);
			$pdf->SetTextColor(255,255,255);
			$pdf->Cell($col1+10,$height,'Date','LTB',0,'C',1);
			$pdf->Cell($col1,$height,'Receipt','TB',0,'C',1);
			$pdf->Cell($col1,$height,'PO #','TB',0,'C',1);
			$pdf->Cell($col2-3,$height,'License Plate','TB',0,'C',1);
			$pdf->Cell($col1,$height,'Claims #','TB',0,'C',1);
			$pdf->Cell($col2,$height,'Vehicle Description','TB',0,'C',1);
			$pdf->Cell($col2,$height,'Towed From','TB',0,'C',1);
			$pdf->Cell($col2,$height,'Towed To','TB',0,'C',1);
			$pdf->Cell(0,$height,'Charges','TBR',1,'C',1);
			$pdf->Cell(0,$height,'','LR',1);
			$count=0;
			$page++;	
		}
		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->Cell($col1+10,$height,$row['opendt'],'L',0,'C');
		$pdf->Cell($col1,$height,$row['receipt'],'',0,'C');
		$pdf->Cell($col1,$height,$row['po_number'],'',0,'C');
		$pdf->Cell($col2-3,$height,$row['a_number'],'',0,'C');
		$pdf->Cell($col1,$height,'','',0,'C');
		$pdf->Cell($col2,$height,$row['car'],'',0,'C');
		$pdf->Cell($col2,$height,substr($row['location'],0,20),'',0,'C');
		$pdf->Cell($col2,$height,substr($row['to_location'],0,20),'',0,'C');
		$pdf->Cell(0,$height,number_format($row['charged'],2),'R',1,'R');
		
		$subtotal = $subtotal + $row['charged'];
		$total_height = $total_height + $height;
		$count++;
	}
	
	
	$pdf->Cell(175,$height,'Subtotal','L',0,'R');
	$pdf->Cell(0,$height,number_format($subtotal,2),'R',1,'R');
	$pdf->Cell(175,$height,'BBO 1.5%','L',0,'R');
	$pdf->Cell(0,$height,number_format($subtotal*0.015,2),'R',1,'R');
	$pdf->Cell(175,$height,'Health Tax 3.0%','L',0,'R');
	$pdf->Cell(0,$height,number_format($subtotal*0.03,2),'R',1,'R');
	$pdf->Cell(175,$height,'BAVP 1.5%','L',0,'R');
	$pdf->Cell(0,$height,number_format($subtotal*0.015,2),'R',1,'R');
	$pdf->Cell(0,6,'','LR',1);
	$pdf->Cell(175,$height,'Total','L',0,'R');
	$pdf->Cell(0,$height,'Afl. '.number_format(1.06*$subtotal,2),'R',1,'R');
	$pdf->Cell(0,6,'','LRB',1);
	$pdf->sety(-50);
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,'Please remit payment to Nagico Road & Claims Services N.V. Referance/ Inv #','',1,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,$height,'Aruba Bank: 2614670190      KvK: 40068.0','',1);

	$pdf->Ln(12);
	$pdf->SetLeftMargin(40);
	$pdf->SetRightMargin(40);
	$pdf->Cell(0,$height,'Thank you for your business','T',1,'C');
	$pdf->Output('Statement_'.$id.'.pdf', 'D');	
}


?>